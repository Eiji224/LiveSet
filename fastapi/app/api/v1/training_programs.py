from fastapi import APIRouter, Depends, Response
from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from sqlalchemy.orm import selectinload

from typing import List

from app.models import TrainingProgram, ProgramExercise
from app.services import CreateTraining
from app.schemas import ApiResponse, StatusCreated, TrainingProgramSchema
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
        await CreateTraining(payload, db)
    except Exception as e:
        return {
            'status': 500,
            'body': None,
            'errors': e
        }
    
    return {
        'status': 201,
        'body': {
            "created": True
        },
        'errors': None
    }