"""
FastAPI Prediction Service

Provides REST API endpoints for:
- Single customer churn prediction
- Batch predictions
- Model health checks
- Feature importance
- Customer risk scoring and prioritization
"""

from fastapi import FastAPI, HTTPException, BackgroundTasks
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import List, Dict, Optional, Any
import pandas as pd
import numpy as np
import joblib
import logging
from datetime import datetime
import json
from pathlib import Path

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Initialize FastAPI app
app = FastAPI(
    title="Customer Churn Prediction API",
    description="Predictive Business Intelligence API for customer churn analysis",
    version="1.0.0",
    docs_url="/docs",
    redoc_url="/redoc"
)

# Enable CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Configure appropriately for production
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Global variables
model_data = None
feature_names = None
is_model_loaded = False


class CustomerInput(BaseModel):
    """Input schema for single customer prediction."""
    customer_id: str
    tenure: int = Field(..., description="Customer tenure in months")
    monthly_charges: float = Field(..., description="Monthly charges in dollars")
    total_charges: float = Field(..., description="Total charges to date")
    contract_type: str = Field(..., description="Contract type: Month-to-month, One year, or Two year")
    payment_method: str = Field(..., description="Payment method")
    num_services: int = Field(default=0, description="Number of services subscribed")
    support_tickets: int = Field(default=0, description="Support tickets in last 3 months")
    avg_monthly_usage: float = Field(default=0, description="Average monthly usage in GB")
    usage_trend: str = Field(default="Stable", description="Usage trend: Increasing, Stable, or Declining")
    segment: str = Field(default="Consumer", description="Customer segment: Enterprise, SMB, or Consumer")
    senior_citizen: int = Field(default=0, description="Is senior citizen (0 or 1)")
    dependents: str = Field(default="No", description="Has dependents (Yes or No)")
    partner: str = Field(default="No", description="Has partner (Yes or No)")
    internet_service: str = Field(default="No", description="Internet service type")
    phone_service: str = Field(default="No", description="Phone service (Yes or No)")


class BatchPredictionInput(BaseModel):
    """Input schema for batch predictions."""
    customers: List[CustomerInput]


class PredictionResponse(BaseModel):
    """Response schema for predictions."""
    customer_id: str
    churn_probability: float
    churn_risk: str  # Low, Medium, High
    risk_score: int
    key_factors: List[str]
    recommended_action: str
    prediction_timestamp: datetime


class ModelHealthResponse(BaseModel):
    """Response schema for model health check."""
    status: str
    model_name: str
    model_loaded: bool
    feature_count: int
    last_prediction: Optional[datetime]
    uptime_seconds: float


# Model loading functions
def load_model(model_path: str = "models/best_model.pkl"):
    """Load the trained model."""
    global model_data, feature_names, is_model_loaded
    
    try:
        model_path = Path(model_path)
        if not model_path.exists():
            logger.warning(f"Model file not found at {model_path}")
            return False
        
        model_data = joblib.load(model_path)
        feature_names = model_data.get('feature_names', [])
        is_model_loaded = True
        
        logger.info(f"Model loaded successfully: {model_data.get('model_name', 'Unknown')}")
        logger.info(f"Feature count: {len(feature_names)}")
        
        return True
    
    except Exception as e:
        logger.error(f"Error loading model: {str(e)}")
        is_model_loaded = False
        return False


