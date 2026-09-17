from fastapi import APIRouter, Depends, HTTPException, Query
from fastapi.responses import StreamingResponse
from sqlalchemy.orm import Session
from pydantic import BaseModel
from typing import Optional, List
import uuid
import csv
import io
import json
from datetime import datetime

from app.database import get_db
from routers.auth import get_current_user
from models.database import Annotation, Clip, Annotator

router = APIRouter(prefix="/annotations", tags=["Annotations"])


class AnnotationCreate(BaseModel):
    clip_id: str
    language: str
    hindi_dialect: Optional[str] = None
    english_clarity: Optional[str] = None
    speaker_id: Optional[str] = None
    transcript: str
    naturalness: int
    accent_strength: Optional[str] = None
    noise_tags: List[str] = []
    prosody_tags: List[str] = []
    disfluency_tags: List[str] = []
    evaluator_judgment: Optional[str] = None
    estimated_f0_hz: Optional[float] = None


class AnnotationResponse(BaseModel):
    id: str
    clip_id: str
    annotator_id: str
    language: str
    hindi_dialect: Optional[str]
    english_clarity: Optional[str]
    speaker_id: Optional[str]
    transcript: str
    naturalness: int
    accent_strength: Optional[str]
    noise_tags: List[str]
    prosody_tags: List[str]
    disfluency_tags: List[str]
    evaluator_judgment: Optional[str]
    estimated_f0_hz: Optional[float]
    created_at: datetime
    
    class Config:
        from_attributes = True


@router.post("", response_model=AnnotationResponse)
async def create_annotation(
    annotation_data: AnnotationCreate,
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Submit an annotation for a clip."""
    # Verify clip exists
    clip = db.query(Clip).filter(Clip.id == uuid.UUID(annotation_data.clip_id)).first()
    if not clip:
        raise HTTPException(status_code=404, detail="Clip not found")
    
    # Check if annotation already exists (upsert behavior)
    existing = db.query(Annotation).filter(
        Annotation.clip_id == clip.id,
        Annotation.annotator_id == current_user.id
    ).first()
    
    if existing:
        # Update existing annotation
        existing.language = annotation_data.language
        existing.hindi_dialect = annotation_data.hindi_dialect
        existing.english_clarity = annotation_data.english_clarity
        existing.speaker_id = annotation_data.speaker_id
        existing.transcript = annotation_data.transcript
        existing.naturalness = annotation_data.naturalness
        existing.accent_strength = annotation_data.accent_strength
        existing.noise_tags = annotation_data.noise_tags
        existing.prosody_tags = annotation_data.prosody_tags
        existing.disfluency_tags = annotation_data.disfluency_tags
        existing.evaluator_judgment = annotation_data.evaluator_judgment
        existing.estimated_f0_hz = annotation_data.estimated_f0_hz
        
        db.commit()
        db.refresh(existing)
        return existing
    else:
        # Create new annotation
        annotation = Annotation(
            clip_id=clip.id,
            annotator_id=current_user.id,
            **annotation_data.dict()
        )
        
        db.add(annotation)
        db.commit()
        db.refresh(annotation)
        return annotation


@router.get("", response_model=List[AnnotationResponse])
async def list_annotations(
    annotator_id: Optional[str] = Query(None),
    clip_id: Optional[str] = Query(None),
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Query annotations for review or export."""
    query = db.query(Annotation)
    
    if annotator_id:
        query = query.filter(Annotation.annotator_id == uuid.UUID(annotator_id))
    
    if clip_id:
        query = query.filter(Annotation.clip_id == uuid.UUID(clip_id))
    
    # Only allow annotators to see their own annotations, unless admin/reviewer
    if current_user.role not in ["admin", "reviewer"]:
        query = query.filter(Annotation.annotator_id == current_user.id)
    
    return query.all()


@router.get("/export")
async def export_annotations(
    format: str = Query("json", description="Export format: json or csv"),
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Export annotations in JSON or CSV format."""
    # Only admins/reviewers can export all data
    if current_user.role not in ["admin", "reviewer"]:
        raise HTTPException(status_code=403, detail="Insufficient permissions")
    
    annotations = db.query(Annotation).all()
    
    if format == "csv":
        output = io.StringIO()
        fieldnames = [
            "id", "clip_id", "annotator_id", "language", "hindi_dialect",
            "english_clarity", "speaker_id", "transcript", "naturalness",
            "accent_strength", "noise_tags", "prosody_tags", "disfluency_tags",
            "evaluator_judgment", "estimated_f0_hz", "created_at"
        ]
        
        writer = csv.DictWriter(output, fieldnames=fieldnames)
        writer.writeheader()
        
        for ann in annotations:
            row = {
                "id": str(ann.id),
                "clip_id": str(ann.clip_id),
                "annotator_id": str(ann.annotator_id),
                "language": ann.language,
                "hindi_dialect": ann.hindi_dialect,
                "english_clarity": ann.english_clarity,
                "speaker_id": ann.speaker_id,
                "transcript": ann.transcript,
                "naturalness": ann.naturalness,
                "accent_strength": ann.accent_strength,
                "noise_tags": ";".join(ann.noise_tags) if ann.noise_tags else "",
                "prosody_tags": ";".join(ann.prosody_tags) if ann.prosody_tags else "",
                "disfluency_tags": ";".join(ann.disfluency_tags) if ann.disfluency_tags else "",
                "evaluator_judgment": ann.evaluator_judgment,
                "estimated_f0_hz": ann.estimated_f0_hz,
                "created_at": ann.created_at.isoformat()
            }
            writer.writerow(row)
        
        output.seek(0)
        return StreamingResponse(
            io.BytesIO(output.getvalue().encode('utf-8')),
            media_type="text/csv",
            headers={"Content-Disposition": "attachment; filename=annotations.csv"}
        )
    
    else:  # JSON
        data = []
        for ann in annotations:
            data.append({
                "id": str(ann.id),
                "clip_id": str(ann.clip_id),
                "annotator_id": str(ann.annotator_id),
                "language": ann.language,
                "hindi_dialect": ann.hindi_dialect,
                "english_clarity": ann.english_clarity,
                "speaker_id": ann.speaker_id,
                "transcript": ann.transcript,
                "naturalness": ann.naturalness,
                "accent_strength": ann.accent_strength,
                "noise_tags": ann.noise_tags,
                "prosody_tags": ann.prosody_tags,
                "disfluency_tags": ann.disfluency_tags,
                "evaluator_judgment": ann.evaluator_judgment,
                "estimated_f0_hz": ann.estimated_f0_hz,
                "created_at": ann.created_at.isoformat()
            })
        
        return StreamingResponse(
            io.BytesIO(json.dumps(data, indent=2).encode('utf-8')),
            media_type="application/json",
            headers={"Content-Disposition": "attachment; filename=annotations.json"}
        )
