from fastapi import APIRouter

from app.api.v1.exercises import router as exercises_router
from app.api.v1.training_programs import router as trainings_router
from app.api.v1.websocket import router as websocket_router

router = APIRouter(prefix='/v1')
router.include_router(exercises_router)
router.include_router(trainings_router)
router.include_router(websocket_router)