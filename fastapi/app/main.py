import asyncio
from contextlib import asynccontextmanager
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.config import settings
from app.api.v1 import router
from app.services import start_redis_listener

from datetime import datetime


@asynccontextmanager
async def lifespan(app: FastAPI):
    worker_task = asyncio.create_task(start_redis_listener())
    yield

    worker_task.cancel()
    await asyncio.gather(worker_task, return_exceptions=True)


app = FastAPI(title=settings.PROJECT_NAME, debug=settings.DEBUG, lifespan=lifespan)
app.add_middleware(
    CORSMiddleware,
    allow_origins=['*'],
    allow_credentials=True,
    allow_methods=['*'],
    allow_headers=['*'],
)

app.include_router(router, prefix='/api')

@app.get('/health')
async def health():
    return {
        'status': 'ok',
        'time': str(datetime.now())
    }