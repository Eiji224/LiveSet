from typing import AsyncGenerator
from sqlalchemy.ext.asyncio import create_async_engine, async_sessionmaker, AsyncSession
import redis.asyncio as redis

from app.config import settings


DATABASE_URL = f"mysql+aiomysql://{settings.DB_USERNAME}:{settings.DB_PASSWORD}@{settings.DB_HOST}:{settings.DB_PORT}/{settings.DB_DATABASE}"

engine = create_async_engine(DATABASE_URL, echo=settings.DEBUG)

async_session_maker = async_sessionmaker(engine, expire_on_commit=False)

redis_pool = redis.ConnectionPool.from_url(
    f"redis://{settings.REDIS_HOST}:{settings.REDIS_PORT}/{settings.REDIS_DB}",
    encoding="utf-8",
    decode_responses=True,
    socket_keepalive=True,
    health_check_interval=30,
)

async def get_db() -> AsyncGenerator[AsyncSession, None]:
    async with async_session_maker() as session:
        yield session

def get_redis() -> redis.Redis:
    return redis.Redis(connection_pool=redis_pool)