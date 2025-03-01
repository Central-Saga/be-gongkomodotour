<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CabinController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\EmailBlastController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HotelOccupanciesController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EmailBlastRecipientController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GalleryAssetController;
use App\Http\Controllers\AssetController;

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [
    AuthenticatedSessionController::class,
    'destroy'
])->middleware('auth:sanctum');

Route::middleware('auth:sanctum', 'check.user.status')->group(function () {
    // Permissions
    Route::apiResource('permissions', PermissionController::class);
    Route::patch('permissions/{id}/status', [PermissionController::class, 'updateStatus']);
    // Roles
    Route::apiResource('roles', RoleController::class);
    Route::patch('roles/{id}/status', [RoleController::class, 'updateStatus']);
    // Users
    Route::apiResource('users', UserController::class);
    Route::patch('users/{id}/status', [UserController::class, 'updateStatus']);
    // Trips
    Route::apiResource('trips', TripController::class);
    Route::patch('trips/{id}/status', [TripController::class, 'updateStatus']);
    // Customers
    Route::apiResource('customers', CustomersController::class);
    Route::patch('customers/{id}/status', [CustomersController::class, 'updateStatus']);
    // Hotel Occupancies
    Route::apiResource('hoteloccupancies', HotelOccupanciesController::class);
    Route::patch('hoteloccupancies/{id}/status', [HotelOccupanciesController::class, 'updateStatus']);
    // Boats
    Route::apiResource('boat', BoatController::class);
    Route::patch('boat/{id}/status', [BoatController::class, 'updateStatus']);
    // Cabins
    Route::apiResource('cabin', CabinController::class);
    Route::patch('cabin/{id}/status', [CabinController::class, 'updateStatus']);
    // EmailBlast
    Route::apiResource('email_blast', EmailBlastController::class);
    Route::patch('email_blast/{id}/status', [EmailBlastController::class, 'updateStatus']);
    // EmailBlastRecipient
    Route::apiResource('email_blast_recipient', EmailBlastRecipientController::class);
    Route::patch('email_blast_recipient/{id}/status', [EmailBlastRecipientController::class, 'updateStatus']);
    // Subscribers
    Route::apiResource('subscriber', SubscriberController::class);
    // Blog
    Route::apiResource('blog', BlogController::class);

    // Testimonial
    Route::apiResource('testimonial', TestimonialController::class);

    // Faq
    Route::apiResource('faq', FaqController::class);

    // Galleries
    Route::apiResource('galleries', GalleryController::class);
    Route::patch('galleries/{id}/status', [GalleryController::class, 'updateStatus']);
    // Generic Assets
    Route::apiResource('assets', AssetController::class)->except(['index']);
    Route::get('assets', [AssetController::class, 'index']); // Custom index with query parameters
    Route::post('assets/multiple', [AssetController::class, 'storeMultiple']);
    // Bookings
    Route::apiResource('bookings', BookingController::class);
    Route::patch('bookings/{id}/status', [BookingController::class, 'updateStatus']);
    // Transactions
    Route::apiResource('transactions', TransactionController::class);
    Route::patch('transactions/{id}/status', [TransactionController::class, 'updateStatus']);
    // Bank Accounts
    Route::apiResource('bank_accounts', BankAccountController::class);
    Route::patch('bank_accounts/{id}/status', [BankAccountController::class, 'updateStatus']);
});
