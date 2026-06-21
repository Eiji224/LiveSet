<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\LiveSessionController;
use App\Http\Controllers\Auth\GitHubController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth/github/redirect', [GitHubController::class, 'redirect'])->name('oauth.github');
Route::get('/oauth/github/callback', [GitHubController::class, 'callback']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('exercises', ExerciseController::class);
    Route::resource('trainings', TrainingProgramController::class);

    Route::get('/liveTraining', [LiveSessionController::class, 'store'])->name('liveTrainings.store');
    Route::get('/liveTraining/{uniqueUrl}', [LiveSessionController::class, 'show'])->name('liveTrainings.show');
});

require __DIR__.'/auth.php';
