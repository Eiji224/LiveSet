<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\ProgramExercise;
use App\Models\Set;
use App\Models\TrainingProgram;
use Illuminate\Support\Facades\DB;

class TrainingProgramService
{
    public function parseFromJSON(string $json): TrainingProgram
    {
        $data = json_decode($json, true);

        return DB::transaction(function() use ($data) {
            $title = $data['title'];
            $description = $data['description'];
            $is_private = $data['isPrivate'];
            $trainingTime = $data['trainingTime'];

            $trainingProgram = TrainingProgram::create([
                'title' => $title,
                'description' => $description,
                'is_private' => $is_private,
                'training_time' => $trainingTime,
                'user_id' => auth()->id(),
            ]);

            foreach ($data['programExercises'] as $index => $exercise) {
                $newProgramExercise = $trainingProgram->programExercises()->create([
                    'order' => $index + 1,
                    'exercise_id' => $exercise['exercise_id'],
                ]);

                $sets = array_map(function ($set) {
                    return [
                        'weight_value' => $set['weight'],
                        'rest_time' => $set['restTime'],
                        'reps' => $set['reps'],
                    ];
                }, $exercise['sets']);

                $newProgramExercise->sets()->createMany($sets);
            }

            return $trainingProgram;
        });
    }
}
