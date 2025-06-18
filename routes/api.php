<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('tasks')->group(function () {
    Route::get('/{id}', [TaskController::class, 'getTaskById']);
    Route::get('', [TaskController::class, 'getAllTasks']);
    Route::post('', [TaskController::class, 'store']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}/soft', [TaskController::class, 'softDelete']);
    Route::delete('/{id}/hard', [TaskController::class, 'hardDelete']);
});


Route::prefix('projects')->group(callback: function () {
    Route::get('/{id}', [ProjectController::class, 'getProjectById']);
    Route::get('', [ProjectController::class, 'getAllProjects']);
    Route::post('', [ProjectController::class, 'store']);
    Route::put('/{id}', [ProjectController::class, 'update']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', action: [AuthController::class, 'register']);
    Route::post('/login', action: [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAllDevices']);
    });
});