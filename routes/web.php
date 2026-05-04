<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\TrainingProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('exercises', ExerciseController::class);
Route::resource('trainings', TrainingProgramController::class);
