import asyncio
import json

import redis.exceptions

from app.database import get_redis
from app.services.logger import write_log, logger


async def start_redis_listener():
    write_log("Redis listener task started")
    redis_client = get_redis()
    pubsub = redis_client.pubsub()
    channel_name = "liveset-events"

    try:
        await pubsub.subscribe(channel_name)
        write_log(f"Worker has subscribed to Redis channel: {channel_name}")

        while True:
            try:
                message = await pubsub.get_message(
                    ignore_subscribe_messages=True,
                    timeout=1.0,
                )
            except redis.exceptions.TimeoutError:
                continue

            if message is None:
                continue

            try:
                data = json.loads(message["data"])
            except (TypeError, ValueError):
                logger.warning("%r", message["data"])
                continue

            write_log(f"Got event: {data}")
    except asyncio.CancelledError:
        write_log("Stopping Redis listener...")
        raise
    except Exception:
        logger.exception("Redis listener has crashed")
    finally:
        await pubsub.aclose()
        await redis_client.aclose()