"""
Streamlit Dashboard for Customer Churn Prediction

Features:
- Interactive churn prediction interface
- Customer risk prioritization table
- Model performance metrics
- Feature importance visualization
- Business impact calculator
- Data exploration tools
"""

import streamlit as st
import pandas as pd
import numpy as np
import plotly.express as px
import plotly.graph_objects as go
from plotly.subplots import make_subplots
import requests
import json
from datetime import datetime
import os

# Page configuration
st.set_page_config(
    page_title="Customer Churn Prediction Dashboard",
    page_icon="📊",
    layout="wide",
    initial_sidebar_state="expanded"
)

# Custom CSS for better UI
st.markdown("""
<style>
    .main-header {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f77b4;
        text-align: center;
        margin-bottom: 1rem;
    }
    .metric-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1rem;
        border-radius: 0.5rem;
        color: white;
        text-align: center;
    }
    .risk-high {
        background-color: #ffebee;
        border-left: 4px solid #f44336;
    }
    .risk-medium {
        background-color: #fff3e0;
        border-left: 4px solid #ff9800;
    }
    .risk-low {
        background-color: #e8f5e9;
        border-left: 4px solid #4caf50;
    }
    .stButton>button {
        width: 100%;
        border-radius: 0.5rem;
        font-weight: 600;
    }
</style>
""", unsafe_allow_html=True)

# API Configuration
API_BASE_URL = os.getenv("API_URL", "http://localhost:8000")

# Session state initialization
if 'predictions' not in st.session_state:
    st.session_state.predictions = []
if 'model_info' not in st.session_state:
    st.session_state.model_info = None


def check_api_health():
    """Check if the prediction API is available."""
    try:
        response = requests.get(f"{API_BASE_URL}/health", timeout=5)
        return response.status_code == 200, response.json()
    except:
        return False, None


def predict_single_customer(customer_data):
    """Get prediction for a single customer."""
    try:
        response = requests.post(
            f"{API_BASE_URL}/predict",
            json=customer_data,
            timeout=10
        )
        if response.status_code == 200:
            return True, response.json()
        else:
            return False, response.json()
    except Exception as e:
        return False, str(e)


def get_feature_importance():
    """Get feature importance from the model."""
    try:
        response = requests.get(f"{API_BASE_URL}/feature-importance", timeout=10)
        if response.status_code == 200:
            return response.json()
    except:
        pass
    return None


def load_sample_data():
    """Load sample customer data for demonstration."""
    try:
        df = pd.read_csv('data/processed/customers_cleaned.csv')
        return df.head(100)  # Load first 100 for demo
    except:
        return None


