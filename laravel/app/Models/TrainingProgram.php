<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingProgram extends Model
{
    protected $fillable = [
        'title',
        'description',
        'training_time',
        'is_private',
        'user_id',
    ];

    public function programExercises(): HasMany
    {
        return $this->hasMany(ProgramExercise::class, 'training_id');
    }
}
