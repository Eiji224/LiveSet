<?php

use App\Http\Controllers\Api\TrainingProgramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/training-programs', [TrainingProgramController::class, 'store']);
