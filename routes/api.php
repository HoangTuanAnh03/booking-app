<?php

use App\Http\Controllers\AuthController;
//use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SportTypeController;
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
