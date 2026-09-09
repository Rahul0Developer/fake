"""
Data Cleaning and Validation Module

Handles:
- Missing value detection and imputation
- Outlier detection
- Data type validation
- Duplicate removal
- Data quality checks
"""

import pandas as pd
import numpy as np
from typing import Tuple, Dict, Optional
import logging

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)


class DataCleaner:
    """Comprehensive data cleaning pipeline for customer churn data."""
    
    def __init__(self):
        self.missing_stats = {}
        self.outlier_info = {}
        self.duplicate_count = 0
        self.quality_report = {}
    
    def load_data(self, filepath: str) -> pd.DataFrame:
        """Load data from CSV file."""
        logger.info(f"Loading data from {filepath}")
        df = pd.read_csv(filepath)
        logger.info(f"Loaded {len(df)} records with {len(df.columns)} columns")
        return df
    
    def detect_missing_values(self, df: pd.DataFrame) -> Dict:
        """Detect and report missing values."""
        missing = df.isnull().sum()
        missing_pct = (df.isnull().sum() / len(df)) * 100
        
        self.missing_stats = {
            'count': missing.to_dict(),
            'percentage': missing_pct.to_dict(),
            'total_missing': missing.sum(),
            'columns_with_missing': missing[missing > 0].index.tolist()
        }
        
        logger.info(f"Total missing values: {self.missing_stats['total_missing']}")
        logger.info(f"Columns with missing values: {self.missing_stats['columns_with_missing']}")
        
        return self.missing_stats
    
    def handle_missing_values(self, df: pd.DataFrame, strategy: str = 'auto') -> pd.DataFrame:
        """
        Handle missing values with appropriate strategies.
        
        Strategies:
        - 'auto': Automatically choose based on column type
        - 'drop': Drop rows with missing values
        - 'mean': Fill numerical with mean
        - 'median': Fill numerical with median
        - 'mode': Fill categorical with mode
        """
        df_clean = df.copy()
        
        if strategy == 'drop':
            initial_len = len(df_clean)
            df_clean = df_clean.dropna()
            logger.info(f"Dropped {initial_len - len(df_clean)} rows with missing values")
            return df_clean
        
        for col in df_clean.columns:
            if df_clean[col].isnull().any():
                if strategy == 'auto' or df_clean[col].dtype in ['float64', 'int64']:
                    # Numerical columns: use median (robust to outliers)
                    fill_value = df_clean[col].median()
                    df_clean[col] = df_clean[col].fillna(fill_value)
                    logger.info(f"Filled missing values in '{col}' with median: {fill_value}")
                else:
                    # Categorical columns: use mode
                    fill_value = df_clean[col].mode()[0] if not df_clean[col].mode().empty else 'Unknown'
                    df_clean[col] = df_clean[col].fillna(fill_value)
                    logger.info(f"Filled missing values in '{col}' with mode: {fill_value}")
        
        return df_clean
    
    def detect_outliers(self, df: pd.DataFrame, method: str = 'iqr') -> Dict:
        """
        Detect outliers using IQR or Z-score method.
        """
        outlier_info = {}
        
        numeric_cols = df.select_dtypes(include=[np.number]).columns
        
        for col in numeric_cols:
            if method == 'iqr':
                Q1 = df[col].quantile(0.25)
                Q3 = df[col].quantile(0.75)
                IQR = Q3 - Q1
                lower_bound = Q1 - 1.5 * IQR
                upper_bound = Q3 + 1.5 * IQR
                
                outliers = ((df[col] < lower_bound) | (df[col] > upper_bound)).sum()
                
                outlier_info[col] = {
                    'method': 'IQR',
                    'lower_bound': lower_bound,
                    'upper_bound': upper_bound,
                    'outlier_count': int(outliers),
                    'outlier_percentage': round((outliers / len(df)) * 100, 2)
                }
            
            elif method == 'zscore':
                from scipy import stats
                z_scores = np.abs(stats.zscore(df[col].dropna()))
                outliers = (z_scores > 3).sum()
                
                outlier_info[col] = {
                    'method': 'Z-Score',
                    'threshold': 3,
                    'outlier_count': int(outliers),
                    'outlier_percentage': round((outliers / len(df)) * 100, 2)
                }
        
        self.outlier_info = outlier_info
        logger.info(f"Outlier detection completed for {len(outlier_info)} columns")
        
        return outlier_info
    
    def remove_outliers(self, df: pd.DataFrame, columns: Optional[list] = None, 
                       method: str = 'iqr') -> pd.DataFrame:
        """Remove outliers from specified columns."""
        df_clean = df.copy()
        
        if columns is None:
            columns = df_clean.select_dtypes(include=[np.number]).columns.tolist()
        
        initial_len = len(df_clean)
        
        for col in columns:
            if col not in df_clean.columns:
                continue
                
            Q1 = df_clean[col].quantile(0.25)
            Q3 = df_clean[col].quantile(0.75)
            IQR = Q3 - Q1
            
            lower_bound = Q1 - 1.5 * IQR
            upper_bound = Q3 + 1.5 * IQR
            
            df_clean = df_clean[(df_clean[col] >= lower_bound) & (df_clean[col] <= upper_bound)]
        
        removed = initial_len - len(df_clean)
        logger.info(f"Removed {removed} rows containing outliers ({removed/initial_len*100:.2f}%)")
        
        return df_clean
    
    def remove_duplicates(self, df: pd.DataFrame, subset: Optional[list] = None) -> pd.DataFrame:
        """Remove duplicate rows."""
        initial_len = len(df)
        
        if subset:
            df_clean = df.drop_duplicates(subset=subset)
        else:
            df_clean = df.drop_duplicates()
        
        self.duplicate_count = initial_len - len(df_clean)
        logger.info(f"Removed {self.duplicate_count} duplicate rows")
        
        return df_clean
    
    def validate_data_types(self, df: pd.DataFrame, expected_types: Dict[str, str]) -> Tuple[bool, Dict]:
        """
        Validate that columns have expected data types.
        
        Returns:
            Tuple of (is_valid, mismatch_report)
        """
        mismatches = {}
        
        for col, expected_type in expected_types.items():
            if col not in df.columns:
                mismatches[col] = f"Column '{col}' not found in dataframe"
                continue
            
            actual_type = str(df[col].dtype)
            
            if expected_type not in actual_type:
                mismatches[col] = {
                    'expected': expected_type,
                    'actual': actual_type
                }
        
        is_valid = len(mismatches) == 0
        
        if not is_valid:
            logger.warning(f"Data type validation failed for {len(mismatches)} columns")
            for col, info in mismatches.items():
                logger.warning(f"  {col}: {info}")
        else:
            logger.info("Data type validation passed")
        
        return is_valid, mismatches
    
    def run_quality_checks(self, df: pd.DataFrame) -> Dict:
        """Run comprehensive data quality checks."""
        checks = {
            'total_records': len(df),
            'total_columns': len(df.columns),
            'missing_values': df.isnull().sum().to_dict(),
            'duplicate_rows': df.duplicated().sum(),
            'unique_values_per_column': {col: df[col].nunique() for col in df.columns},
            'data_types': {col: str(df[col].dtype) for col in df.columns},
            'numeric_stats': df.describe().to_dict(),
            'categorical_stats': {}
        }
        
        # Add categorical statistics
        for col in df.select_dtypes(include=['object', 'category']).columns:
            checks['categorical_stats'][col] = df[col].value_counts().to_dict()
        
        self.quality_report = checks
        logger.info("Data quality checks completed")
        
        return checks
    
    def clean_pipeline(self, df: pd.DataFrame, remove_outliers_flag: bool = True) -> pd.DataFrame:
        """
        Run complete cleaning pipeline.
        
        Steps:
        1. Detect missing values
        2. Handle missing values
        3. Remove duplicates
        4. Detect and optionally remove outliers
        5. Run quality checks
        """
        logger.info("Starting data cleaning pipeline...")
        
        # Step 1: Detect missing values
        self.detect_missing_values(df)
        
        # Step 2: Handle missing values
        df_clean = self.handle_missing_values(df, strategy='auto')
        
        # Step 3: Remove duplicates
        df_clean = self.remove_duplicates(df_clean)
        
        # Step 4: Handle outliers
        if remove_outliers_flag:
            self.detect_outliers(df_clean)
            df_clean = self.remove_outliers(df_clean)
        
        # Step 5: Quality checks
        self.run_quality_checks(df_clean)
        
        logger.info(f"Cleaning pipeline completed. Final dataset: {len(df_clean)} records")
        
        return df_clean


