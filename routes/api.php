<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\HotelOccupanciesController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BoatController;
use App\Http\Controllers\CabinController;
use App\Http\Controllers\EmailBlastController;
use App\Http\Controllers\EmailBlastRecipientController;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 
'destroy'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Permissions
    Route::apiResource('permissions', PermissionController::class);
    // Roles
    Route::apiResource('roles', RoleController::class);
    // Users
    Route::apiResource('users', UserController::class);
    // Customers
    Route::apiResource('customers', CustomersController::class);
    // Hotel Occupancies
    Route::apiResource('hoteloccupancies', HotelOccupanciesController::class);
    // Boats
    Route::apiResource('boat', BoatController::class);
    // Cabins
    Route::apiResource('cabin', CabinController::class);

    // EmailBlast
    Route::apiResource('email_blast', EmailBlastController::class);
    
    // EmailBlastRecipient
    Route::apiResource('email_blast_recipient', EmailBlastRecipientController::class);


});
