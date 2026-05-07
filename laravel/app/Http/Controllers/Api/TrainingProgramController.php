<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgram;
use App\Services\TrainingProgramService;
use Illuminate\Http\Request;

class TrainingProgramController extends Controller
{
    protected TrainingProgramService $trainingProgramService;


    public function __construct(TrainingProgramService $trainingProgramService)
    {
        $this->trainingProgramService = $trainingProgramService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'programExercises' => 'required|array',
            'programExercises.*.exercise_id' => 'required|exists:exercises,id',
            'programExercises.*.sets' => 'required|array',
        ]);

        $program = $this->trainingProgramService->parseFromJSON($request->getContent());

        return response()->json([
            'message' => 'Training program created.',
            'program_id' => $program->id,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingProgram $trainingProgram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrainingProgram $trainingProgram)
    {
        //
    }
}
