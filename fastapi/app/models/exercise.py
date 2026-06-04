from typing import Optional
from sqlalchemy import String, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.models import Base


class Exercise(Base):
    __tablename__ = "exercises"

    title: Mapped[str] = mapped_column(String(255), nullable=False)
    body_part: Mapped[str] = mapped_column(String(255), nullable=False)
    
    description: Mapped[Optional[str]] = mapped_column(Text, nullable=True)
    instruction: Mapped[str] = mapped_column(Text, nullable=False)

    user_id: Mapped[int] = mapped_column(index=True, nullable=False)