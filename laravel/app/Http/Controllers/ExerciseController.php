<?php

namespace App\Http\Controllers;

use App\Enums\BodyPart;
use App\Http\Requests\Trainings\ExerciseStoreRequest;
use App\Http\Requests\Trainings\ExerciseUpdateRequest;
use App\Models\Exercise;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bodyParts = BodyPart::values();
        return view('exercises.create', compact('bodyParts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExerciseStoreRequest $request)
    {
        $request->validated();

        $exercise = Exercise::create([
            'title' => $request->title,
            'description' => $request->description,
            'instruction' => $request->instruction,
            'body_part' => $request->body_part
        ]);

        return redirect()->route('exercises.show', $exercise);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        return view('exercises.show', compact('exercise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExerciseUpdateRequest $request, Exercise $exercise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        //
    }
}