def prepare_features(customer_input: CustomerInput) -> pd.DataFrame:
    """
    Prepare features from customer input for model prediction.
    This replicates the feature engineering logic from training.
    """
    # Create base dataframe
    data = {
        'customer_id': [customer_input.customer_id],
        'tenure': [customer_input.tenure],
        'monthly_charges': [customer_input.monthly_charges],
        'total_charges': [customer_input.total_charges],
        'contract_type': [customer_input.contract_type],
        'payment_method': [customer_input.payment_method],
        'num_services': [customer_input.num_services],
        'support_tickets': [customer_input.support_tickets],
        'avg_monthly_usage': [customer_input.avg_monthly_usage],
        'usage_trend': [customer_input.usage_trend],
        'segment': [customer_input.segment],
        'senior_citizen': [customer_input.senior_citizen],
        'dependents': [customer_input.dependents],
        'partner': [customer_input.partner],
        'internet_service': [customer_input.internet_service],
        'phone_service': [customer_input.phone_service]
    }
    
    df = pd.DataFrame(data)
    
    # Feature engineering (simplified version - should match training)
    
    # Tenure features
    df['is_new_customer'] = (df['tenure'] < 6).astype(int)
    df['tenure_squared'] = df['tenure'] ** 2
    df['log_tenure'] = np.log1p(df['tenure'])
    
    # Financial features
    df['avg_charge_per_month'] = df['total_charges'] / (df['tenure'] + 1)
    df['charge_ratio'] = df['monthly_charges'] / 70.0  # Approximate mean
    df['is_high_value'] = (df['monthly_charges'] > 90).astype(int)
    df['charges_per_tenure'] = df['total_charges'] / np.maximum(df['tenure'], 1)
    
    # Engagement features
    df['service_density'] = df['num_services'] / np.maximum(df['tenure'], 1)
    df['tickets_per_month'] = df['support_tickets'] / np.maximum(df['tenure'], 1)
    df['high_support_need'] = (df['support_tickets'] > 3).astype(int)
    df['low_usage'] = (df['avg_monthly_usage'] < 25).astype(int)  # Approximate quartile
    
    trend_map = {'Declining': -1, 'Stable': 0, 'Increasing': 1}
    df['usage_trend_encoded'] = df['usage_trend'].map(trend_map)
    
    # Contract features
    contract_risk_map = {'Month-to-month': 3, 'One year': 2, 'Two year': 1}
    df['contract_risk_score'] = df['contract_type'].map(contract_risk_map)
    df['is_flexible_contract'] = (df['contract_type'] == 'Month-to-month').astype(int)
    
    payment_risk_map = {
        'Electronic check': 3,
        'Mailed check': 2,
        'Bank transfer': 1,
        'Credit card': 1
    }
    df['payment_risk_score'] = df['payment_method'].map(payment_risk_map)
    df['has_auto_pay'] = df['payment_method'].isin(['Bank transfer', 'Credit card']).astype(int)
    
    # Demographic features
    df['household_size'] = (
        1 +
        (df['partner'] == 'Yes').astype(int) +
        (df['dependents'] == 'Yes').astype(int) * 2
    )
    df['is_single_household'] = ((df['partner'] == 'No') & (df['dependents'] == 'No')).astype(int)
    df['is_senior'] = df['senior_citizen']
    
    segment_value_map = {'Enterprise': 3, 'SMB': 2, 'Consumer': 1}
    df['segment_value_score'] = df['segment'].map(segment_value_map)
    
    # Risk indicators
    risk_factors = []
    risk_factors.append(df['is_new_customer'] * 2)
    risk_factors.append(df['is_flexible_contract'] * 3)
    risk_factors.append(df['high_support_need'] * 2)
    risk_factors.append((df['usage_trend_encoded'] == -1).astype(int) * 2)
    risk_factors.append((df['payment_risk_score'] >= 3).astype(int))
    
    df['churn_risk_score'] = sum(risk_factors)
    df['high_risk_flag'] = (df['churn_risk_score'] >= 5).astype(int)
    
    # One-hot encoding for categorical variables
    categorical_cols = ['contract_type', 'payment_method', 'usage_trend', 'segment', 
                       'dependents', 'partner', 'internet_service', 'phone_service']
    
    for col in categorical_cols:
        if col in df.columns:
            dummies = pd.get_dummies(df[col], prefix=col, drop_first=True)
            df = pd.concat([df, dummies], axis=1)
            df = df.drop(col, axis=1)
    
    # Select only the features used in training
    # For missing features, fill with 0
    expected_features = [f for f in feature_names if f != 'customer_id']
    
    for feat in expected_features:
        if feat not in df.columns:
            df[feat] = 0
    
    # Select and order features
    X = df[expected_features]
    
    return X


