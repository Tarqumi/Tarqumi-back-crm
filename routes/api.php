<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\LandingPageController;
use App\Http\Controllers\Api\V1\PermissionsController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are prefixed with /api and versioned as /api/v1/...
| Authentication uses Laravel Sanctum tokens.
|
*/

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1'); // Rate limit: 10 attempts per minute
    
    // Landing Page
    Route::get('/landing/showcase-projects', [LandingPageController::class, 'showcaseProjects']);
    Route::get('/landing/company-info', [LandingPageController::class, 'companyInfo']);
});

// Protected routes (authentication required)
Route::prefix('v1')->middleware(['auth:sanctum', 'update.last.active'])->group(function () {
    // Authentication
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // Permissions
    Route::get('/permissions', [PermissionsController::class, 'index']);

    // Team Management (Admin, Super Admin, HR)
    Route::middleware('role:super_admin,admin,hr')->group(function () {
        Route::get('/team', [TeamController::class, 'index']);
        Route::post('/team', [TeamController::class, 'store']);
        Route::get('/team/departments', [TeamController::class, 'departments']);
        Route::get('/team/{user}', [TeamController::class, 'show']);
        Route::put('/team/{user}', [TeamController::class, 'update']);
        Route::patch('/team/{user}', [TeamController::class, 'update']);
        Route::delete('/team/{user}', [TeamController::class, 'destroy']);
        Route::post('/team/{oldManager}/reassign/{newManager}', [TeamController::class, 'reassign']);
    });

    // Client Management (Admin, Super Admin)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/clients', [ClientController::class, 'index']);
        Route::post('/clients', [ClientController::class, 'store']);
        Route::get('/clients/{client}', [ClientController::class, 'show']);
        Route::put('/clients/{client}', [ClientController::class, 'update']);
        Route::patch('/clients/{client}', [ClientController::class, 'update']);
        Route::delete('/clients/{client}', [ClientController::class, 'destroy']);
        Route::post('/clients/{client}/restore', [ClientController::class, 'restore'])->withTrashed();
    });

    // Project Management (Admin, Super Admin)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::get('/projects/kanban', [ProjectController::class, 'kanban']);
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);
        Route::patch('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
        Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->withTrashed();
    });
});
