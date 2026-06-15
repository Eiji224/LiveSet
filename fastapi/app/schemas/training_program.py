from pydantic import BaseModel, Field
from typing import List
from app.schemas import TuningModel


class SetSchema(TuningModel):
    id: int = Field(..., description='ID of set')
    weight: int = Field(..., description='Weight that used in set')
    reps: int = Field(..., description='How many times exercise was done in this set')
    rest_time: int = Field(..., description='Time need to rest after set in sec')


class ProgramExerciseSchema(TuningModel):
    id: int = Field(..., description='ID of the program exercise')
    title: str = Field(..., description='Title of the exercise')
    exercise_id: int = Field(..., description='ID of exercise that was used in program')
    sets: List[SetSchema] = Field(..., description='List of sets for this program')


class TrainingProgramSchema(TuningModel):
    title: str = Field(..., max_length=255, description='Title of the training program')
    description: str = Field(..., description='Description of the training program')
    is_private: bool = Field(..., description='Does only author of training program has access to one?')
    user_id: int = Field(..., description='ID of author of this training')
    training_time: int = Field(..., description='Length of the training in seconds')
    program_exercises: List[ProgramExerciseSchema] = Field(..., description='Exercises of the training program')