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

    Route::post('/password/verify-email', 'passwordVerifyEmail')
        ->middleware('guest.sanctum');

    Route::post('/password/rest', 'restPassword')
        ->middleware('guest.sanctum');

    Route::post('/register', 'register')
        ->middleware('guest.sanctum');

    Route::post('/login', 'login')
        ->middleware('guest.sanctum');

    Route::post('/logout', 'logout')
        ->middleware('auth:sanctum');
});