<?php

use App\Features\Auth\Controllers\AuthController;
use App\Features\ShopOwner\Controllers\ShopOwnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'SwiftFix API is alive.',
        'data' => ['status' => 'ok', 'time' => now()->toDateTimeString()],
    ]);
});

// Route Auth Users
Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class,
], function () {

    Route::post('/register', 'register')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/login', 'login')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/password/send-code', 'sendPasswordResetCode')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/password/reset', 'resetPassword')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/email/send-verification', 'sendVerificationEmail')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/email/resend-verification', 'resendVerificationEmail')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/logout', 'logout')
        ->middleware('auth:sanctum');
});


// Route ShopOwner
Route::group([
    'prefix' => 'shop-owner',
    'controller' => ShopOwnerController::class,
], function () {

    Route::post('/store', 'store');
    Route::get('/get-all-countrys', 'getAllCountries');
    Route::get('/get-all-services', 'getAllServices');
    Route::get('/get-all-city/{id}', 'getAllCity');
    Route::get('/get-all-district/{id}', 'getAllDistrict');
});