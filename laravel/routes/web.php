<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\IndexPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\LiveSessionController;
use App\Http\Controllers\Auth\GitHubController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexPageController::class)->name('index');

Route::get('/oauth/github/redirect', [GitHubController::class, 'redirect'])->name('oauth.github');
Route::get('/oauth/github/callback', [GitHubController::class, 'callback']);

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
Route::get('/trainings', [TrainingProgramController::class, 'index'])->name('trainings.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('exercises', ExerciseController::class)->except(['index']);
    Route::resource('trainings', TrainingProgramController::class)->except(['index']);

    Route::get('/liveTraining', [LiveSessionController::class, 'store'])->name('liveTrainings.store');
    Route::get('/liveTraining/{uniqueUrl}', [LiveSessionController::class, 'show'])->name('liveTrainings.show');
});

require __DIR__.'/auth.php';
