from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session
from pydantic import BaseModel
from typing import Optional
import uuid

from app.database import get_db
from routers.auth import get_current_user
from models.database import AgreementRun, Annotation, Annotator
from utils.agreement import compute_agreement, interpret_kappa

router = APIRouter(prefix="/agreement", tags=["Agreement"])


class AgreementResponse(BaseModel):
    annotator_a_id: str
    annotator_b_id: str
    matched_clips: int
    cohens_kappa: Optional[float]
    avg_prosody_jaccard: Optional[float]
    interpretation: Optional[str]


class AgreementComputeRequest(BaseModel):
    annotator_a_id: str
    annotator_b_id: str


@router.post("/compute")
async def compute_agreement_endpoint(
    request: AgreementComputeRequest,
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Compute inter-annotator agreement between two annotators."""
    # Only admins/reviewers can compute agreement
    if current_user.role not in ["admin", "reviewer"]:
        raise HTTPException(status_code=403, detail="Insufficient permissions")
    
    try:
        annotator_a_id = uuid.UUID(request.annotator_a_id)
        annotator_b_id = uuid.UUID(request.annotator_b_id)
    except ValueError:
        raise HTTPException(status_code=400, detail="Invalid annotator ID format")
    
    # Get annotations from both annotators
    annotations_a = db.query(Annotation).filter(
        Annotation.annotator_id == annotator_a_id
    ).all()
    
    annotations_b = db.query(Annotation).filter(
        Annotation.annotator_id == annotator_b_id
    ).all()
    
    if not annotations_a or not annotations_b:
        raise HTTPException(status_code=400, detail="One or both annotators have no annotations")
    
    # Convert to dict format for agreement computation
    pairs_a = [
        {
            "clip_id": str(ann.clip_id),
            "naturalness": ann.naturalness,
            "prosody_tags": ann.prosody_tags
        }
        for ann in annotations_a
    ]
    
    pairs_b = [
        {
            "clip_id": str(ann.clip_id),
            "naturalness": ann.naturalness,
            "prosody_tags": ann.prosody_tags
        }
        for ann in annotations_b
    ]
    
    # Compute agreement
    result = compute_agreement(pairs_a, pairs_b)
    
    if result["matched"] == 0:
        raise HTTPException(status_code=400, detail="No matching clips found between annotators")
    
    # Store result
    agreement_run = AgreementRun(
        annotator_a=annotator_a_id,
        annotator_b=annotator_b_id,
        clip_count=result["matched"],
        cohens_kappa=result["kappa"],
        avg_prosody_jaccard=result["avg_jaccard"]
    )
    
    db.add(agreement_run)
    db.commit()
    
    return {
        "annotator_a_id": str(annotator_a_id),
        "annotator_b_id": str(annotator_b_id),
        "matched_clips": result["matched"],
        "cohens_kappa": result["kappa"],
        "avg_prosody_jaccard": result["avg_jaccard"],
        "interpretation": interpret_kappa(result["kappa"]) if result["kappa"] else None
    }


@router.get("/{annotator_a_id}/{annotator_b_id}", response_model=AgreementResponse)
async def get_agreement(
    annotator_a_id: str,
    annotator_b_id: str,
    db: Session = Depends(get_db),
    current_user: Annotator = Depends(get_current_user)
):
    """Retrieve latest computed agreement between two annotators."""
    try:
        annotator_a_uuid = uuid.UUID(annotator_a_id)
        annotator_b_uuid = uuid.UUID(annotator_b_id)
    except ValueError:
        raise HTTPException(status_code=400, detail="Invalid annotator ID format")
    
    # Get latest agreement run
    agreement = db.query(AgreementRun).filter(
        ((AgreementRun.annotator_a == annotator_a_uuid) & (AgreementRun.annotator_b == annotator_b_uuid)) |
        ((AgreementRun.annotator_a == annotator_b_uuid) & (AgreementRun.annotator_b == annotator_a_uuid))
    ).order_by(AgreementRun.computed_at.desc()).first()
    
    if not agreement:
        raise HTTPException(status_code=404, detail="No agreement computation found")
    
    return {
        "annotator_a_id": str(agreement.annotator_a),
        "annotator_b_id": str(agreement.annotator_b),
        "matched_clips": agreement.clip_count,
        "cohens_kappa": agreement.cohens_kappa,
        "avg_prosody_jaccard": agreement.avg_prosody_jaccard,
        "interpretation": interpret_kappa(agreement.cohens_kappa) if agreement.cohens_kappa else None
    }