def load_and_clean_data(customer_path: str, transaction_path: Optional[str] = None) -> pd.DataFrame:
    """
    Main function to load and clean customer data.
    
    Args:
        customer_path: Path to customer CSV file
        transaction_path: Optional path to transaction CSV file
    
    Returns:
        Cleaned DataFrame ready for feature engineering
    """
    cleaner = DataCleaner()
    
    # Load customer data
    customers = cleaner.load_data(customer_path)
    
    # Clean customer data
    customers_clean = cleaner.clean_pipeline(customers)
    
    # Load and aggregate transaction data if provided
    if transaction_path:
        transactions = cleaner.load_data(transaction_path)
        transactions = cleaner.handle_missing_values(transactions)
        
        # Aggregate transactions per customer
        txn_agg = transactions.groupby('customer_id').agg({
            'amount': ['sum', 'mean', 'std', 'count'],
            'transaction_type': lambda x: x.value_counts().idxmax()
        }).reset_index()
        
        # Flatten column names
        txn_agg.columns = ['customer_id', 'total_amount', 'avg_amount', 'std_amount', 'txn_count', 'most_common_txn_type']
        
        # Merge with customer data
        customers_clean = customers_clean.merge(txn_agg, on='customer_id', how='left')
        
        # Fill NaN in aggregated columns
        agg_cols = ['total_amount', 'avg_amount', 'std_amount', 'txn_count']
        for col in agg_cols:
            customers_clean[col] = customers_clean[col].fillna(0)
        
        if 'most_common_txn_type' in customers_clean.columns:
            customers_clean['most_common_txn_type'] = customers_clean['most_common_txn_type'].fillna('None')
    
    return customers_clean


if __name__ == "__main__":
    # Example usage
    cleaner = DataCleaner()
    
    # Load sample data
    df = pd.read_csv('data/raw/customers.csv')
    
    print("Initial data shape:", df.shape)
    print("\nMissing values:")
    print(cleaner.detect_missing_values(df))
    
    print("\nRunning cleaning pipeline...")
    df_clean = cleaner.clean_pipeline(df)
    
    print("\nCleaned data shape:", df_clean.shape)
    print("\nQuality Report Summary:")
    print(f"  Total records: {cleaner.quality_report['total_records']}")
    print(f"  Duplicates removed: {cleaner.duplicate_count}")
    print(f"  Columns: {list(df_clean.columns)}")
    
    # Save cleaned data
    df_clean.to_csv('data/processed/customers_cleaned.csv', index=False)
    print("\nCleaned data saved to data/processed/customers_cleaned.csv")
