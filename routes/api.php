<?php

use App\Http\Controllers\AuthController;
//use App\Http\Controllers\ProductController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenuePaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SportTypeController;
use App\Http\Controllers\LocationServiceController;
use App\Http\Controllers\VenueController;

// Auth Route
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/verifyEmail', [AuthController::class, 'verifyEmail']);

// User Route
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
});

// Products Route

/*
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::middleware(['auth:sanctum', 'ability:admin,owner'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

*/

Route::get("/sportTypes", [SportTypeController::class, 'index']);
Route::get("/sportTypes/{id}", [SportTypeController::class, 'findById']);
Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    Route::post('/sportTypes', [SportTypeController::class, 'store']);
    Route::put('/sportTypes/{id}', [SportTypeController::class, 'update']);
    Route::delete('/sportTypes/{id}', [SportTypeController::class, 'delete']);
});

// LocationService routes
Route::prefix('locationServices')->group(function () {
    Route::get('/getByVenueId/{id}', [LocationServiceController::class, 'index']);
    Route::get('/{id}', [LocationServiceController::class, 'findById']);
    Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
        Route::post('/', [LocationServiceController::class, 'store']);
        Route::put('/{id}', [LocationServiceController::class, 'update']);
        Route::delete('/{id}', [LocationServiceController::class, 'delete']);
    });
});

// Review routes
Route::prefix('reviews')->group(function () {
    Route::get('/getByVenueId/{id}', [ReviewController::class, 'index']);
    Route::get('/{id}', [ReviewController::class, 'findById']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [ReviewController::class, 'store']);
        Route::delete('/{id}', [ReviewController::class, 'delete']);
    });
});

// Field routes
Route::prefix('fields')->group(function () {
    Route::get('/getByVenueId/{id}', [FieldController::class, 'index']);
    Route::get('/{id}', [FieldController::class, 'findById']);
    Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
        Route::post('/', [FieldController::class, 'store']);
        Route::put('/{id}', [FieldController::class, 'update']);
        Route::delete('/{id}', [FieldController::class, 'delete']);
    });
});


//Venue route
Route::prefix('venues')->group(function () {
    Route::get('/', [VenueController::class, 'index']);
    Route::get('/{id}', [VenueController::class, 'findById']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [VenueController::class, 'store']);
        Route::put('/{id}', [VenueController::class, 'update']);
        Route::delete('/{id}', [VenueController::class, 'delete']);
    });
});

Route::get('/paymentRemiderEmail/IAMTDFHAHAHAHAHAHAHAH', [VenuePaymentController::class, 'paymentRemiderEmail']);
Route::get('/unpaidVenueLocking/IAMTDFHAHAHAHAHAHAHAH', [VenuePaymentController::class, 'unpaidVenueLocking']);

Route::post('/webhook/IAMTDFHAHAHAHAHAHAHAH', [VenuePaymentController::class, 'handle']);

Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
    Route::post('/venuePayment/{venue_id}', [VenuePaymentController::class, 'make']);
    Route::get('/venuePayment', [VenuePaymentController::class, 'getAllVenueByOwnerId']);
});
