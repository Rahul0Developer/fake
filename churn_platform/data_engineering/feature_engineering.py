"""
Feature Engineering Module

Creates predictive features for churn modeling:
- Behavioral features from transaction history
- Engagement metrics
- Risk indicators
- Temporal features
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
from typing import Dict, List, Tuple
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class FeatureEngineer:
    """Create and transform features for churn prediction."""
    
    def __init__(self):
        self.feature_metadata = {}
        self.categorical_encodings = {}
        self.numeric_scalers = {}
    
    def create_tenure_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create tenure-based features.
        
        Tenure is often one of the strongest predictors of churn.
        """
        df = df.copy()
        
        # Tenure groups
        df['tenure_group'] = pd.cut(
            df['tenure'],
            bins=[0, 6, 12, 24, 48, 72],
            labels=['0-6mo', '6-12mo', '1-2yr', '2-4yr', '4yr+']
        )
        
        # Is new customer (first 6 months are critical)
        df['is_new_customer'] = (df['tenure'] < 6).astype(int)
        
        # Tenure squared (to capture non-linear relationship)
        df['tenure_squared'] = df['tenure'] ** 2
        
        # Log tenure (handle zero by adding 1)
        df['log_tenure'] = np.log1p(df['tenure'])
        
        logger.info("Created tenure features: tenure_group, is_new_customer, tenure_squared, log_tenure")
        
        return df
    
    def create_financial_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create financial/charging features.
        """
        df = df.copy()
        
        # Average monthly charge per tenure month
        df['avg_charge_per_month'] = df['total_charges'] / (df['tenure'] + 1)
        
        # Charge ratio (monthly vs average)
        overall_avg = df['monthly_charges'].mean()
        df['charge_ratio'] = df['monthly_charges'] / overall_avg
        
        # High value customer flag
        df['is_high_value'] = (df['monthly_charges'] > df['monthly_charges'].quantile(0.75)).astype(int)
        
        # Total charges normalized by tenure
        df['charges_per_tenure'] = df['total_charges'] / np.maximum(df['tenure'], 1)
        
        logger.info("Created financial features: avg_charge_per_month, charge_ratio, is_high_value, charges_per_tenure")
        
        return df
    
    def create_engagement_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create customer engagement features.
        """
        df = df.copy()
        
        # Service count (already exists but ensure it's numeric)
        if 'num_services' in df.columns:
            df['service_density'] = df['num_services'] / np.maximum(df['tenure'], 1)
        
        # Support ticket intensity
        if 'support_tickets' in df.columns:
            df['tickets_per_month'] = df['support_tickets'] / np.maximum(df['tenure'], 1)
            df['high_support_need'] = (df['support_tickets'] > 3).astype(int)
        
        # Usage features
        if 'avg_monthly_usage' in df.columns:
            df['usage_per_service'] = df['avg_monthly_usage'] / np.maximum(df['num_services'], 1)
            df['low_usage'] = (df['avg_monthly_usage'] < df['avg_monthly_usage'].quantile(0.25)).astype(int)
        
        # Usage trend encoding
        if 'usage_trend' in df.columns:
            trend_map = {'Declining': -1, 'Stable': 0, 'Increasing': 1}
            df['usage_trend_encoded'] = df['usage_trend'].map(trend_map)
        
        logger.info("Created engagement features")
        
        return df
    
    def create_contract_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create contract-related features.
        """
        df = df.copy()
        
        # Contract risk score
        contract_risk = {
            'Month-to-month': 3,
            'One year': 2,
            'Two year': 1
        }
        df['contract_risk_score'] = df['contract_type'].map(contract_risk)
        
        # Is flexible contract (month-to-month)
        df['is_flexible_contract'] = (df['contract_type'] == 'Month-to-month').astype(int)
        
        # Payment method risk
        payment_risk = {
            'Electronic check': 3,
            'Mailed check': 2,
            'Bank transfer': 1,
            'Credit card': 1
        }
        df['payment_risk_score'] = df['payment_method'].map(payment_risk)
        
        # Auto-pay indicator (bank transfer or credit card usually means auto-pay)
        df['has_auto_pay'] = df['payment_method'].isin(['Bank transfer', 'Credit card']).astype(int)
        
        logger.info("Created contract features: contract_risk_score, is_flexible_contract, payment_risk_score, has_auto_pay")
        
        return df
    
    def create_demographic_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create demographic/household features.
        """
        df = df.copy()
        
        # Household size proxy
        if 'dependents' in df.columns and 'partner' in df.columns:
            df['household_size'] = (
                1 +  # Customer themselves
                (df['partner'] == 'Yes').astype(int) +
                (df['dependents'] == 'Yes').astype(int) * 2  # Assume 2 dependents on average
            )
            df['is_single_household'] = ((df['partner'] == 'No') & (df['dependents'] == 'No')).astype(int)
        
        # Senior citizen flag (already binary)
        if 'senior_citizen' in df.columns:
            df['is_senior'] = df['senior_citizen']
        
        # Segment encoding
        if 'segment' in df.columns:
            segment_value = {
                'Enterprise': 3,
                'SMB': 2,
                'Consumer': 1
            }
            df['segment_value_score'] = df['segment'].map(segment_value)
        
        logger.info("Created demographic features")
        
        return df
    
    def create_transaction_features(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create features from transaction data if available.
        """
        df = df.copy()
        
        if 'txn_count' in df.columns:
            # Transaction frequency
            df['txn_frequency'] = df['txn_count'] / np.maximum(df['tenure'], 1)
            
            # Is active customer (recent transactions)
            df['is_active'] = (df['txn_count'] > 0).astype(int)
        
        if 'total_amount' in df.columns:
            # Average transaction value
            df['avg_txn_value'] = df['total_amount'] / np.maximum(df['txn_count'], 1)
            
            # Revenue contribution
            total_revenue = df['total_amount'].sum()
            df['revenue_share'] = df['total_amount'] / total_revenue if total_revenue > 0 else 0
        
        if 'std_amount' in df.columns:
            # Spending consistency
            df['spending_consistency'] = 1 / (1 + df['std_amount'])
        
        logger.info("Created transaction features")
        
        return df
    
    def create_risk_indicators(self, df: pd.DataFrame) -> pd.DataFrame:
        """
        Create composite risk indicators.
        """
        df = df.copy()
        
        # Overall risk score (weighted combination)
        risk_factors = []
        
        if 'is_new_customer' in df.columns:
            risk_factors.append(df['is_new_customer'] * 2)
        
        if 'is_flexible_contract' in df.columns:
            risk_factors.append(df['is_flexible_contract'] * 3)
        
        if 'high_support_need' in df.columns:
            risk_factors.append(df['high_support_need'] * 2)
        
        if 'usage_trend_encoded' in df.columns:
            risk_factors.append((df['usage_trend_encoded'] == -1).astype(int) * 2)
        
        if 'payment_risk_score' in df.columns:
            risk_factors.append((df['payment_risk_score'] >= 3).astype(int))
        
        if risk_factors:
            df['churn_risk_score'] = sum(risk_factors)
            df['high_risk_flag'] = (df['churn_risk_score'] >= 5).astype(int)
        
        logger.info("Created risk indicators")
        
        return df
    
    def encode_categorical_features(self, df: pd.DataFrame, 
                                   categorical_cols: List[str]) -> pd.DataFrame:
        """
        Encode categorical features using one-hot encoding.
        """
        df = df.copy()
        
        for col in categorical_cols:
            if col not in df.columns:
                continue
            
            # One-hot encoding
            dummies = pd.get_dummies(df[col], prefix=col, drop_first=True)
            df = pd.concat([df, dummies], axis=1)
            
            # Store encoding info
            self.categorical_encodings[col] = list(dummies.columns)
            
            # Drop original column
            df = df.drop(col, axis=1)
        
        logger.info(f"Encoded {len(categorical_cols)} categorical columns")
        
        return df
    
    def select_features_for_model(self, df: pd.DataFrame) -> Tuple[pd.DataFrame, List[str]]:
        """
        Select final feature set for modeling.
        
        Returns:
            Tuple of (feature_dataframe, feature_names)
        """
        # Columns to exclude
        exclude_cols = [
            'customer_id',
            'signup_date',
            'last_interaction',
            'churn'  # Target variable
        ]
        
        # Select all numeric columns except excluded ones
        feature_cols = [
            col for col in df.columns 
            if col not in exclude_cols and df[col].dtype in ['int64', 'float64', 'uint8', 'int32']
        ]
        
        logger.info(f"Selected {len(feature_cols)} features for modeling")
        
        return df[feature_cols], feature_cols
    
    def create_all_features(self, df: pd.DataFrame, 
                           include_transactions: bool = True) -> Tuple[pd.DataFrame, List[str]]:
        """
        Run complete feature engineering pipeline.
        
        Args:
            df: Input DataFrame with cleaned customer data
            include_transactions: Whether to include transaction-based features
        
        Returns:
            Tuple of (feature_dataframe, feature_names)
        """
        logger.info("Starting feature engineering pipeline...")
        initial_cols = len(df.columns)
        
        # Apply all feature creation methods
        df = self.create_tenure_features(df)
        df = self.create_financial_features(df)
        df = self.create_engagement_features(df)
        df = self.create_contract_features(df)
        df = self.create_demographic_features(df)
        
        if include_transactions:
            df = self.create_transaction_features(df)
        
        df = self.create_risk_indicators(df)
        
        # Identify categorical columns for encoding
        categorical_cols = df.select_dtypes(include=['object', 'category']).columns.tolist()
        categorical_cols = [col for col in categorical_cols if col not in ['customer_id']]
        
        # Encode categorical features
        if categorical_cols:
            df = self.encode_categorical_features(df, categorical_cols)
        
        # Select final features
        X, feature_names = self.select_features_for_model(df)
        
        logger.info(f"Feature engineering completed: {initial_cols} → {len(X.columns)} features")
        
        # Store metadata
        self.feature_metadata = {
            'original_columns': initial_cols,
            'final_features': len(X.columns),
            'feature_names': feature_names,
            'categorical_encoded': list(self.categorical_encodings.keys())
        }
        
        return X, feature_names


def prepare_features(customer_path: str, transaction_path: str = None) -> Tuple[pd.DataFrame, List[str]]:
    """
    Main function to prepare features from raw data.
    
    Args:
        customer_path: Path to cleaned customer CSV
        transaction_path: Optional path to transaction CSV
    
    Returns:
        Tuple of (feature_dataframe, feature_names)
    """
    # Load cleaned data
    df = pd.read_csv(customer_path)
    
    # If transaction path provided and transactions exist in data, use them
    engineer = FeatureEngineer()
    X, feature_names = engineer.create_all_features(df, include_transactions=('txn_count' in df.columns))
    
    return X, feature_names


if __name__ == "__main__":
    print("Loading cleaned customer data...")
    df = pd.read_csv('data/processed/customers_cleaned.csv')
    
    print(f"\nInitial data shape: {df.shape}")
    print(f"Columns: {list(df.columns)}")
    
    print("\nRunning feature engineering pipeline...")
    engineer = FeatureEngineer()
    X, feature_names = engineer.create_all_features(df)
    
    print(f"\nFinal feature matrix shape: {X.shape}")
    print(f"\nFeature names ({len(feature_names)}):")
    for i, name in enumerate(feature_names, 1):
        print(f"  {i}. {name}")
    
    # Save features
    X.to_csv('data/processed/features.csv', index=False)
    print(f"\nFeatures saved to data/processed/features.csv")
    
    # Print feature metadata
    print("\nFeature Engineering Summary:")
    for key, value in engineer.feature_metadata.items():
        print(f"  {key}: {value}")
