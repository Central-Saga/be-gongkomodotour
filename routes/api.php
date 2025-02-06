<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Permissions
    Route::apiResource('permissions', PermissionController::class);
    // Roles
    Route::apiResource('roles', RoleController::class);
    // Users
    Route::apiResource('users', UserController::class);
});