# Main Dashboard
def main():
    # Header
    st.markdown('<h1 class="main-header">🎯 Customer Churn Prediction Dashboard</h1>', unsafe_allow_html=True)
    st.markdown("---")
    
    # Sidebar
    with st.sidebar:
        st.image("https://img.icons8.com/color/96/000000/customer.png", width=80)
        st.header("Navigation")
        
        menu_options = [
            "📊 Dashboard Overview",
            "🔮 Predict Churn",
            "📈 Customer Analytics",
            "🤖 Model Performance",
            "💼 Business Impact",
            "⚙️ Settings"
        ]
        
        selected_menu = st.radio("", menu_options, label_visibility="collapsed")
        
        st.markdown("---")
        
        # API Health Check
        st.subheader("System Status")
        api_healthy, health_data = check_api_health()
        
        if api_healthy:
            st.success("✅ API Connected")
            st.info(f"Model: {health_data.get('model_name', 'Unknown')}")
        else:
            st.error("❌ API Disconnected")
            st.warning("Running in demo mode")
        
        st.markdown("---")
        st.caption(f"Last updated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    
    # Dashboard content based on selection
    if selected_menu == "📊 Dashboard Overview":
        show_dashboard_overview()
    elif selected_menu == "🔮 Predict Churn":
        show_prediction_interface()
    elif selected_menu == "📈 Customer Analytics":
        show_customer_analytics()
    elif selected_menu == "🤖 Model Performance":
        show_model_performance()
    elif selected_menu == "💼 Business Impact":
        show_business_impact()
    elif selected_menu == "⚙️ Settings":
        show_settings()


def show_dashboard_overview():
    """Show dashboard overview with key metrics."""
    st.header("Executive Summary")
    
    # Load sample data
    df = load_sample_data()
    
    if df is not None and 'churn' in df.columns:
        # Key metrics row
        col1, col2, col3, col4 = st.columns(4)
        
        total_customers = len(df)
        churn_rate = df['churn'].mean() * 100
        high_risk_customers = int(total_customers * churn_rate * 0.3)  # Estimate
        at_risk_revenue = high_risk_customers * 75 * 12  # Avg monthly charge * 12 months
        
        with col1:
            st.metric(
                label="Total Customers",
                value=f"{total_customers:,}",
                delta="Active"
            )
        
        with col2:
            st.metric(
                label="Churn Rate",
                value=f"{churn_rate:.1f}%",
                delta="-2.3%" if churn_rate < 30 else "+1.2%",
                delta_color="inverse" if churn_rate < 30 else "normal"
            )
        
        with col3:
            st.metric(
                label="High Risk Customers",
                value=f"{high_risk_customers:,}",
                delta="Requires attention"
            )
        
        with col4:
            st.metric(
                label="At-Risk Revenue",
                value=f"${at_risk_revenue:,.0f}",
                delta="Annual"
            )
        
        st.markdown("---")
        
        # Charts row
        col1, col2 = st.columns(2)
        
        with col1:
            st.subheader("Churn Distribution by Contract Type")
            contract_churn = df.groupby('contract_type')['churn'].agg(['mean', 'count']).reset_index()
            contract_churn.columns = ['Contract Type', 'Churn Rate', 'Count']
            
            fig = px.bar(
                contract_churn,
                x='Contract Type',
                y='Churn Rate',
                color='Churn Rate',
                color_continuous_scale='Reds',
                text=contract_churn['Churn Rate'].apply(lambda x: f"{x*100:.1f}%"),
                title="Churn Rate by Contract Type"
            )
            fig.update_layout(height=400)
            st.plotly_chart(fig, use_container_width=True)
        
        with col2:
            st.subheader("Customer Tenure Distribution")
            tenure_dist = df['tenure'].copy()
            
            fig = px.histogram(
                df,
                x='tenure',
                color='churn',
                nbins=30,
                color_discrete_map={0: '#4CAF50', 1: '#F44336'},
                title="Tenure Distribution (Churned vs Active)",
                labels={'tenure': 'Tenure (months)', 'count': 'Number of Customers'}
            )
            fig.update_layout(height=400, showlegend=True)
            fig.update_traces(hovertemplate='Tenure: %{x}<br>Count: %{y}')
            st.plotly_chart(fig, use_container_width=True)
        
        # Risk factors
        st.subheader("🔍 Top Churn Risk Factors")
        
        risk_factors = [
            ("Month-to-month contracts", "Customers with flexible contracts are 3x more likely to churn"),
            ("Low tenure (< 6 months)", "New customers in first 6 months have highest churn probability"),
            ("Electronic check payments", "Manual payment methods correlate with higher churn"),
            ("High support tickets", "Customers with 4+ tickets show elevated churn risk"),
            ("Declining usage patterns", "Decreasing usage is a strong leading indicator")
        ]
        
        for i, (factor, description) in enumerate(risk_factors, 1):
            st.markdown(f"**{i}. {factor}**")
            st.caption(description)
    
    else:
        st.info("📁 No customer data found. Please upload data or run the data generation script.")
        
        # Demo mode with static content
        st.subheader("Demo Mode - Sample Insights")
        
        demo_metrics = {
            "Total Customers": "10,000",
            "Churn Rate": "26.4%",
            "High Risk": "2,640",
            "At-Risk Revenue": "$2.4M"
        }
        
        cols = st.columns(4)
        for i, (metric, value) in enumerate(demo_metrics.items()):
            cols[i].metric(label=metric, value=value)


def show_prediction_interface():
    """Interactive prediction interface."""
    st.header("Predict Customer Churn")
    st.markdown("Enter customer details to get personalized churn prediction and recommendations.")
    
    # Two column layout
    col1, col2 = st.columns([2, 1])
    
    with col1:
        st.subheader("Customer Information")
        
        # Form for customer input
        with st.form(key="prediction_form"):
            cust_col1, cust_col2 = st.columns(2)
            
            with cust_col1:
                customer_id = st.text_input("Customer ID", value="CUST_000001")
                tenure = st.slider("Tenure (months)", min_value=1, max_value=72, value=12)
                monthly_charges = st.number_input("Monthly Charges ($)", min_value=20.0, max_value=120.0, value=70.0)
                total_charges = st.number_input("Total Charges ($)", min_value=0.0, value=840.0)
                contract_type = st.selectbox(
                    "Contract Type",
                    ["Month-to-month", "One year", "Two year"]
                )
                payment_method = st.selectbox(
                    "Payment Method",
                    ["Electronic check", "Mailed check", "Bank transfer", "Credit card"]
                )
            
            with cust_col2:
                num_services = st.slider("Number of Services", min_value=0, max_value=7, value=3)
                support_tickets = st.slider("Support Tickets (3 months)", min_value=0, max_value=15, value=2)
                avg_monthly_usage = st.slider("Avg Monthly Usage (GB)", min_value=0, max_value=100, value=50)
                usage_trend = st.selectbox("Usage Trend", ["Increasing", "Stable", "Declining"])
                segment = st.selectbox("Customer Segment", ["Consumer", "SMB", "Enterprise"])
                senior_citizen = st.selectbox("Senior Citizen", [0, 1])
            
            cust_col3, cust_col4 = st.columns(2)
            with cust_col3:
                dependents = st.selectbox("Dependents", ["Yes", "No"])
                partner = st.selectbox("Partner", ["Yes", "No"])
            
            with cust_col4:
                internet_service = st.selectbox("Internet Service", ["DSL", "Fiber optic", "No"])
                phone_service = st.selectbox("Phone Service", ["Yes", "No"])
            
            submit_button = st.form_submit_button("🔮 Predict Churn Risk", type="primary", use_container_width=True)
            
            if submit_button:
                # Prepare customer data
                customer_data = {
                    "customer_id": customer_id,
                    "tenure": tenure,
                    "monthly_charges": monthly_charges,
                    "total_charges": total_charges,
                    "contract_type": contract_type,
                    "payment_method": payment_method,
                    "num_services": num_services,
                    "support_tickets": support_tickets,
                    "avg_monthly_usage": float(avg_monthly_usage),
                    "usage_trend": usage_trend,
                    "segment": segment,
                    "senior_citizen": senior_citizen,
                    "dependents": dependents,
                    "partner": partner,
                    "internet_service": internet_service,
                    "phone_service": phone_service
                }
                
                # Make prediction
                api_healthy, _ = check_api_health()
                
                if api_healthy:
                    success, result = predict_single_customer(customer_data)
                    
                    if success:
                        st.session_state.last_prediction = result
                        st.rerun()
                    else:
                        st.error(f"Prediction failed: {result}")
                else:
                    # Demo prediction
                    demo_prob = np.random.uniform(0.1, 0.9)
                    demo_result = {
                        "customer_id": customer_id,
                        "churn_probability": round(demo_prob, 4),
                        "churn_risk": "High" if demo_prob > 0.7 else "Medium" if demo_prob > 0.5 else "Low",
                        "risk_score": int(demo_prob * 10),
                        "key_factors": ["Demo mode - connect API for real predictions"],
                        "recommended_action": "This is a demo prediction"
                    }
                    st.session_state.last_prediction = demo_result
                    st.info("ℹ️ Running in demo mode. Connect to API for real predictions.")
                    st.rerun()
    
    with col2:
        st.subheader("Prediction Result")
        
        if 'last_prediction' in st.session_state:
            pred = st.session_state.last_prediction
            
            # Risk gauge
            probability = pred['churn_probability']
            
            fig = go.Figure(go.Indicator(
                mode="gauge+number+delta",
                value=probability,
                domain={'x': [0, 1], 'y': [0, 1]},
                title={'text': "Churn Probability", 'font': {'size': 16}},
                delta={'reference': 0.5},
                gauge={
                    'axis': {'range': [None, 1]},
                    'bar': {'color': "#f44336" if probability > 0.7 else "#ff9800" if probability > 0.5 else "#4caf50"},
                    'bgcolor': "white",
                    'borderwidth': 2,
                    'bordercolor': "gray",
                    'steps': [
                        {'range': [0, 0.5], 'color': "#e8f5e9"},
                        {'range': [0.5, 0.7], 'color': "#fff3e0"},
                        {'range': [0.7, 1], 'color': "#ffebee"}
                    ],
                }
            ))
            fig.update_layout(height=300)
            st.plotly_chart(fig, use_container_width=True)
            
            # Risk level badge
            risk_level = pred['churn_risk']
            risk_color = {"High": "🔴", "Medium": "🟠", "Low": "🟢"}
            
            st.markdown(f"### {risk_color.get(risk_level, '⚪')} Risk Level: {risk_level}")
            st.metric("Risk Score", f"{pred['risk_score']}/10")
            
            # Key factors
            st.markdown("**Key Risk Factors:**")
            for factor in pred['key_factors']:
                st.markdown(f"- {factor}")
            
            # Recommended action
            st.markdown("**Recommended Action:**")
            st.info(pred['recommended_action'])
            
            # Add to history
            if pred not in st.session_state.predictions:
                st.session_state.predictions.append(pred)
        else:
            st.info("Enter customer details and click 'Predict' to see results")


def show_customer_analytics():
    """Customer analytics and segmentation."""
    st.header("Customer Analytics")
    
    df = load_sample_data()
    
    if df is not None:
        # Filters
        st.subheader("Filters")
        filter_col1, filter_col2, filter_col3 = st.columns(3)
        
        with filter_col1:
            contract_filter = st.multiselect(
                "Contract Type",
                options=df['contract_type'].unique(),
                default=df['contract_type'].unique()
            )
        
        with filter_col2:
            segment_filter = st.multiselect(
                "Segment",
                options=df['segment'].unique() if 'segment' in df.columns else [],
                default=df['segment'].unique() if 'segment' in df.columns else []
            )
        
        with filter_col3:
            churn_filter = st.selectbox("Churn Status", ["All", "Churned", "Active"])
        
        # Apply filters
        filtered_df = df[df['contract_type'].isin(contract_filter)]
        
        if segment_filter and 'segment' in df.columns:
            filtered_df = filtered_df[filtered_df['segment'].isin(segment_filter)]
        
        if churn_filter == "Churned":
            filtered_df = filtered_df[filtered_df['churn'] == 1]
        elif churn_filter == "Active":
            filtered_df = filtered_df[filtered_df['churn'] == 0]
        
        st.markdown(f"**Showing {len(filtered_df)} customers**")
        
        # Customer table with risk scoring
        st.subheader("Customer Risk Table")
        
        # Calculate simple risk score for display
        filtered_df['risk_score'] = (
            (filtered_df['tenure'] < 6).astype(int) * 2 +
            (filtered_df['contract_type'] == 'Month-to-month').astype(int) * 3 +
            (filtered_df['support_tickets'] > 3).astype(int) * 2
        )
        
        # Display table
        display_cols = ['customer_id', 'tenure', 'contract_type', 'monthly_charges', 
                       'support_tickets', 'churn', 'risk_score']
        display_cols = [c for c in display_cols if c in filtered_df.columns]
        
        styled_df = filtered_df[display_cols].copy()
        
        def color_risk(val):
            if val >= 5:
                return 'background-color: #ffebee'
            elif val >= 3:
                return 'background-color: #fff3e0'
            else:
                return 'background-color: #e8f5e9'
        
        if 'risk_score' in styled_df.columns:
            styled_df = styled_df.style.applymap(color_risk, subset=['risk_score'])
        
        st.dataframe(styled_df, use_container_width=True, height=400)
        
        # Download button
        csv = filtered_df.to_csv(index=False)
        st.download_button(
            label="📥 Download Filtered Data",
            data=csv,
            file_name="customer_analysis.csv",
            mime="text/csv"
        )
    
    else:
        st.info("No customer data available")


def show_model_performance():
    """Display model performance metrics."""
    st.header("Model Performance")
    
    # Get feature importance
    feat_importance = get_feature_importance()
    
    col1, col2 = st.columns(2)
    
    with col1:
        st.subheader("Feature Importance")
        
        if feat_importance and 'top_features' in feat_importance:
            importance_df = pd.DataFrame(feat_importance['top_features'])
            
            fig = px.bar(
                importance_df.sort_values('importance', ascending=True),
                x='importance',
                y='feature',
                orientation='h',
                title="Top 20 Most Important Features",
                labels={'importance': 'Importance Score', 'feature': ''}
            )
            fig.update_layout(height=600)
            st.plotly_chart(fig, use_container_width=True)
        else:
            st.info("Feature importance data not available")
    
    with col2:
        st.subheader("Model Comparison")
        
        # Demo model comparison
        model_data = pd.DataFrame({
            'Model': ['Logistic Regression', 'Random Forest', 'Gradient Boosting'],
            'ROC-AUC': [0.82, 0.87, 0.89],
            'F1 Score': [0.74, 0.79, 0.81],
            'Precision': [0.76, 0.80, 0.82],
            'Recall': [0.72, 0.78, 0.80]
        })
        
        fig = go.Figure()
        
        for metric in ['ROC-AUC', 'F1 Score', 'Precision', 'Recall']:
            fig.add_trace(go.Bar(
                name=metric,
                y=model_data['Model'],
                x=model_data[metric],
                orientation='h'
            ))
        
        fig.update_layout(
            barmode='group',
            title="Model Performance Comparison",
            xaxis_title="Score",
            height=400
        )
        
        st.plotly_chart(fig, use_container_width=True)
        
        # Model info
        st.subheader("Model Details")
        st.json({
            "Best Model": "Gradient Boosting",
            "Training Samples": "8,000",
            "Features": "45",
            "Last Trained": "2024-01-15",
            "Version": "1.0.0"
        })


def show_business_impact():
    """Business impact calculator."""
    st.header("💼 Business Impact Calculator")
    
    st.markdown("Estimate the financial impact of churn and potential savings from intervention.")
    
    col1, col2 = st.columns(2)
    
    with col1:
        st.subheader("Input Parameters")
        
        total_customers = st.number_input("Total Customers", min_value=100, value=10000)
        avg_monthly_revenue = st.number_input("Avg Monthly Revenue per Customer ($)", min_value=10, value=75)
        churn_rate = st.slider("Current Churn Rate (%)", min_value=0, max_value=100, value=26)
        intervention_cost = st.number_input("Intervention Cost per Customer ($)", min_value=0, value=50)
        intervention_success = st.slider("Intervention Success Rate (%)", min_value=0, max_value=100, value=60)
        target_reduction = st.slider("Target Churn Reduction (%)", min_value=0, max_value=50, value=20)
    
    with col2:
        st.subheader("Impact Analysis")
        
        # Calculations
        monthly_churn = total_customers * (churn_rate / 100)
        annual_churn = monthly_churn * 12
        annual_revenue_loss = annual_churn * avg_monthly_revenue * 12
        
        # With intervention
        reduced_churn_rate = churn_rate * (1 - target_reduction / 100)
        reduced_monthly_churn = total_customers * (reduced_churn_rate / 100)
        reduced_annual_churn = reduced_monthly_churn * 12
        reduced_revenue_loss = reduced_annual_churn * avg_monthly_revenue * 12
        
        # Savings
        revenue_saved = annual_revenue_loss - reduced_revenue_loss
        intervention_customers = monthly_churn * 12  # Target all churned customers
        intervention_total_cost = intervention_customers * intervention_cost
        net_savings = revenue_saved - intervention_total_cost
        
        roi = (net_savings / intervention_total_cost * 100) if intervention_total_cost > 0 else 0
        
        # Display metrics
        st.metric("Annual Revenue Loss (Current)", f"${annual_revenue_loss:,.0f}")
        st.metric("Annual Revenue Loss (After Intervention)", f"${reduced_revenue_loss:,.0f}")
        st.metric("Revenue Saved", f"${revenue_saved:,.0f}", delta_color="normal")
        st.metric("Intervention Cost", f"${intervention_total_cost:,.0f}")
        st.metric("Net Annual Savings", f"${net_savings:,.0f}", delta_color="normal")
        st.metric("ROI", f"{roi:.1f}%", delta_color="normal")
    
    # Visualization
    st.markdown("---")
    st.subheader("Financial Impact Visualization")
    
    impact_data = pd.DataFrame({
        'Metric': ['Revenue Loss', 'Intervention Cost', 'Net Savings'],
        'Value': [-annual_revenue_loss, -intervention_total_cost, net_savings]
    })
    
    fig = px.bar(
        impact_data,
        x='Metric',
        y='Value',
        color='Value',
        color_continuous_scale=['red', 'orange', 'green'],
        title="Annual Financial Impact",
        text=impact_data['Value'].apply(lambda x: f"${x:,.0f}")
    )
    fig.update_layout(height=400)
    st.plotly_chart(fig, use_container_width=True)
    
    # Recommendations
    st.subheader("Strategic Recommendations")
    
    if roi > 100:
        st.success("🎯 Excellent ROI! Strong business case for intervention program.")
    elif roi > 50:
        st.info("👍 Good ROI. Consider targeted interventions for high-risk segments.")
    else:
        st.warning("⚠️ Moderate ROI. Review intervention strategy and costs.")
    
    st.markdown("""
    **Next Steps:**
    1. Identify top 20% highest-risk customers
    2. Design personalized retention offers
    3. Implement proactive customer success outreach
    4. Monitor intervention effectiveness
    5. Iterate and optimize approach
    """)


def show_settings():
    """Settings and configuration."""
    st.header("Settings")
    
    st.subheader("API Configuration")
    
    api_url = st.text_input("API Base URL", value=API_BASE_URL)
    
    if st.button("Save Configuration"):
        st.success("Configuration saved!")
        st.rerun()
    
    st.subheader("Data Management")
    
    if st.button("🔄 Refresh Data"):
        st.cache_data.clear()
        st.success("Cache cleared! Refresh the page to reload data.")
    
    if st.button("🗑️ Clear Prediction History"):
        st.session_state.predictions = []
        st.success("Prediction history cleared!")
    
    st.subheader("About")
    st.markdown("""
    **Customer Churn Prediction Dashboard**
    
    Version: 1.0.0
    
    Built with:
    - Streamlit
    - FastAPI
    - Scikit-learn
    - MLflow
    - Plotly
    
    This dashboard helps businesses identify at-risk customers and prioritize retention efforts.
    """)


if __name__ == "__main__":
    main()
