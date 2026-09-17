from fastapi import APIRouter, Depends, HTTPException, status, UploadFile, File, Form
from fastapi.responses import RedirectResponse
from sqlalchemy.orm import Session
from pydantic import BaseModel
from typing import Optional, List
import uuid
from datetime import datetime

from app.database import get_db
from routers.auth import get_current_user
from models.database import Clip, Annotation, Annotator
from storage.s3_storage import upload_file, get_signed_url

router = APIRouter(prefix="/clips", tags=["Clips"])


class ClipResponse(BaseModel):
    id: str
    storage_key: str
    original_filename: Optional[str]
    duration_seconds: Optional[float]
    source: str
    created_at: datetime
    
    class Config:
        from_attributes = True


class AudioUrlResponse(BaseModel):
    url: str
    expires_in: int


@router.post("", response_model=ClipResponse)
async def upload_clip(
    file: UploadFile = File(...),
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Upload a new audio clip for annotation."""
    clip_id = uuid.uuid4()
    file_extension = file.filename.split(".")[-1] if file.filename else "wav"
    storage_key = f"clips/{clip_id}/original.{file_extension}"
    
    # Upload to S3
    success = upload_file(file.file, storage_key, content_type=file.content_type or "audio/wav")
    if not success:
        raise HTTPException(status_code=500, detail="Failed to upload file")
    
    # Create database record
    clip = Clip(
        id=clip_id,
        storage_key=storage_key,
        original_filename=file.filename,
        source="uploaded",
        uploaded_by=current_user.id
    )
    
    db.add(clip)
    db.commit()
    db.refresh(clip)
    
    return clip


@router.get("/next", response_model=Optional[ClipResponse])
async def get_next_clip(
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Get the next unannotated clip for the current user."""
    # Find clips that haven't been annotated by this user
    subquery = db.query(Clip.id).join(Annotation).filter(
        Annotation.annotator_id == current_user.id
    ).subquery()
    
    clip = db.query(Clip).filter(
        ~Clip.id.in_(subquery)
    ).first()
    
    if clip is None:
        return None
    
    return clip


@router.get("/{clip_id}/audio", response_model=AudioUrlResponse)
async def get_clip_audio(
    clip_id: str,
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Get a signed URL to stream an audio clip."""
    clip = db.query(Clip).filter(Clip.id == uuid.UUID(clip_id)).first()
    if not clip:
        raise HTTPException(status_code=404, detail="Clip not found")
    
    signed_url = get_signed_url(clip.storage_key, expiration=3600)
    if not signed_url:
        raise HTTPException(status_code=500, detail="Failed to generate signed URL")
    
    return {"url": signed_url, "expires_in": 3600}


@router.post("/{clip_id}/recording")
async def upload_recording(
    clip_id: str,
    file: UploadFile = File(...),
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Upload a recorded voice sample for a clip."""
    clip = db.query(Clip).filter(Clip.id == uuid.UUID(clip_id)).first()
    if not clip:
        raise HTTPException(status_code=404, detail="Clip not found")
    
    # Check if annotation exists for this clip by this user
    annotation = db.query(Annotation).filter(
        Annotation.clip_id == clip.id,
        Annotation.annotator_id == current_user.id
    ).first()
    
    if not annotation:
        raise HTTPException(status_code=400, detail="No annotation exists for this clip")
    
    # Upload recording
    storage_key = f"clips/{clip_id}/recordings/{annotation.id}.webm"
    success = upload_file(file.file, storage_key, content_type="audio/webm")
    if not success:
        raise HTTPException(status_code=500, detail="Failed to upload recording")
    
    return {"status": "success", "storage_key": storage_key}


@router.get("", response_model=List[ClipResponse])
async def list_clips(
    skip: int = 0,
    limit: int = 100,
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """List all clips (admin/reviewer only)."""
    if current_user.role not in ["admin", "reviewer"]:
        raise HTTPException(status_code=403, detail="Insufficient permissions")
    
    clips = db.query(Clip).offset(skip).limit(limit).all()
    return clips
