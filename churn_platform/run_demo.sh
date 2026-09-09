#!/bin/bash

echo "========================================"
echo "Customer Churn Prediction Platform Demo"
echo "========================================"
echo ""

# Step 1: Generate data (if not exists)
if [ ! -f "data/raw/customers.csv" ]; then
    echo "📊 Generating synthetic customer data..."
    python data_engineering/generate_data.py
else
    echo "✓ Customer data already exists"
fi

# Step 2: Clean data (if not exists)
if [ ! -f "data/processed/customers_cleaned.csv" ]; then
    echo "🧹 Cleaning and validating data..."
    python data_engineering/clean_data.py
else
    echo "✓ Cleaned data already exists"
fi

# Step 3: Feature engineering (if not exists)
if [ ! -f "data/processed/features_final.csv" ]; then
    echo "⚙️ Engineering features..."
    python data_engineering/feature_engineering.py
    python -c "
import pandas as pd
df = pd.read_csv('data/processed/features.csv')
customers = pd.read_csv('data/processed/customers_cleaned.csv')
df.insert(0, 'customer_id', customers['customer_id'].values[:len(df)])
df.to_csv('data/processed/features_final.csv', index=False)
"
else
    echo "✓ Features already engineered"
fi

# Step 4: Train models (if not exists)
if [ ! -f "models/best_model.pkl" ]; then
    echo "🤖 Training ML models..."
    python ml_training/train_models.py
else
    echo "✓ Models already trained"
fi

echo ""
echo "========================================"
echo "✅ Setup Complete!"
echo "========================================"
echo ""
echo "To start the API server:"
echo "  uvicorn api.predict:app --host 0.0.0.0 --port 8000"
echo ""
echo "To start the dashboard:"
echo "  streamlit run dashboard/app.py"
echo ""
echo "Or use Docker Compose:"
echo "  docker-compose up -d"
echo ""
