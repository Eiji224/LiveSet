from typing import Dict
from json import dumps as json_dumps

from app.models import TrainingProgram
from app.schemas import TrainingProgramSchema


class LiveTraining:
    def __init__(self, training_program: TrainingProgram, host_user_id: int, unique_url: str):
        self.training_program = training_program
        self.host_user_id = host_user_id
        self.unique_url = unique_url
        self.status = 'waiting'
    
    def set_status_waiting(self):
        self.status = 'waiting'

    def set_status_in_progress(self):
        self.status = 'in_progress'

    def set_status_completed(self):
        self.status = 'completed'


class LiveTrainingManager:
    def __init__(self):
        self.active_trainings: Dict[str, LiveTraining] = {}
        self.current_states: Dict[str, TrainingProgramSchema] = {}
    

    def start_training(self, training: TrainingProgram, host_user_id: int, unique_url: str):
        self.active_trainings[unique_url] = LiveTraining(training, host_user_id, unique_url)
        self.current_states[unique_url] = TrainingProgramSchema.model_validate(training)
    
    async def set_training_in_progress(self, unique_url: str):
        from app.services import redis_publish_event

        if unique_url in self.active_trainings:
            live_training = self.active_trainings[unique_url]
            live_training.set_status_in_progress()

            redis_message = {
                'type': 'live_session_in_progress',
                'payload': {
                    'training_program_id': live_training.training_program.id,
                    'host_user_id': live_training.host_user_id,
                    'unique_url': live_training.unique_url,
                    'status': 'in_progress',
                }
            }
            await redis_publish_event(json_dumps(redis_message))

    async def update_state(self, unique_url: str, current_state: TrainingProgramSchema):
        self.current_states[unique_url] = current_state

    async def end_training(self, unique_url: str):
        from app.services import redis_publish_event

        live_training = self.active_trainings[unique_url]
        live_training.set_status_completed()

        del self.active_trainings[unique_url]
        del self.current_states[unique_url]

        redis_message = {
            'type': 'live_session_stopped',
            'payload': {
                'training_program_id': live_training.training_program.id,
                'host_user_id': live_training.host_user_id,
                'unique_url': live_training.unique_url,
                'status': 'completed',
            }
        }
        await redis_publish_event(json_dumps(redis_message))


live_training_manager = LiveTrainingManager()