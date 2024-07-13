<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\PasswordResetController;

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('estimations', EstimationController::class);
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