from typing import List
from app.models import Base

from sqlalchemy import String, Integer, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship


class ProgramExercise(Base):
    __tablename__ = "program_exercises"

    title: Mapped[str] = mapped_column(String(255), nullable=False)
    order: Mapped[int] = mapped_column(Integer, nullable=False)

    training_program_id: Mapped[int] = mapped_column(ForeignKey("training_programs.id", ondelete="CASCADE"))
    exercise_id: Mapped[int] = mapped_column(ForeignKey("exercises.id", ondelete="CASCADE"))

    training_program: Mapped["TrainingProgram"] = relationship(back_populates="program_exercises")
    exercise: Mapped["Exercise"] = relationship(back_populates="program_exercises")
    sets: Mapped[List["Set"]] = relationship(back_populates="program_exercise", cascade="all, delete-orphan")