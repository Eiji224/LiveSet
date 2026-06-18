<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class TrainingProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainingPrograms = TrainingProgram::with('user')->paginate(10);

        return view('training.index', compact('trainingPrograms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exercises = Exercise::all();

        return view('training.create', compact('exercises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingProgram $trainingProgram)
    {
        $exercises = Exercise::all();

        return view('training.show', compact('exercises', 'trainingProgram'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingProgram $trainingProgram)
    {
        //
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
