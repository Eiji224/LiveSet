from typing import Optional
from sqlalchemy import String, Integer, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.models import Base


class Set(Base):
    __tablename__ = "sets"

    order: Mapped[int] = mapped_column(Integer, nullable=False)
    weight: Mapped[int] = mapped_column(Integer, nullable=False)
    reps: Mapped[int] = mapped_column(Integer, nullable=False)
    rest_time: Mapped[int] = mapped_column(Integer, nullable=False)

    program_exercise_id: Mapped[int] = mapped_column(ForeignKey("program_exercises.id", ondelete="CASCADE"))

    program_exercise: Mapped["ProgramExercise"] = relationship(back_populates="sets")