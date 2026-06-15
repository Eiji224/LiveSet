from typing import Optional, List
from app.models import Base
from sqlalchemy import String, Text, Integer, Boolean
from sqlalchemy.orm import Mapped, mapped_column, relationship


class TrainingProgram(Base):
    __tablename__ = "training_programs"

    title: Mapped[str] = mapped_column(String(255), nullable=False)
    description: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    training_time: Mapped[int] = mapped_column(Integer, nullable=False)
    is_private: Mapped[bool] = mapped_column(Boolean, nullable=False)
    user_id: Mapped[int] = mapped_column(Integer, nullable=False)

    program_exercises: Mapped[List["ProgramExercise"]] = relationship(back_populates="training_program", cascade="all, delete-orphan")