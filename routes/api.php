<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\LocationServiceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SportTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\VenueImageController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/verifyEmail', [AuthController::class, 'verifyEmail']);

// User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
});

// SportType routes

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
    Route::middleware(['auth:sanctum', 'ability:user,admin'])->group(function () {
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

// Venue routes
Route::prefix('venues')->group(function () {
    Route::get('/', [VenueController::class, 'index']);
    Route::get('/search_by_id/{venue_id}', [VenueController::class, 'findById']);
    // Route::get('/search_by_name', [VenueController::class, 'searchByName']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [VenueController::class, 'store']);
        Route::put('/{venue_id}', [VenueController::class, 'update']);
        Route::delete('/{venue_id}', [VenueController::class, 'delete']);
        Route::patch('/{venue_id}/status', [VenueController::class, 'updateStatus']);

    });
});

//VenueImage
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/venue-images/{venue_id}', [VenueImageController::class, 'getImagesByVenue']);
    Route::post('/venue-images/{venue_id}', [VenueImageController::class, 'store']);
    Route::delete('/venue-images/{image_id}', [VenueImageController::class, 'destroy']);
    Route::put('/venue-images/{image_id}/thumbnail', [VenueImageController::class, 'updateThumbnail']);
});

