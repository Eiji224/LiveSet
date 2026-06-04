from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.config import settings

from datetime import datetime

app = FastAPI(title=settings.PROJECT_NAME, debug=settings.DEBUG)
app.add_middleware(
    CORSMiddleware,
    allow_origins=['*'],
    allow_credentials=['*'],
    allow_methods=['*'],
    allow_headers=['*'],
)

@app.get('/health')
async def health():
    return {
        'status': 'ok',
        'time': str(datetime.now())
    }