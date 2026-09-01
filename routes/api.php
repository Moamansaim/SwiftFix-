<?php

use App\Features\Auth\Controllers\AuthController;
use App\Features\Brand\Controllers\BrandController;
use App\Features\Category\Controllers\CategoryController;
use App\Features\City\Controllers\CityController;
use App\Features\Country\Controllers\CountryController;
use App\Features\DeviceModel\Controllers\DeviceModelController;
use App\Features\Favorite\Controller\FavoriteController;
use App\Features\Product\Controllers\ProductController;
use App\Features\Review\Controllers\AdminReviewController;
use App\Features\Review\Controllers\CustomerReviewController;
use App\Features\Review\Controllers\ShopOwnerReviewController;
use App\Features\Review\Controllers\ShopReviewController;
use App\Features\Services\Controllers\ServiceController;
use App\Features\ShopOwner\Controllers\ShopOwnerController;
use App\Features\ShopProduct\Controllers\ShopProductController;
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

    Route::post('/change-password', 'changePassword')
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
    Route::get('/get/shop-profile', 'getShopProfile')
        ->middleware('auth:sanctum');
});

// Route countries
Route::group([
    'prefix' => 'countries',
    'controller' => CountryController::class,
], function () {
    Route::get('/get-all', 'getAllCountries');
    Route::get('/get-for-select', 'getCountriesForSelect');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Routes cities
Route::group([
    'prefix' => 'cities',
    'controller' => CityController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/get-cities-by-country/{id}', 'getCitiesByCountry');
    Route::get('/get-all', 'getAllCities');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Route services
Route::group([
    'prefix' => 'services',
    'controller' => ServiceController::class,
], function () {
    Route::get('/get-all', 'getAllServices');
    Route::get('/get-for-select', 'getServicesForSelect');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Route Brands
Route::group([
    'prefix' => 'brands',
    'controller' => BrandController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::get('/get-all', 'getAllBrands');
    Route::get('/get-for-select', 'getBrandsForSelect');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Route Device Models
Route::group([
    'prefix' => 'device-models',
    'controller' => DeviceModelController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::get('/get-all', 'getAllDeviceModels');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Route Categories
Route::group([
    'prefix' => 'categories',
    'controller' => CategoryController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::get('/get-all', 'getAllCategories');
    Route::get('/get-for-select', 'getCategoriesForSelect');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Route Products
Route::group([
    'prefix' => 'products',
    'controller' => ProductController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::get('/get-all', 'getAllProducts');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Route Shop Products
Route::group([
    'prefix' => 'shop-products',
    'controller' => ShopProductController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::get('/get-all', 'getAllShopProducts');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});



// Route favorites
Route::group([
    'prefix' => 'favorites',
    'controller' => FavoriteController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::post('/add/{shopId}', 'addToFavorites');
    Route::get('/get-my-favorites', 'getMyFavorites');
    Route::delete('/remove/{shopId}', 'removeFromFavorites');
});


// Route customer  review
Route::group([
    'prefix' => 'reviews',
    'controller' => CustomerReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::post('/{shopId}/reviews/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});


// Route  get shop  review
Route::group([
    'prefix' => 'shops',
    'controller' => ShopReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/{shopId}/reviews', 'index');
});

// Route  shop owner  review
Route::group([
    'prefix' => 'shop-owner/reviews',
    'controller' => ShopOwnerReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/get-all', 'index');
    Route::patch('/reply/{id}', 'reply');
});

// Route  admin review
Route::group([
    'prefix' => 'admin/reviews',
    'controller' => AdminReviewController::class,
    'middleware' =>  'auth:sanctum',
], function () {
    Route::delete('/delete/{id}', 'destroy');
});


// Route  admin review
Route::group([
    'prefix' => 'home',
    'controller' => ShopOwnerController::class,
], function () {
    Route::get('/get-all-shop', 'getAllShop');
});