import asyncio

from app.database import get_redis
from app.services.logger import write_log


async def start_redis_listener():
    redis = get_redis()
    pubsub = redis.pubsub()
    channel_name = "liveset-events"

    await pubsub.subscribe(channel_name)
    write_log(f"Worker has subscribed to Redis channel: {channel_name}")

    try:
        async for message in pubsub.listen():
            write_log(f"Got message: {message}")
    except asyncio.CancelledError:
        write_log("Stopping...")
        await pubsub.unsubscribe(channel_name)
        await redis.close()