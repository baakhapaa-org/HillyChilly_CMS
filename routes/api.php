<?php

use App\Http\Controllers\Api\AiChatController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RewardController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------
// Public routes
// ---------------------------------------------------------
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login',    [AuthController::class, 'login']);

    // Packages — public browsing
    Route::get('/packages',           [PackageController::class, 'index']);
    Route::get('/packages/{package}', [PackageController::class, 'show']);

    // Flutter-compatible quest endpoint (camelCase format)
    Route::get('/quests',             [PackageController::class, 'quests']);

    // ---------------------------------------------------------
    // Protected routes (Sanctum token required)
    // ---------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::get('/auth/me',      [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Packages — admin mutations
        Route::post('/packages',              [PackageController::class, 'store']);
        Route::put('/packages/{package}',     [PackageController::class, 'update']);
        Route::delete('/packages/{package}',  [PackageController::class, 'destroy']);

        // Bookings
        Route::get('/bookings',              [BookingController::class, 'index']);
        Route::post('/bookings',             [BookingController::class, 'store']);
        Route::get('/bookings/{booking}',    [BookingController::class, 'show']);
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

        // Rewards
        Route::get('/rewards',              [RewardController::class, 'index']);
        Route::get('/rewards/transactions', [RewardController::class, 'transactions']);

        // Profile
        Route::get('/profile',  [ProfileController::class, 'show']);
        Route::put('/profile',  [ProfileController::class, 'update']);

        // AI Chat proxy
        Route::post('/ai/chat', [AiChatController::class, 'chat']);
    });
});
