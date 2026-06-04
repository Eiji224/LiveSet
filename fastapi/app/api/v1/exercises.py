from fastapi import APIRouter, HTTPException, Depends, status
from sqlalchemy import select
from sqlalchemy.ext.asyncio import AsyncSession

from typing import List

from app.database import get_db
from app.models import Exercise
from app.schemas import ApiResponse, ExerciseResponse, ExerciseCreate

router = APIRouter(
    prefix='/exercises',
    tags=['Exercises']
)

@router.get('/', response_model=ApiResponse[List[ExerciseResponse]])
async def get_exercises(db: AsyncSession = Depends(get_db)):
    query = select(Exercise)
    result = await db.execute(query)

    exercises = result.scalars().all()

    return {
        'status': 200,
        'body': exercises,
        'errors': None
    }

@router.post('/', response_model=ApiResponse[ExerciseResponse], status_code=status.HTTP_201_CREATED)
async def create_exercise(payload: ExerciseCreate, db: AsyncSession = Depends(get_db)):
    new_exercise = Exercise(
        title = payload.title,
        body_part = payload.body_part,
        instruction = payload.instruction,
        description = payload.description,
        user_id = payload.user_id
    )

    db.add(new_exercise)
    await db.commit()
    await db.refresh(new_exercise)

    return {
        'status': 200,
        'body': new_exercise,
        'errors': None
    }