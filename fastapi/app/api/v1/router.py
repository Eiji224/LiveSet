from fastapi import APIRouter

from app.api.v1.exercises import router as exercises_router

router = APIRouter(prefix='/v1')
router.include_router(exercises_router)