<?php

use App\Http\Controllers\AuthController;
//use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SportTypeController;

// Auth Route
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/verify-email', [AuthController::class, 'verifyEmail']);

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
