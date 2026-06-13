<?php

namespace App\Http\Controllers;

use App\Enums\BodyPart;
use App\Http\Requests\Trainings\ExerciseStoreRequest;
use App\Http\Requests\Trainings\ExerciseUpdateRequest;
use App\Models\Exercise;
use Illuminate\Support\Facades\Redis;

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

        $redisMessage = [
            'type' => 'exercise_created',
            'payload' => $exercise,
        ];
        Redis::publish('liveset-events', json_encode($redisMessage));

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
        $bodyParts = BodyPart::values();
        return view('exercises.update', compact('exercise', 'bodyParts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExerciseUpdateRequest $request, Exercise $exercise)
    {
        $validated = $request->validated();
        $exercise->update([
            'title' => $request->title,
            'description' => $request->description,
            'instruction' => $request->instruction,
            'body_part' => $request->body_part
        ]);

        $redis_message = [
            'type' => 'exercise_updated',
            'payload' => $exercise,
        ];
        Redis::publish('liveset-events', json_encode($redis_message));

        return redirect()->route('exercises.show', $exercise);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        $redis_message = [
            'type' => 'exercise_deleted',
            'payload' => [
                'id' => $exercise->id,
            ],
        ];
        Redis::publish('liveset-events', json_encode($redis_message));

        $exercise->delete();
        return redirect()->route('exercises.index');
    }
}
