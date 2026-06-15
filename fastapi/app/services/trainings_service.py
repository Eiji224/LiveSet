from sqlalchemy.ext.asyncio import AsyncSession

from app.models import Set, ProgramExercise, TrainingProgram
from app.schemas import TrainingProgramSchema
from app.services import write_log


async def CreateTraining(response: TrainingProgramSchema, db: AsyncSession):
    training_program = TrainingProgram(
        title = response.title,
        description= response.description,
        training_time = response.training_time,
        is_private = response.is_private,
        user_id = response.user_id,
    )

    async with db.begin():
        try:
            programs = []
            for program_id, program in enumerate(response.program_exercises):
                sets = []
                for set_id, set_data in enumerate(program.sets):
                    new_set = Set(
                        order = set_id,
                        weight = set_data.weight,
                        reps = set_data.reps,
                        rest_time = set_data.rest_time,
                    )

                    sets.append(new_set)
                
                new_program = ProgramExercise(
                    order = program_id,
                    title = program.title,
                    exercise_id = program.exercise_id,
                    sets = sets
                )
                programs.append(new_program)
            
            training_program.program_exercises = programs
            db.add(training_program)

        except Exception as e:
            write_log(f"Failed to save training: {e}")
            raise e
    
    await db.refresh(training_program)
    return training_program