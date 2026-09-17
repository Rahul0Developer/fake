import boto3
from botocore.exceptions import ClientError
from app.config import settings
from typing import Optional


def get_s3_client():
    """Get S3 client based on configuration."""
    if settings.S3_ENDPOINT_URL:
        # Use MinIO or other S3-compatible storage
        return boto3.client(
            's3',
            endpoint_url=settings.S3_ENDPOINT_URL,
            aws_access_key_id=settings.S3_ACCESS_KEY,
            aws_secret_access_key=settings.S3_SECRET_KEY,
            region_name=settings.S3_REGION
        )
    else:
        # Use AWS S3
        return boto3.client(
            's3',
            aws_access_key_id=settings.S3_ACCESS_KEY,
            aws_secret_access_key=settings.S3_SECRET_KEY,
            region_name=settings.S3_REGION
        )


def upload_file(file_obj, object_key: str, content_type: str = "audio/wav") -> bool:
    """Upload a file to S3 storage."""
    try:
        s3_client = get_s3_client()
        s3_client.upload_fileobj(
            file_obj,
            settings.S3_BUCKET_NAME,
            object_key,
            ExtraArgs={'ContentType': content_type}
        )
        return True
    except ClientError as e:
        print(f"Upload error: {e}")
        return False


def get_signed_url(object_key: str, expiration: int = 3600) -> Optional[str]:
    """Generate a signed URL for accessing an object."""
    try:
        s3_client = get_s3_client()
        url = s3_client.generate_presigned_url(
            'get_object',
            Params={'Bucket': settings.S3_BUCKET_NAME, 'Key': object_key},
            ExpiresIn=expiration
        )
        return url
    except ClientError as e:
        print(f"URL generation error: {e}")
        return None


def delete_file(object_key: str) -> bool:
    """Delete a file from S3 storage."""
    try:
        s3_client = get_s3_client()
        s3_client.delete_object(Bucket=settings.S3_BUCKET_NAME, Key=object_key)
        return True
    except ClientError as e:
        print(f"Delete error: {e}")
        return False
