import asyncio
import json
import redis.exceptions

from contextlib import asynccontextmanager
from sqlalchemy import select

from app.database import get_redis, get_db
from app.models import Exercise
from app.services.logger import write_log, logger
from app.services.live_training_manager import live_training_manager


CHANNEL_NAME = "liveset-events"


async def send_message(message: str):
    redis_clinet = get_redis()
    write_log("Publishing...")
    try:
        await redis_clinet.publish(CHANNEL_NAME, message)
    except Exception as e:
        write_log(f"Error has occured: {e}")

async def start_redis_listener():
    write_log("Redis listener task started")
    redis_client = get_redis()
    pubsub = redis_client.pubsub()

    try:
        await pubsub.subscribe(CHANNEL_NAME)
        write_log(f"Worker has subscribed to Redis channel: {CHANNEL_NAME}")

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
            data_type = data.get("type")
            data_payload = data.get("payload")

            match data_type:
                case "exercise_created":
                    await create_exercise(data_payload)
                case "exercise_deleted":
                    await delete_exercise(data_payload)
                case "exercise_updated":
                    await update_exercise(data_payload)
                case "live_session_started":
                    await handle_live_session_start(data_payload)
                case _:
                    logger.info(f"Unknown redis message type: {data_type}")

    except asyncio.CancelledError:
        write_log("Stopping Redis listener...")
        raise
    except Exception:
        logger.exception("Redis listener has crashed")
    finally:
        await pubsub.aclose()
        await redis_client.aclose()

async def create_exercise(payload: dict):
    async with asynccontextmanager(get_db)() as db:
        try:
            new_exercise = Exercise(
                title = payload["title"],
                body_part = payload["body_part"],
                instruction = payload["instruction"],
                description = payload["description"],
            )

            db.add(new_exercise)
            await db.commit()
        except Exception as e:
            await db.rollback()
            logger.error(f"Failed to create exercise: {e}")

async def update_exercise(payload: dict):
    async with asynccontextmanager(get_db)() as db:
        try:
            exercise_id = payload.get("id")

            result = await db.execute(
                select(Exercise).where(Exercise.id == exercise_id)
            )
            exercise = result.scalar_one_or_none()

            if not exercise:
                write_log(f"Exercise with id={payload["id"]} not found")
                return
            
            exercise.title = payload.get("title", exercise.title)
            exercise.description = payload.get("description", exercise.description)
            exercise.instruction = payload.get("instruction", exercise.instruction)
            exercise.body_part = payload.get("body_part", exercise.body_part)

            await db.commit()
        except Exception as e:
            await db.rollback
            logger.error(f"Failed to updated exercise: {e}")

async def delete_exercise(payload: dict):
    async with asynccontextmanager(get_db)() as db:
        try:
            result = await db.execute(
                select(Exercise).where(Exercise.id == payload["id"])
            )
            exercise = result.scalar_one_or_none()
            if exercise:
                await db.delete(exercise)
                await db.commit()
            else:
                write_log(f"Exercise with id={payload["id"]} not found")
        except Exception as e:
            logger.error(f"Failed to delete exercise: {e}")

async def handle_live_session_start(payload: dict):
    from app.services.trainings_service import find_training_program

    async with asynccontextmanager(get_db)() as db:
        training = await find_training_program(int(payload["training_program_id"]), db)

    live_training_manager.start_training(
        training=training,
        host_user_id=payload["host_user_id"],
        unique_url=payload["unique_url"]
    )