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
use App\Http\Controllers\ItinerariesController;
use App\Http\Controllers\FlightScheduleController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripPricesController;
use App\Http\Controllers\TripDurationController;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 
'destroy'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum', 'check.user.status')->group(function () {
    // Permissions
    Route::apiResource('permissions', PermissionController::class);
    // Roles
    Route::apiResource('roles', RoleController::class);
    // Users
    Route::apiResource('users', UserController::class);
    // Itineraries
    Route::apiResource('itineraries', ItinerariesController::class);
    // Flight Schedules
    Route::apiResource('flight-schedules', FlightScheduleController::class);
    // Trips
    Route::apiResource('trips', TripController::class);
    // Trip Prices
    Route::apiResource('trip-prices', TripPricesController::class);
    // Trip Durations
    Route::apiResource('trip-durations', TripDurationController::class);
    // Customers
    Route::apiResource('customers', CustomersController::class);
    // Hotel Occupancies
    Route::apiResource('hoteloccupancies', HotelOccupanciesController::class);
});
