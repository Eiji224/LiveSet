from pydantic import BaseModel, Field
from typing import Optional

class ExerciseBase(BaseModel):
    title: str = Field(..., max_length=255, description='Title of the exercise')
    body_part: str = Field(... ,max_length=255, description='Fhich part of body will be improved')
    instruction: str = Field(..., description='Instruction for exercise')
    description: Optional[str] = Field(None, description='Description of exercise')
    user_id: int


class ExerciseCreate(ExerciseBase):
    pass


class ExerciseUpdate(ExerciseBase):
    title: Optional[str] = None
    body_part: Optional[str] = None
    instuction: Optional[str] = None
    description: Optional[str] = None


class ExerciseResponse(ExerciseBase):
    id: int
    
    model_config = {
        'from_attributes': True
    }