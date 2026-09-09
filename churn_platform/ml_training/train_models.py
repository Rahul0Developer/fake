"""
ML Training Module

Implements:
- Multiple ML models (Logistic Regression, Random Forest, XGBoost)
- MLflow experiment tracking
- Hyperparameter tuning
- Model evaluation with business metrics
- Model persistence
"""

import pandas as pd
import numpy as np
from typing import Dict, Tuple, Optional, List
import logging
import joblib
import mlflow
import mlflow.sklearn
from pathlib import Path

from sklearn.model_selection import train_test_split, cross_val_score, GridSearchCV
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression
from sklearn.ensemble import RandomForestClassifier, GradientBoostingClassifier
from sklearn.metrics import (
    accuracy_score, precision_score, recall_score, f1_score,
    roc_auc_score, confusion_matrix, classification_report,
    precision_recall_curve, roc_curve
)

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class ChurnModelTrainer:
    """Train and evaluate churn prediction models."""
    
    def __init__(self, experiment_name: str = "customer_churn_prediction"):
        self.experiment_name = experiment_name
        self.models = {}
        self.results = {}
        self.best_model = None
        self.best_model_name = None
        self.scaler = StandardScaler()
        self.feature_names = []
        
        # Set up MLflow experiment
        mlflow.set_experiment(experiment_name)
        logger.info(f"MLflow experiment set: {experiment_name}")
    
    def prepare_data(self, X: pd.DataFrame, y: pd.Series, 
                    test_size: float = 0.2, random_state: int = 42) -> Tuple:
        """
        Split data into train/test sets and scale features.
        """
        self.feature_names = X.columns.tolist()
        
        # Train-test split
        X_train, X_test, y_train, y_test = train_test_split(
            X, y, test_size=test_size, random_state=random_state, stratify=y
        )
        
        # Scale features
        X_train_scaled = self.scaler.fit_transform(X_train)
        X_test_scaled = self.scaler.transform(X_test)
        
        # Convert back to DataFrame for better tracking
        X_train_scaled = pd.DataFrame(X_train_scaled, columns=self.feature_names, index=X_train.index)
        X_test_scaled = pd.DataFrame(X_test_scaled, columns=self.feature_names, index=X_test.index)
        
        logger.info(f"Data prepared: {len(X_train)} train, {len(X_test)} test samples")
        logger.info(f"Churn rate - Train: {y_train.mean():.2%}, Test: {y_test.mean():.2%}")
        
        return X_train_scaled, X_test_scaled, y_train, y_test
    
    def train_logistic_regression(self, X_train, y_train, 
                                  C: float = 1.0, 
                                  max_iter: int = 1000) -> LogisticRegression:
        """Train Logistic Regression model."""
        model = LogisticRegression(
            C=C,
            max_iter=max_iter,
            random_state=42,
            solver='lbfgs',
            class_weight='balanced'
        )
        model.fit(X_train, y_train)
        logger.info("Logistic Regression model trained")
        return model
    
    def train_random_forest(self, X_train, y_train,
                           n_estimators: int = 100,
                           max_depth: int = 10,
                           min_samples_split: int = 5) -> RandomForestClassifier:
        """Train Random Forest model."""
        model = RandomForestClassifier(
            n_estimators=n_estimators,
            max_depth=max_depth,
            min_samples_split=min_samples_split,
            random_state=42,
            class_weight='balanced',
            n_jobs=-1
        )
        model.fit(X_train, y_train)
        logger.info(f"Random Forest model trained ({n_estimators} trees)")
        return model
    
    def train_gradient_boosting(self, X_train, y_train,
                               n_estimators: int = 100,
                               learning_rate: float = 0.1,
                               max_depth: int = 5) -> GradientBoostingClassifier:
        """Train Gradient Boosting model."""
        model = GradientBoostingClassifier(
            n_estimators=n_estimators,
            learning_rate=learning_rate,
            max_depth=max_depth,
            random_state=42
        )
        model.fit(X_train, y_train)
        logger.info(f"Gradient Boosting model trained ({n_estimators} estimators)")
        return model
    
    def evaluate_model(self, model, X_test, y_test, model_name: str) -> Dict:
        """
        Comprehensive model evaluation.
        """
        # Predictions
        y_pred = model.predict(X_test)
        y_pred_proba = model.predict_proba(X_test)[:, 1] if hasattr(model, 'predict_proba') else model.decision_function(X_test)
        
        # Basic metrics
        metrics = {
            'model_name': model_name,
            'accuracy': accuracy_score(y_test, y_pred),
            'precision': precision_score(y_test, y_pred),
            'recall': recall_score(y_test, y_pred),
            'f1_score': f1_score(y_test, y_pred),
            'roc_auc': roc_auc_score(y_test, y_pred_proba)
        }
        
        # Confusion matrix
        cm = confusion_matrix(y_test, y_pred)
        metrics['confusion_matrix'] = {
            'true_negative': int(cm[0, 0]),
            'false_positive': int(cm[0, 1]),
            'false_negative': int(cm[1, 0]),
            'true_positive': int(cm[1, 1])
        }
        
        # Business metrics
        # Cost of false negative (missed churn) vs false positive (unnecessary intervention)
        # Assume: False negative costs $500 (lost customer), False positive costs $50 (intervention cost)
        fn_cost = 500
        fp_cost = 50
        total_cost = (cm[1, 0] * fn_cost) + (cm[0, 1] * fp_cost)
        metrics['estimated_business_cost'] = total_cost
        
        # Precision-Recall curve
        precision_curve, recall_curve, pr_thresholds = precision_recall_curve(y_test, y_pred_proba)
        metrics['pr_auc'] = np.trapz(precision_curve, recall_curve)
        
        logger.info(f"\n{model_name} Evaluation:")
        logger.info(f"  Accuracy:  {metrics['accuracy']:.4f}")
        logger.info(f"  Precision: {metrics['precision']:.4f}")
        logger.info(f"  Recall:    {metrics['recall']:.4f}")
        logger.info(f"  F1 Score:  {metrics['f1_score']:.4f}")
        logger.info(f"  ROC-AUC:   {metrics['roc_auc']:.4f}")
        logger.info(f"  PR-AUC:    {metrics['pr_auc']:.4f}")
        logger.info(f"  Business Cost: ${total_cost:,.0f}")
        
        return metrics
    
    def get_feature_importance(self, model, top_n: int = 10) -> pd.DataFrame:
        """Extract feature importance from model."""
        if hasattr(model, 'feature_importances_'):
            importances = model.feature_importances_
        elif hasattr(model, 'coef_'):
            importances = np.abs(model.coef_[0])
        else:
            logger.warning("Model does not support feature importance")
            return pd.DataFrame()
        
        importance_df = pd.DataFrame({
            'feature': self.feature_names,
            'importance': importances
        }).sort_values('importance', ascending=False)
        
        logger.info(f"Top {top_n} important features:")
        for idx, row in importance_df.head(top_n).iterrows():
            logger.info(f"  {row['feature']}: {row['importance']:.4f}")
        
        return importance_df
    
    def tune_hyperparameters(self, X_train, y_train, model_type: str = 'random_forest',
                            cv: int = 5) -> Tuple:
        """
        Hyperparameter tuning using GridSearchCV.
        """
        logger.info(f"Tuning hyperparameters for {model_type}...")
        
        if model_type == 'logistic_regression':
            param_grid = {
                'C': [0.01, 0.1, 1, 10],
                'max_iter': [500, 1000, 2000]
            }
            base_model = LogisticRegression(random_state=42, class_weight='balanced')
        
        elif model_type == 'random_forest':
            param_grid = {
                'n_estimators': [50, 100, 200],
                'max_depth': [5, 10, 15, None],
                'min_samples_split': [2, 5, 10]
            }
            base_model = RandomForestClassifier(random_state=42, class_weight='balanced', n_jobs=-1)
        
        elif model_type == 'gradient_boosting':
            param_grid = {
                'n_estimators': [50, 100, 200],
                'learning_rate': [0.01, 0.1, 0.2],
                'max_depth': [3, 5, 7]
            }
            base_model = GradientBoostingClassifier(random_state=42)
        
        else:
            raise ValueError(f"Unknown model type: {model_type}")
        
        grid_search = GridSearchCV(
            base_model,
            param_grid,
            cv=cv,
            scoring='roc_auc',
            n_jobs=-1,
            verbose=1
        )
        
        grid_search.fit(X_train, y_train)
        
        logger.info(f"Best parameters: {grid_search.best_params_}")
        logger.info(f"Best CV ROC-AUC: {grid_search.best_score_:.4f}")
        
        return grid_search.best_estimator_, grid_search.best_params_
    
    def log_to_mlflow(self, model, metrics: Dict, params: Dict, 
                     model_name: str, X_train, y_train):
        """Log model and metrics to MLflow."""
        # Clean model name for MLflow (remove slashes and special chars)
        clean_model_name = model_name.replace(" ", "_").replace("/", "_")
        
        with mlflow.start_run(run_name=clean_model_name):
            # Log parameters
            mlflow.log_params(params)
            
            # Log metrics
            for metric_name, value in metrics.items():
                if isinstance(value, (int, float)):
                    mlflow.log_metric(metric_name, value)
            
            # Log model - use simple artifact path without slashes
            mlflow.sklearn.log_model(
                sk_model=model,
                artifact_path=f"model_{clean_model_name}",
                registered_model_name=clean_model_name
            )
            
            # Log additional info
            mlflow.log_param("training_samples", len(X_train))
            mlflow.log_param("feature_count", len(self.feature_names))
            
            logger.info(f"Logged {model_name} to MLflow")
    
    def train_all_models(self, X_train, y_train, X_test, y_test) -> Dict:
        """
        Train and evaluate all models.
        """
        models_config = {
            'Logistic Regression': {
                'train_fn': lambda: self.train_logistic_regression(X_train, y_train),
                'params': {'model': 'logistic_regression', 'C': 1.0, 'max_iter': 1000}
            },
            'Random Forest': {
                'train_fn': lambda: self.train_random_forest(X_train, y_train),
                'params': {'model': 'random_forest', 'n_estimators': 100, 'max_depth': 10}
            },
            'Gradient Boosting': {
                'train_fn': lambda: self.train_gradient_boosting(X_train, y_train),
                'params': {'model': 'gradient_boosting', 'n_estimators': 100, 'learning_rate': 0.1}
            }
        }
        
        for model_name, config in models_config.items():
            logger.info(f"\n{'='*60}")
            logger.info(f"Training {model_name}")
            logger.info(f"{'='*60}")
            
            # Train
            model = config['train_fn']()
            self.models[model_name] = model
            
            # Evaluate
            metrics = self.evaluate_model(model, X_test, y_test, model_name)
            self.results[model_name] = metrics
            
            # Log to MLflow
            self.log_to_mlflow(model, metrics, config['params'], model_name.replace(" ", "_"), X_train, y_train)
            
            # Get feature importance
            importance_df = self.get_feature_importance(model)
        
        # Select best model based on ROC-AUC
        best_model_name = max(self.results.keys(), key=lambda k: self.results[k]['roc_auc'])
        self.best_model = self.models[best_model_name]
        self.best_model_name = best_model_name
        
        logger.info(f"\n{'='*60}")
        logger.info(f"BEST MODEL: {best_model_name}")
        logger.info(f"ROC-AUC: {self.results[best_model_name]['roc_auc']:.4f}")
        logger.info(f"F1 Score: {self.results[best_model_name]['f1_score']:.4f}")
        logger.info(f"Business Cost: ${self.results[best_model_name]['estimated_business_cost']:,.0f}")
        logger.info(f"{'='*60}")
        
        return self.results
    
    def save_model(self, model_path: str = "models/best_model.pkl"):
        """Save the best model to disk."""
        if self.best_model is None:
            logger.error("No model trained yet!")
            return
        
        Path(model_path).parent.mkdir(parents=True, exist_ok=True)
        
        model_data = {
            'model': self.best_model,
            'model_name': self.best_model_name,
            'scaler': self.scaler,
            'feature_names': self.feature_names,
            'results': self.results
        }
        
        joblib.dump(model_data, model_path)
        logger.info(f"Model saved to {model_path}")
    
    def load_model(self, model_path: str = "models/best_model.pkl"):
        """Load a trained model from disk."""
        model_data = joblib.load(model_path)
        
        self.best_model = model_data['model']
        self.best_model_name = model_data['model_name']
        self.scaler = model_data['scaler']
        self.feature_names = model_data['feature_names']
        self.results = model_data['results']
        
        logger.info(f"Model loaded from {model_path}")
        logger.info(f"Model type: {self.best_model_name}")
        
        return self.best_model