def get_key_factors(customer_input: CustomerInput, probability: float) -> List[str]:
    """Identify key factors contributing to churn risk."""
    factors = []
    
    if customer_input.tenure < 6:
        factors.append("New customer (tenure < 6 months)")
    
    if customer_input.contract_type == "Month-to-month":
        factors.append("Flexible contract (month-to-month)")
    
    if customer_input.support_tickets > 3:
        factors.append(f"High support ticket count ({customer_input.support_tickets})")
    
    if customer_input.usage_trend == "Declining":
        factors.append("Declining usage pattern")
    
    if customer_input.payment_method == "Electronic check":
        factors.append("Electronic check payment method")
    
    if customer_input.monthly_charges > 90:
        factors.append(f"High monthly charges (${customer_input.monthly_charges:.2f})")
    
    if customer_input.num_services < 2:
        factors.append("Low service adoption")
    
    if probability > 0.7:
        factors.append("Very high predicted churn probability")
    
    return factors if factors else ["No major risk factors identified"]


def get_recommended_action(probability: float, risk_factors: List[str]) -> str:
    """Generate recommended action based on churn risk."""
    if probability >= 0.7:
        return "URGENT: Immediate retention intervention required. Offer personalized discount or upgrade."
    elif probability >= 0.5:
        return "HIGH PRIORITY: Schedule customer success call within 48 hours. Review account issues."
    elif probability >= 0.3:
        return "MEDIUM PRIORITY: Add to watchlist. Monitor usage patterns. Send engagement email."
    else:
        return "LOW PRIORITY: Continue normal engagement. Consider upsell opportunities."


# API Endpoints
@app.on_event("startup")
async def startup_event():
    """Load model on startup."""
    logger.info("Starting up Churn Prediction API...")
    load_model()


@app.get("/", tags=["Root"])
async def root():
    """Root endpoint with API information."""
    return {
        "message": "Customer Churn Prediction API",
        "version": "1.0.0",
        "documentation": "/docs",
        "health": "/health",
        "predict": "/predict",
        "batch_predict": "/batch_predict"
    }


@app.get("/health", response_model=ModelHealthResponse, tags=["Health"])
async def health_check():
    """Check model health and status."""
    return ModelHealthResponse(
        status="healthy" if is_model_loaded else "unhealthy",
        model_name=model_data.get('model_name', 'Unknown') if model_data else 'None',
        model_loaded=is_model_loaded,
        feature_count=len(feature_names) if feature_names else 0,
        last_prediction=datetime.now(),
        uptime_seconds=0.0  # Would track properly in production
    )


@app.post("/predict", response_model=PredictionResponse, tags=["Predictions"])
async def predict_churn(customer: CustomerInput):
    """
    Predict churn probability for a single customer.
    
    Returns:
        - churn_probability: Probability of churning (0-1)
        - churn_risk: Risk category (Low/Medium/High)
        - risk_score: Composite risk score (0-10)
        - key_factors: Top factors contributing to churn risk
        - recommended_action: Suggested business action
    """
    if not is_model_loaded:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    try:
        # Prepare features
        X = prepare_features(customer)
        
        # Get prediction
        model = model_data['model']
        scaler = model_data['scaler']
        
        # Scale features
        X_scaled = scaler.transform(X)
        
        # Predict probability
        churn_prob = float(model.predict_proba(X_scaled)[0, 1])
        
        # Determine risk category
        if churn_prob >= 0.7:
            risk_level = "High"
            risk_score = min(10, int(churn_prob * 10) + 2)
        elif churn_prob >= 0.5:
            risk_level = "Medium"
            risk_score = min(10, int(churn_prob * 10) + 1)
        else:
            risk_level = "Low"
            risk_score = max(1, int(churn_prob * 10))
        
        # Get key factors
        key_factors = get_key_factors(customer, churn_prob)
        
        # Get recommended action
        recommended_action = get_recommended_action(churn_prob, key_factors)
        
        return PredictionResponse(
            customer_id=customer.customer_id,
            churn_probability=round(churn_prob, 4),
            churn_risk=risk_level,
            risk_score=risk_score,
            key_factors=key_factors,
            recommended_action=recommended_action,
            prediction_timestamp=datetime.now()
        )
    
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Prediction failed: {str(e)}")


