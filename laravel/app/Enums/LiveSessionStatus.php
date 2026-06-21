<?php

namespace App\Enums;

enum LiveSessionStatus: string
{
    case waiting = 'waiting';
    case in_progress = 'in_progress';
    case completed = 'completed';
}
