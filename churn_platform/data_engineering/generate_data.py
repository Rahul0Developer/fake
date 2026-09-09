"""
Predictive Business Intelligence & ML Operations Platform
Customer Churn Prediction System

This module generates synthetic customer data for demonstration purposes.
In production, this would be replaced by actual database connections.
"""

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import random


def generate_customer_data(n_customers: int = 10000) -> pd.DataFrame:
    """
    Generate synthetic customer data with realistic churn patterns.
    
    Features engineered to create meaningful churn signals:
    - Tenure: Longer tenure reduces churn probability
    - Monthly charges: Higher charges increase churn
    - Total charges: Derived from tenure * monthly charges
    - Contract type: Month-to-month has highest churn
    - Payment method: Electronic check has higher churn
    - Services: Multiple services reduce churn
    - Support tickets: More tickets increase churn
    - Usage patterns: Declining usage indicates churn risk
    """
    
    np.random.seed(42)
    random.seed(42)
    
    # Basic customer info
    customer_ids = [f"CUST_{str(i).zfill(6)}" for i in range(1, n_customers + 1)]
    
    # Tenure (months) - skewed towards newer customers
    tenure = np.random.exponential(scale=24, size=n_customers).clip(1, 72).astype(int)
    
    # Contract types with different churn probabilities
    contract_types = np.random.choice(
        ['Month-to-month', 'One year', 'Two year'], 
        size=n_customers, 
        p=[0.55, 0.25, 0.20]
    )
    
    # Monthly charges ($20-$120)
    monthly_charges = np.random.uniform(20, 120, size=n_customers).round(2)
    
    # Total charges (tenure * monthly_charges with some variation)
    total_charges = (tenure * monthly_charges * np.random.uniform(0.95, 1.05, size=n_customers)).round(2)
    
    # Payment methods
    payment_methods = np.random.choice(
        ['Electronic check', 'Mailed check', 'Bank transfer', 'Credit card'],
        size=n_customers,
        p=[0.35, 0.20, 0.25, 0.20]
    )
    
    # Internet service
    internet_service = np.random.choice(
        ['DSL', 'Fiber optic', 'No'],
        size=n_customers,
        p=[0.35, 0.45, 0.20]
    )
    
    # Phone service
    phone_service = np.random.choice(['Yes', 'No'], size=n_customers, p=[0.70, 0.30])
    
    # Additional services (more services = lower churn)
    num_services = np.random.poisson(lam=3, size=n_customers).clip(0, 7)
    
    # Support tickets in last 3 months (more tickets = higher churn)
    support_tickets = np.random.poisson(lam=2, size=n_customers).clip(0, 15)
    
    # Average monthly usage (GB) - declining usage indicates churn
    avg_monthly_usage = np.random.gamma(shape=5, scale=10, size=n_customers).round(2)
    usage_trend = np.random.choice(['Increasing', 'Stable', 'Declining'], 
                                    size=n_customers, 
                                    p=[0.30, 0.45, 0.25])
    
    # Customer segment
    segments = np.random.choice(
        ['Enterprise', 'SMB', 'Consumer'],
        size=n_customers,
        p=[0.15, 0.35, 0.50]
    )
    
    # Senior citizen
    senior_citizen = np.random.choice([1, 0], size=n_customers, p=[0.16, 0.84])
    
    # Dependents
    dependents = np.random.choice(['Yes', 'No'], size=n_customers, p=[0.30, 0.70])
    
    # Partner
    partner = np.random.choice(['Yes', 'No'], size=n_customers, p=[0.48, 0.52])
    
    # Calculate churn probability based on features
    churn_prob = np.zeros(n_customers)
    
    # Base churn rate
    churn_prob += 0.15
    
    # Contract type impact
    churn_prob += np.where(contract_types == 'Month-to-month', 0.25, 0)
    churn_prob += np.where(contract_types == 'One year', 0.05, 0)
    churn_prob += np.where(contract_types == 'Two year', -0.10, 0)
    
    # Tenure impact (newer customers churn more)
    churn_prob += np.where(tenure < 6, 0.15, 0)
    churn_prob += np.where(tenure > 24, -0.10, 0)
    
    # Payment method impact
    churn_prob += np.where(payment_methods == 'Electronic check', 0.12, 0)
    churn_prob += np.where(payment_methods == 'Credit card', -0.08, 0)
    
    # Support tickets impact
    churn_prob += np.clip(support_tickets * 0.03, 0, 0.25)
    
    # Usage trend impact
    churn_prob += np.where(usage_trend == 'Declining', 0.15, 0)
    churn_prob += np.where(usage_trend == 'Increasing', -0.05, 0)
    
    # Number of services impact (more services = stickier)
    churn_prob -= np.clip(num_services * 0.02, 0, 0.10)
    
    # Monthly charges impact (very high charges increase churn)
    churn_prob += np.where(monthly_charges > 90, 0.08, 0)
    
    # Add some randomness
    churn_prob += np.random.normal(0, 0.05, size=n_customers)
    
    # Clip to valid probability range
    churn_prob = np.clip(churn_prob, 0.02, 0.95)
    
    # Generate actual churn based on probability
    churn = (np.random.random(n_customers) < churn_prob).astype(int)
    
    # Create DataFrame
    df = pd.DataFrame({
        'customer_id': customer_ids,
        'tenure': tenure,
        'monthly_charges': monthly_charges,
        'total_charges': total_charges,
        'contract_type': contract_types,
        'payment_method': payment_methods,
        'internet_service': internet_service,
        'phone_service': phone_service,
        'num_services': num_services,
        'support_tickets': support_tickets,
        'avg_monthly_usage': avg_monthly_usage,
        'usage_trend': usage_trend,
        'segment': segments,
        'senior_citizen': senior_citizen,
        'dependents': dependents,
        'partner': partner,
        'churn': churn,
        'signup_date': [datetime.now() - timedelta(days=int(t * 30)) for t in tenure],
        'last_interaction': [datetime.now() - timedelta(days=np.random.randint(1, 60)) for _ in range(n_customers)]
    })
    
    return df


