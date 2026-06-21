from typing import Optional, List
from sqlalchemy import String, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.models import Base


class Exercise(Base):
    __tablename__ = "exercises"

    title: Mapped[str] = mapped_column(String(255), nullable=False)
    body_part: Mapped[str] = mapped_column(String(255), nullable=False)
    description: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    instruction: Mapped[str] = mapped_column(Text, nullable=False)

    program_exercises: Mapped[List["ProgramExercise"]] = relationship(back_populates="exercise")