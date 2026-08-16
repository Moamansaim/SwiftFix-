<?php

use App\Features\Auth\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class,
], function () {

    Route::post('/password/send-code', 'sendPasswordResetCode')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/password/reset', 'resetPassword')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/register', 'register')
        ->middleware('guest.sanctum');

    Route::post('/login', 'login')
        ->middleware('guest.sanctum');

    Route::post('/email/send-verification', 'sendVerificationEmail')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/resend-verification', 'resendVerificationEmail')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    Route::post('/logout', 'logout')
        ->middleware('auth:sanctum');
});