@app.post("/batch_predict", tags=["Predictions"])
async def batch_predict_churn(batch: BatchPredictionInput):
    """
    Predict churn for multiple customers.
    
    Returns list of predictions sorted by churn probability (highest first).
    """
    if not is_model_loaded:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    if len(batch.customers) > 1000:
        raise HTTPException(status_code=400, detail="Batch size limit is 1000 customers")
    
    try:
        predictions = []
        
        for customer in batch.customers:
            # Prepare features
            X = prepare_features(customer)
            
            # Get prediction
            model = model_data['model']
            scaler = model_data['scaler']
            
            X_scaled = scaler.transform(X)
            churn_prob = float(model.predict_proba(X_scaled)[0, 1])
            
            # Determine risk category
            if churn_prob >= 0.7:
                risk_level = "High"
                risk_score = min(10, int(churn_prob * 10) + 2)
            elif churn_prob >= 0.5:
                risk_level = "Medium"
                risk_score = min(10, int(churn_prob * 10) + 1)
            else:
                risk_level = "Low"
                risk_score = max(1, int(churn_prob * 10))
            
            key_factors = get_key_factors(customer, churn_prob)
            recommended_action = get_recommended_action(churn_prob, key_factors)
            
            predictions.append({
                "customer_id": customer.customer_id,
                "churn_probability": round(churn_prob, 4),
                "churn_risk": risk_level,
                "risk_score": risk_score,
                "key_factors": key_factors,
                "recommended_action": recommended_action,
                "prediction_timestamp": datetime.now().isoformat()
            })
        
        # Sort by churn probability (descending)
        predictions.sort(key=lambda x: x['churn_probability'], reverse=True)
        
        # Add summary statistics
        high_risk_count = sum(1 for p in predictions if p['churn_risk'] == 'High')
        avg_probability = sum(p['churn_probability'] for p in predictions) / len(predictions)
        
        return {
            "total_customers": len(predictions),
            "high_risk_count": high_risk_count,
            "medium_risk_count": sum(1 for p in predictions if p['churn_risk'] == 'Medium'),
            "low_risk_count": sum(1 for p in predictions if p['churn_risk'] == 'Low'),
            "average_churn_probability": round(avg_probability, 4),
            "predictions": predictions
        }
    
    except Exception as e:
        logger.error(f"Batch prediction error: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Batch prediction failed: {str(e)}")


@app.get("/feature-importance", tags=["Model Info"])
async def get_feature_importance():
    """Get model feature importance rankings."""
    if not is_model_loaded:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    try:
        model = model_data['model']
        
        if hasattr(model, 'feature_importances_'):
            importances = model.feature_importances_
        elif hasattr(model, 'coef_'):
            importances = np.abs(model.coef_[0])
        else:
            raise HTTPException(status_code=500, detail="Model does not support feature importance")
        
        importance_df = pd.DataFrame({
            'feature': feature_names,
            'importance': importances
        }).sort_values('importance', ascending=False)
        
        return {
            "model_name": model_data.get('model_name', 'Unknown'),
            "feature_count": len(importance_df),
            "top_features": importance_df.head(20).to_dict('records')
        }
    
    except Exception as e:
        logger.error(f"Feature importance error: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))


@app.get("/model-info", tags=["Model Info"])
async def get_model_info():
    """Get detailed model information."""
    if not is_model_loaded:
        raise HTTPException(status_code=503, detail="Model not loaded")
    
    return {
        "model_name": model_data.get('model_name', 'Unknown'),
        "feature_count": len(feature_names),
        "features": feature_names[:50],  # First 50 features
        "results": model_data.get('results', {}),
        "model_type": str(type(model_data['model']))
    }


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
