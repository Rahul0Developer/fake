# Predictive Business Intelligence Platform - Customer Churn Prediction
FROM python:3.10-slim

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    gcc \
    g++ \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements first for better caching
COPY requirements.txt .

# Install Python dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY . .

# Create necessary directories
RUN mkdir -p data/raw data/processed models mlruns logs

# Expose API port
EXPOSE 8000

# Expose Streamlit port
EXPOSE 8501

# Set environment variables
ENV PYTHONUNBUFFERED=1
ENV MLFLOW_TRACKING_URI=./mlruns

# Default command - run API (override for dashboard)
CMD ["uvicorn", "api.predict:app", "--host", "0.0.0.0", "--port", "8000"]
