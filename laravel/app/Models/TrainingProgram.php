<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TrainingProgram extends Model
{
    protected $fillable = [
        'title',
        'description',
        'training_time',
        'is_private',
    ];

    public function programExercises(): BelongsToMany
    {
        return $this->belongsToMany(ProgramExercise::class);
    }
}
