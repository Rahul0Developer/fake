from sqlalchemy import create_engine, Column, String, Integer, Float, Text, ForeignKey, CheckConstraint, UniqueConstraint
from sqlalchemy.dialects.postgresql import UUID, ARRAY, TIMESTAMPTZ
from sqlalchemy.orm import declarative_base, relationship, sessionmaker
from datetime import datetime
import uuid

Base = declarative_base()


class Annotator(Base):
    __tablename__ = "annotators"
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    email = Column(String, unique=True, nullable=False)
    display_name = Column(String, nullable=False)
    role = Column(String, nullable=False, default="annotator")  # annotator | reviewer | admin
    created_at = Column(TIMESTAMPTZ, nullable=False, default=datetime.utcnow)
    
    annotations = relationship("Annotation", back_populates="annotator")
    uploaded_clips = relationship("Clip", back_populates="uploader")


class Clip(Base):
    __tablename__ = "clips"
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    storage_key = Column(String, nullable=False)
    original_filename = Column(String)
    duration_seconds = Column(Float)
    sample_rate = Column(Integer)
    source = Column(String, nullable=False)  # 'uploaded' | 'recorded'
    uploaded_by = Column(UUID(as_uuid=True), ForeignKey("annotators.id"))
    created_at = Column(TIMESTAMPTZ, nullable=False, default=datetime.utcnow)
    
    uploader = relationship("Annotator", back_populates="uploaded_clips")
    annotations = relationship("Annotation", back_populates="clip")


class Annotation(Base):
    __tablename__ = "annotations"
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    clip_id = Column(UUID(as_uuid=True), ForeignKey("clips.id"), nullable=False)
    annotator_id = Column(UUID(as_uuid=True), ForeignKey("annotators.id"), nullable=False)
    language = Column(String, nullable=False)
    hindi_dialect = Column(String)
    english_clarity = Column(String)
    speaker_id = Column(String)
    transcript = Column(Text, nullable=False)
    naturalness = Column(Integer, nullable=False)
    accent_strength = Column(String)
    noise_tags = Column(ARRAY(String), nullable=False, default=list)
    prosody_tags = Column(ARRAY(String), nullable=False, default=list)
    disfluency_tags = Column(ARRAY(String), nullable=False, default=list)
    evaluator_judgment = Column(Text)
    estimated_f0_hz = Column(Float)
    created_at = Column(TIMESTAMPTZ, nullable=False, default=datetime.utcnow)
    
    __table_args__ = (
        CheckConstraint('naturalness >= 1 AND naturalness <= 5', name='check_naturalness_range'),
        UniqueConstraint('clip_id', 'annotator_id', name='unique_clip_annotator')
    )
    
    clip = relationship("Clip", back_populates="annotations")
    annotator = relationship("Annotator", back_populates="annotations")


class AgreementRun(Base):
    __tablename__ = "agreement_runs"
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    annotator_a = Column(UUID(as_uuid=True), ForeignKey("annotators.id"), nullable=False)
    annotator_b = Column(UUID(as_uuid=True), ForeignKey("annotators.id"), nullable=False)
    clip_count = Column(Integer, nullable=False)
    cohens_kappa = Column(Float)
    avg_prosody_jaccard = Column(Float)
    computed_at = Column(TIMESTAMPTZ, nullable=False, default=datetime.utcnow)
