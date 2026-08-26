<?php

use App\Features\Auth\Controllers\AuthController;
use App\Features\City\Controllers\CityController;
use App\Features\Country\Controllers\CountryController;
use App\Features\Districts\Controllers\DistrictsController;
use App\Features\Services\Controllers\ServiceController;
use App\Features\ShopOwner\Controllers\ShopOwnerController;
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
    Route::post('/store/shop-owner-verifications', 'storeShopOwnerVerifications');
    Route::post('/shop-profile/save-or-update', 'saveOrUpdateProfile')
        ->middleware('auth:sanctum');
});


// Route countries
Route::group([
    'prefix' => 'countries',
    'controller' => CountryController::class,
], function () {
    Route::get('/get-all-countries', 'getAllCountries');
});

// Route cities
Route::group([
    'prefix' => 'cities',
    'controller' => CityController::class,
], function () {
    Route::get('/get-all-cities/{id}', 'getAllCities')
        ->middleware('auth:sanctum');
});

// Route districts
Route::group([
    'prefix' => 'districts',
    'controller' => DistrictsController::class,
], function () {
    Route::get('/get-all-districts/{id}', 'getAllDistricts')
        ->middleware('auth:sanctum');
});

// Route services
Route::group([
    'prefix' => 'services',
    'controller' => ServiceController::class,
], function () {
    Route::get('/get-all-services', 'getAllServices');
});