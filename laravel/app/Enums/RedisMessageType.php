<?php

namespace App\Enums;

enum RedisMessageType: string
{
    case EXERCISE_CREATED = 'exercise_created';
    case EXERCISE_UPDATED = 'exercise_updated';
    case EXERCISE_DELETED = 'exercise_deleted';

    case TRAINING_PROGRAM_CREATED = 'training_program_created';
    case TRAINING_PROGRAM_UPDATED = 'training_program_updated';
    case TRAINING_PROGRAM_DELETED = 'training_program_deleted';

    case LIVE_SESSION_STARTED = 'live_session_started';
    case LIVE_SESSION_IN_PROGRESS = 'live_session_in_progress';
    case LIVE_SESSION_PAUSED = 'live_session_paused';
    case LIVE_SESSION_STOPPED = 'live_session_stopped';
}
