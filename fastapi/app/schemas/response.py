from pydantic import BaseModel
from typing import Generic, TypeVar, Optional, Any


T = TypeVar('T')


class ApiResponse(BaseModel, Generic[T]):
    status: int = 200
    body: Optional[T] = None
    errors: Optional[Any] = None

    model_config = {
        'from_attributes': True
    }


class RedisMessage(BaseModel):
    type: str
    payload: str


class StatusCreated(BaseModel):
    created: bool = True