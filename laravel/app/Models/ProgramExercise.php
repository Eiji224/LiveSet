<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProgramExercise extends Model
{
    protected $fillable = [
        'order',
        'training_program_id',
    ];

    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function sets(): BelongsToMany
    {
        return $this->belongsToMany(Set::class);
    }
}