def generate_transaction_data(customers_df: pd.DataFrame, n_transactions: int = 50000) -> pd.DataFrame:
    """
    Generate transaction history for customers.
    """
    
    np.random.seed(43)
    
    customer_ids = customers_df['customer_id'].tolist()
    churn_map = dict(zip(customers_df['customer_id'], customers_df['churn']))
    
    transactions = []
    
    for i in range(n_transactions):
        cust_id = random.choice(customer_ids)
        is_churned = churn_map[cust_id]
        
        # Transaction date within last 2 years
        days_ago = np.random.randint(1, 730)
        trans_date = datetime.now() - timedelta(days=days_ago)
        
        # Transaction amount
        amount = np.random.exponential(scale=50) + 10
        amount = round(amount, 2)
        
        # Transaction type
        trans_type = np.random.choice(
            ['Purchase', 'Refund', 'Subscription', 'Upgrade'],
            p=[0.60, 0.05, 0.30, 0.05]
        )
        
        # Churned customers have fewer recent transactions
        if is_churned and days_ago < 90:
            if np.random.random() > 0.3:
                continue
        
        transactions.append({
            'transaction_id': f"TXN_{str(i).zfill(8)}",
            'customer_id': cust_id,
            'transaction_date': trans_date,
            'amount': amount,
            'transaction_type': trans_type,
            'payment_status': np.random.choice(['Completed', 'Failed', 'Pending'], p=[0.90, 0.07, 0.03])
        })
    
    return pd.DataFrame(transactions)


if __name__ == "__main__":
    print("Generating synthetic customer data...")
    customers = generate_customer_data(10000)
    print(f"Generated {len(customers)} customer records")
    
    print("\nGenerating transaction data...")
    transactions = generate_transaction_data(customers, 50000)
    print(f"Generated {len(transactions)} transaction records")
    
    # Save to CSV
    customers.to_csv('data/raw/customers.csv', index=False)
    transactions.to_csv('data/raw/transactions.csv', index=False)
    
    print("\nData saved to data/raw/")
    print(f"\nChurn rate: {customers['churn'].mean():.2%}")
    print(f"\nSample customer data:\n{customers.head()}")
