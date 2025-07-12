<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMembershipController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Task Routes
Route::prefix('tasks')
    ->middleware('auth:sanctum')
    ->group(function (): void {
        Route::get('/{id}', [TaskController::class, 'getTaskById']);
        Route::get('/project/{projectId}', [TaskController::class, 'getAllTasks']);
        Route::post('', [TaskController::class, 'store']);
        Route::post('/assign', [TaskController::class, 'assignTask']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}/soft', [TaskController::class, 'softDelete']);
        Route::delete('/{id}/hard', [TaskController::class, 'hardDelete']);
    });

// Project Routes
Route::prefix('projects')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/user', [ProjectController::class, 'getAllProjectsForUser']);
        Route::get('/{id}', [ProjectController::class, 'getProjectById']);
        Route::post('', [ProjectController::class, 'store']);
        Route::put('/{id}', [ProjectController::class, 'update']);
    });

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes for Logout
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAllDevices']);
    });
});

// Project Membership Routes
Route::prefix('/projects/memberships')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('', [ProjectMembershipController::class, 'addMemberToProject']);
        Route::get('/{projectId}', [ProjectMembershipController::class, 'getAllProjectMembers']);
    });

Route::middleware('auth:sanctum')
    ->delete('/projects/{projectId}/membership/{userId}', [ProjectMembershipController::class, 'removeMemberFromProject']);

// Test Route
Route::get('/hello', function () {
    return 'Hello, World!';
});