def train_churn_model(features_path: str, target_column: str = 'churn',
                     save_path: str = "models/best_model.pkl") -> ChurnModelTrainer:
    """
    Main function to train churn prediction model.
    
    Args:
        features_path: Path to features CSV file
        target_column: Name of target column
        save_path: Path to save the trained model
    
    Returns:
        ChurnModelTrainer instance with trained models
    """
    # Load data
    logger.info(f"Loading features from {features_path}")
    df = pd.read_csv(features_path)
    
    # Separate features and target
    if target_column not in df.columns:
        raise ValueError(f"Target column '{target_column}' not found in data")
    
    X = df.drop(columns=[target_column])
    y = df[target_column]
    
    logger.info(f"Features shape: {X.shape}")
    logger.info(f"Target distribution:\n{y.value_counts(normalize=True)}")
    
    # Initialize trainer
    trainer = ChurnModelTrainer()
    
    # Prepare data
    X_train, X_test, y_train, y_test = trainer.prepare_data(X, y)
    
    # Train all models
    results = trainer.train_all_models(X_train, y_train, X_test, y_test)
    
    # Save best model
    trainer.save_model(save_path)
    
    return trainer


if __name__ == "__main__":
    print("="*60)
    print("CHURN MODEL TRAINING PIPELINE")
    print("="*60)
    
    # Load features
    print("\nLoading features...")
    df = pd.read_csv('data/processed/features.csv')
    
    # Check if target exists, if not we need to merge with original data
    if 'churn' not in df.columns:
        print("Target variable 'churn' not in features. Loading original data...")
        original_df = pd.read_csv('data/processed/customers_cleaned.csv')
        df = df.merge(original_df[['customer_id', 'churn']], on='customer_id', how='left')
    
    print(f"Dataset shape: {df.shape}")
    print(f"Churn rate: {df['churn'].mean():.2%}")
    
    # Train models
    print("\nStarting model training...")
    trainer = train_churn_model(
        features_path='data/processed/features.csv',
        target_column='churn',
        save_path='models/best_model.pkl'
    )
    
    print("\n" + "="*60)
    print("TRAINING COMPLETE")
    print("="*60)
    
    # Print summary
    print("\nModel Comparison:")
    for model_name, metrics in trainer.results.items():
        print(f"\n{model_name}:")
        print(f"  ROC-AUC:  {metrics['roc_auc']:.4f}")
        print(f"  F1 Score: {metrics['f1_score']:.4f}")
        print(f"  Cost:     ${metrics['estimated_business_cost']:,.0f}")
