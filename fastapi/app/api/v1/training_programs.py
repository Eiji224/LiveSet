from fastapi import APIRouter, Depends, Response
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from sqlalchemy.orm import selectinload

from typing import List

from app.models import TrainingProgram, ProgramExercise
from app.services import CreateTraining, redis_publish_event
from app.schemas import ApiResponse, StatusCreated, TrainingProgramSchema, RedisMessage
from app.database import get_db


router = APIRouter(
    prefix='/trainings',
    tags=['Training Programs']
)


@router.get('/{training_id}', response_model=ApiResponse[TrainingProgramSchema])
async def read_training_program(training_id: int, response: Response, db: AsyncSession = Depends(get_db)):
    stmt = (
        select(TrainingProgram)
        .where(TrainingProgram.id == training_id)
        .options(
            selectinload(TrainingProgram.program_exercises)
            .selectinload(ProgramExercise.sets)
        )
    )

    result = await db.execute(stmt)
    training = result.scalar_one_or_none()

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
        training = await CreateTraining(payload, db)

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