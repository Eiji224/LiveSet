from sqlalchemy.ext.asyncio import AsyncSession
from sqlalchemy import select
from sqlalchemy.orm import selectinload

from app.models import Set, ProgramExercise, TrainingProgram
from app.schemas import TrainingProgramSchema
from app.services import write_log


async def find_training_program(id: int, db: AsyncSession):
    stmt = (
        select(TrainingProgram)
        .where(TrainingProgram.id == id)
        .options(
            selectinload(TrainingProgram.program_exercises)
            .selectinload(ProgramExercise.sets)
        )
    )
    result = await db.execute(stmt)

    return result.scalar_one_or_none()


async def create_training(request: TrainingProgramSchema, db: AsyncSession):
    training_program = TrainingProgram()

    try:
        training_program = parse_training_program(request)
        db.add(training_program)

        await db.flush()
        training_id = training_program.id

        await db.commit()
        
    except Exception as e:
        await db.rollback()
        write_log(f"Failed to save training: {e}")
        raise e

    return await find_training_program(training_id, db)


async def update_training(db_training: TrainingProgram, request: TrainingProgramSchema, db: AsyncSession):
    try:
        existing_exercises = {ex.id: ex for ex in db_training.program_exercises}
        updated_exercises = []
        for ex_id, ex_data in enumerate(request.program_exercises):
            ex = ProgramExercise()
            if ex_data.id in existing_exercises:
                ex = existing_exercises.pop(ex_data.id)
                existing_sets = {s.id: s for s in ex.sets}

                ex.order = ex_id
                ex.title = ex_data.title
                ex.exercise_id = ex_data.exercise_id
                ex.sets = parse_sets(existing_sets, ex_data.sets)
            else:
                parsed_sets = parse_sets({}, ex_data.sets)
                ex = ProgramExercise(
                    order= ex_id,
                    title = ex_data.title,
                    exercise_id = ex_data.exercise_id,
                    sets = parsed_sets,
                )
            
            updated_exercises.append(ex)
        
        db_training.title = request.title
        db_training.description = request.description
        db_training.is_private = request.is_private
        db_training.training_time = request.training_time
        db_training.program_exercises = updated_exercises

        await db.commit()
    
    except Exception as e:
        await db.rollback()
        write_log(f"Failed to update training: {e}")
        raise e
        
    return await find_training_program(db_training.id, db)


def parse_training_program(request):
    training_program = TrainingProgram(
        title = request.title,
        description= request.description,
        training_time = request.training_time,
        is_private = request.is_private,
        user_id = request.user_id,
    )

    programs = []
    for program_id, program in enumerate(request.program_exercises):
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
    return training_program

def parse_sets(existing_sets, sets_data):
    updated_sets = []

    for s_id, s_data in enumerate(sets_data):
        s = Set()
        if s_data.id in existing_sets:
            s = existing_sets.pop(s_data.id)
            s.weight = s_data.weight
            s.reps = s_data.reps
            s.rest_time = s_data.rest_time
        else:
            s = Set(
                order = s_id,
                weight = s_data.weight,
                reps = s_data.reps,
                rest_time = s_data.rest_time,
            )
        updated_sets.append(s)
    
    return updated_sets