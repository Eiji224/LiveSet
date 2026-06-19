from fastapi import APIRouter, Depends, Response
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from sqlalchemy.orm import selectinload

from typing import List
from json import dumps as json_dumps

from app.models import TrainingProgram, ProgramExercise
from app.services import create_training, update_training, find_training_program, redis_publish_event, write_log
from app.schemas import ApiResponse, StatusCreated, TrainingProgramSchema, RedisMessage
from app.database import get_db


router = APIRouter(
    prefix='/trainings',
    tags=['Training Programs']
)


@router.get('/{training_id}', response_model=ApiResponse[TrainingProgramSchema])
async def read_training_program(training_id: int, response: Response, db: AsyncSession = Depends(get_db)):
    training = await find_training_program(training_id, db)

    if not training:
        response.status_code = 404

        return {
            'status': 404,
            'body': None,
            'errors': 'Not found'
        }

    return {
        'status': 200,
        'body': training,
        'errors': None
    }


@router.post('/', response_model=ApiResponse[StatusCreated])
async def create_training_program(payload: TrainingProgramSchema, db: AsyncSession = Depends(get_db)):
    try:
        training = await create_training(payload, db)

        # TODO: Вынести рассылку сообщения
        pydantic_model = TrainingProgramSchema.model_validate(training)
        message = RedisMessage(
            type="training_program_created",
            payload=pydantic_model.model_dump_json()
        )
        await redis_publish_event(message.model_dump_json())
    except Exception as e:
        return {
            'status': 500,
            'body': None,
            'errors': str(e)
        }
    
    return {
        'status': 201,
        'body': {
            "created": True
        },
        'errors': None
    }


@router.put('/{training_id}', response_model=ApiResponse[TrainingProgramSchema])
async def update_training_program(training_id: int, payload: TrainingProgramSchema, response: Response, db: AsyncSession = Depends(get_db)):
    try:
        db_training = await find_training_program(training_id, db)

        if not db_training:
            response.status_code = 404

            return {
                'status': 404,
                'body': db_training,
                'errors': None
            }
        
        updated_training = await update_training(db_training, payload, db)

        # TODO: Вынести рассылку сообщения
        pydantic_model = TrainingProgramSchema.model_validate(updated_training)
        message = RedisMessage(
            type="training_program_updated",
            payload=pydantic_model.model_dump_json()
        )
        await redis_publish_event(message.model_dump_json())
    except Exception as e:
        response.status_code = 500

        return {
            'status': 500,
            'body': None,
            'errors': str(e)
        }
    
    return {
        'status': 200,
        'body': updated_training,
        'errors': None
    }


@router.delete('/{training_id}')
async def delete_training_program(training_id: int, response: Response, db: AsyncSession = Depends(get_db)):
    try:
        stmt = select(TrainingProgram).where(TrainingProgram.id == training_id)
        result = await db.execute(stmt)
        db_training = result.scalar_one_or_none()

        if db_training is None:
            response.status_code = 404
            return {
                'status': 404,
                'body': None,
                'errors': None
            }
        
        await db.delete(db_training)
        await db.commit()
    except Exception as e:
        await db.rollback()
        response.status_code = 500
        return {
            'status': 500,
            'body': None,
            'errors': str(e)
        }
    
    message_payload = {
        'id': training_id,
    }
    message = RedisMessage(
        type="training_program_deleted",
        payload=json_dumps(message_payload)
    )
    await redis_publish_event(message.model_dump_json())

    response.status_code = 204