<?php

use App\Features\Auth\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1');

Route::post('login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');