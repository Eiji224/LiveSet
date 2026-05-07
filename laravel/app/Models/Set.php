<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Set extends Model
{
    protected $fillable = [
        'weight_value',
        'rest_time',
        'program_exercise_id'
    ];

    public function programExercise(): HasOne
    {
        return $this->hasOne(ProgramExercise::class);
    }
}
