<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\PasswordResetController;

Route::middleware(['auth:api'])->group(function () {
    Route::post('/clients', [ClientController::class, 'store']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    Route::post('/estimations', [EstimationController::class, 'store']);
    Route::put('/estimations/{id}', [EstimationController::class, 'update']);
    Route::delete('/estimations/{id}', [EstimationController::class, 'destroy']);

    Route::apiResource('users', UserController::class);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/estimations', [EstimationController::class, 'index']);

Route::post('/login', [AuthController::class, 'login']);


Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset']);