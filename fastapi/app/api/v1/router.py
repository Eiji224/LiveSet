from fastapi import APIRouter

from app.api.v1.exercises import router as exercises_router
from app.api.v1.training_programs import router as trainings_router

router = APIRouter(prefix='/v1')
router.include_router(exercises_router)
router.include_router(trainings_router)