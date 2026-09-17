# Multilingual Speech Annotation Console

A production-grade multilingual (Hindi/English-first, extensible) speech data annotation platform for preparing training data for speech recognition, TTS, and voice-interaction AI systems.

## Features

- **Audio Visualization**: Real-time oscilloscope and frequency spectrum display
- **Pitch Detection**: F0 (fundamental frequency) estimation via autocorrelation
- **Recording**: In-browser audio recording via MediaRecorder API
- **Transcription**: Standardized disfluency markup ([um], [uh], [repeat], etc.)
- **Metadata Tagging**: Language/dialect selection, acoustic conditions, disfluency tags
- **Prosody Assessment**: Naturalness scoring (1-5), accent strength, prosody tags
- **Inter-Annotator Agreement**: Cohen's kappa and Jaccard similarity computation
- **Export**: CSV and JSON export of annotation sessions

## Project Structure

```
multilingual-speech-annotation/
├── backend/                 # FastAPI backend
│   ├── app/                # Main application
│   │   ├── __init__.py
│   │   ├── config.py       # Configuration settings
│   │   ├── database.py     # Database connection
│   │   ├── auth.py         # JWT authentication
│   │   └── main.py         # FastAPI app entry point
│   ├── models/             # SQLAlchemy models
│   │   ├── __init__.py
│   │   └── database.py     # Database schema
│   ├── routers/            # API endpoints
│   │   ├── __init__.py
│   │   ├── auth.py         # Authentication routes
│   │   ├── clips.py        # Clip management routes
│   │   ├── annotations.py  # Annotation routes
│   │   └── agreement.py    # Agreement computation routes
│   ├── storage/            # Object storage
│   │   ├── __init__.py
│   │   └── s3_storage.py   # S3/MinIO integration
│   ├── utils/              # Utilities
│   │   ├── __init__.py
│   │   └── agreement.py    # Agreement computation
│   ├── requirements.txt    # Python dependencies
│   ├── Dockerfile          # Backend Docker image
│   └── .env.example        # Environment variables template
├── frontend/               # Static frontend
│   └── index.html         # Single-page application
├── docker-compose.yml      # Docker Compose configuration
└── README.md              # This file
```

## Quick Start

### Using Docker Compose (Recommended)

1. **Clone the repository**

2. **Copy environment file**
   ```bash
   cp backend/.env.example backend/.env
   ```

3. **Start all services**
   ```bash
   docker-compose up -d
   ```

4. **Access the application**
   - Frontend: Open `frontend/index.html` in your browser
   - Backend API: http://localhost:8000
   - API Docs: http://localhost:8000/docs
   - MinIO Console: http://localhost:9001 (minioadmin/minioadmin)

### Manual Setup

#### Backend

1. **Install dependencies**
   ```bash
   cd backend
   pip install -r requirements.txt
   ```

2. **Set up environment variables**
   ```bash
   cp .env.example .env
   # Edit .env with your settings
   ```

3. **Start PostgreSQL** (if not using Docker)
   ```bash
   # Create database 'speech_annotation'
   ```

4. **Start MinIO** (if not using Docker)
   ```bash
   minio server ./data
   ```

5. **Run the backend**
   ```bash
   uvicorn app.main:app --reload
   ```

#### Frontend

Simply open `frontend/index.html` in a modern web browser.

## API Endpoints

### Authentication
- `POST /auth/login` - Login and get JWT token
- `POST /auth/register` - Register new annotator
- `GET /auth/me` - Get current user info

### Clips
- `POST /clips` - Upload audio clip
- `GET /clips/next` - Get next unannotated clip
- `GET /clips/{id}/audio` - Get signed URL for audio playback
- `POST /clips/{id}/recording` - Upload recorded sample

### Annotations
- `POST /annotations` - Submit annotation
- `GET /annotations` - Query annotations
- `GET /annotations/export?format=csv|json` - Export annotations

### Agreement
- `POST /agreement/compute` - Compute inter-annotator agreement
- `GET /agreement/{a_id}/{b_id}` - Get agreement results

## Data Model

### Annotator
- id, email, display_name, role, created_at

### Clip
- id, storage_key, original_filename, duration_seconds, source, uploaded_by, created_at

### Annotation
- id, clip_id, annotator_id, language, hindi_dialect, english_clarity, speaker_id
- transcript, naturalness (1-5), accent_strength
- noise_tags[], prosody_tags[], disfluency_tags[]
- evaluator_judgment, estimated_f0_hz, created_at

### AgreementRun
- id, annotator_a, annotator_b, clip_count, cohens_kappa, avg_prosody_jaccard, computed_at

## Tech Stack

- **Frontend**: Vanilla HTML/CSS/JS, Web Audio API, Canvas 2D
- **Backend**: Python, FastAPI
- **Database**: PostgreSQL
- **Object Storage**: S3-compatible (AWS S3 / MinIO / Cloudflare R2)
- **Auth**: JWT (python-jose, passlib)
- **Deployment**: Docker Compose

## License

MIT
