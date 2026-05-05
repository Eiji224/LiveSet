<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Set extends Model
{
    protected $fillable = [
        'weight_value',
        'rest_time',
        'exercise_id'
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function programExercise(): BelongsTo
    {
        return $this->belongsTo(ProgramExercise::class);
    }
}
