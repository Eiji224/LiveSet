<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveSession extends Model
{
    protected $fillable = [
        'unique_url',
        'training_program_id',
        'host_user_id',
        'status'
    ];

    public function training_program(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    public function hostUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }
}
