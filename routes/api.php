<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\FieldOpeningHoursController;
use App\Http\Controllers\FieldPriceController;
use App\Http\Controllers\LocationServiceController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SportTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\VenueImageController;
use App\Http\Controllers\VenuePaymentController;
use App\Http\Controllers\CourtController;
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
    Route::get('/map', [VenueController::class, 'venueForMap']);
    Route::get('/detail/{venueId}', [VenueController::class, 'getVenueDetail']);
    Route::get('/search_by_id/{venue_id}', [VenueController::class, 'findById']);
    Route::get('/search_near', [VenueController::class, 'searchNearByLatLng']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [VenueController::class, 'store']);
        Route::put('/{venue_id}', [VenueController::class, 'update']);
        Route::delete('/{venue_id}', [VenueController::class, 'delete']);
        Route::patch('/{venue_id}/status', [VenueController::class, 'updateStatus']);

    });
});

// VenueImage routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/venue-images/{venue_id}', [VenueImageController::class, 'getImagesByVenue']);
    Route::post('/venue-images/{venue_id}', [VenueImageController::class, 'store']);
    Route::delete('/venue-images/{image_id}', [VenueImageController::class, 'destroy']);
    Route::put('/venue-images/{image_id}', [VenueImageController::class, 'update']);
});

// Payment routes
Route::get('/paymentReminderEmail', [VenuePaymentController::class, 'paymentReminderEmail']);
Route::get('/unpaidVenueLocking', [VenuePaymentController::class, 'unpaidVenueLocking']);
Route::post('/webhook/handlePayment', [VenuePaymentController::class, 'handle']);
Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
    Route::post('/venuePayment/{venue_id}', [VenuePaymentController::class, 'make']);
    Route::get('/venuePayment', [VenuePaymentController::class, 'getAllVenueByOwnerId']);
});
//Court routes
Route::prefix('court')->group(function () {
    Route::get('/', [CourtController::class, 'index']);
    Route::get('/{court_id}', [CourtController::class, 'findById']);
    Route::post('/getByField/{fieldId}', [FieldController::class, 'getCourtsByFieldAndDate']);
    Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
        Route::post('/', [CourtController::class, 'store']);
        Route::put('/{court_id}', [CourtController::class, 'update']);
        Route::delete('/{court_id}', [CourtController::class, 'delete']);
        Route::post('/createSpecialTimes', [CourtController::class, 'createSpecialTimes']);
    });
});

//Route::prefix('courtslot')->group(function () {
//    Route::get('/', [CourtSlotController::class, 'index']);
//    Route::get('/{slot_id}', [CourtSlotController::class, 'findById']);
//    Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
//        Route::post('/', [CourtSlotController::class, 'store']);
//        Route::put('/{slot_id}', [CourtSlotController::class, 'update']);
//        Route::delete('/{slot_id}', [CourtSlotController::class, 'delete']);
//    });
//});
//Route::prefix('bookingcourt')->group(function () {
//    Route::get('/', [BookingCourtController::class, 'index']);
//    Route::get('/{booking_court_id}', [BookingCourtController::class, 'findById']);
//    Route::post('/', [BookingCourtController::class, 'store']);
//    Route::put('/{booking_court_id}', [BookingCourtController::class, 'update']);
//    Route::delete('/{booking_court_id}', [BookingCourtController::class, 'delete']);
//});
//Field Opening Hours
Route::get('/showByFieldId/{fieldId}', [FieldOpeningHoursController::class, 'showByFieldId']);
Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
    Route::post('/openingHours/test', [FieldOpeningHoursController::class, 'test']);
    Route::post('/openingHours', [FieldOpeningHoursController::class, 'store']);
    Route::put('/openingHours', [FieldOpeningHoursController::class, 'store']);
});

Route::post('/test-log2', function () {
    \Illuminate\Support\Facades\Log::info('Test log');
    return response()->json(['message' => 'OK123123']);
});

//Field Price

Route::get('/fieldPrice/{fieldId}', [FieldPriceController::class, 'get']);
Route::middleware(['auth:sanctum', 'ability:owner'])->group(function () {
    Route::post('/fieldPrice/{fieldId}', [FieldPriceController::class, 'save']);
});

//Booking
Route::prefix('/bookings')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('ability:user')->group(function () {
            Route::post('', [BookingController::class, 'store']);
            Route::get('/{id}/confirm', [BookingController::class, 'confirm']);
            Route::get('/stats', [BookingController::class, 'getUserBookingStats']);
            Route::get('/{bookingId}', [BookingController::class, 'getPaymentQRCode']);
        });
        Route::middleware('ability:owner')->group(function () {
            Route::get('/{id}/complete', [BookingController::class, 'completeBooking']);
            Route::post('/lock', [BookingController::class, 'lock']);
            Route::get('/', [BookingController::class, 'getOwnerBookingStats']);
        });
        Route::middleware('ability:owner,user')->group(function () {
            Route::get('/{id}/cancel', [BookingController::class, 'cancelBooking']);
        });
    });
    Route::post('/isLock', [BookingController::class, 'isLock']);
});

Route::prefix('/admin')->group(function () {
    Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
        Route::get('/users', [AdminController::class, 'getUsers']);
        Route::get('/users/{userId}/upRole', [AdminController::class, 'upRole']);
        Route::get('/users/{userId}', [AdminController::class, 'getVenueByUid']);
        Route::get('/venues/{venueId}/activate', [AdminController::class, 'activateVenue']);
        Route::get('/venues', [AdminController::class, 'getVenues']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
    });
});

Route::get('/map/getByName', [MapController::class, 'getLatLngByName']);
