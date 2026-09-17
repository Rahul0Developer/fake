from pydantic_settings import BaseSettings
from typing import Optional


class Settings(BaseSettings):
    # Database
    DATABASE_URL: str = "postgresql://postgres:postgres@localhost:5432/speech_annotation"
    
    # JWT
    SECRET_KEY: str = "your-secret-key-change-in-production"
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 30
    
    # S3/Object Storage
    S3_BUCKET_NAME: str = "speech-annotation"
    S3_ENDPOINT_URL: Optional[str] = None  # For MinIO or other S3-compatible
    S3_ACCESS_KEY: str = ""
    S3_SECRET_KEY: str = ""
    S3_REGION: str = "us-east-1"
    
    # App
    APP_NAME: str = "Multilingual Speech Annotation Console"
    DEBUG: bool = True

    class Config:
        env_file = ".env"


settings = Settings()
