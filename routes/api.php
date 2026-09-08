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

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'SwiftFix API is alive.',
        'data' => ['status' => 'ok', 'time' => now()->toDateTimeString()],
    ]);
});

// Authentication routes
Route::group([
    'prefix' => 'auth',
    'controller' => AuthController::class,
], function () {

    // Register a new user
    Route::post('/register', 'register')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    // Login user
    Route::post('/login', 'login')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    // Send password reset code
    Route::post('/password/send-code', 'sendPasswordResetCode')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    // Reset user password
    Route::post('/password/reset', 'resetPassword')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    // Send email verification
    Route::post('/email/send-verification', 'sendVerificationEmail')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    // Verify user email
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
        ->middleware(['signed'])
        ->name('verification.verify');

    // Resend email verification
    Route::post('/email/resend-verification', 'resendVerificationEmail')
        ->middleware(['guest.sanctum', 'throttle:5,1']);

    // Logout user
    Route::post('/logout', 'logout')
        ->middleware('auth:sanctum');

    // Change user password
    Route::post('/change-password', 'changePassword')
        ->middleware('auth:sanctum');
});

// Shop owner routes
Route::group([
    'prefix' => 'shop-owner',
    'controller' => ShopOwnerController::class,
], function () {

    // Submit shop owner verification request
    Route::post('/store/shop-owner-verifications', 'storeShopOwnerVerifications');

    // Save or update shop profile
    Route::post('/shop-profile/save-or-update', 'saveOrUpdateProfile')
        ->middleware('auth:sanctum');

    // Get authenticated shop profile
    Route::get('/get/shop-profile', 'getShopProfile')
        ->middleware('auth:sanctum');

    // Approve shop owner verification
    Route::post('/verification/{id}/approve', 'accountCreationApproval')
        ->middleware('auth:sanctum');

    // Reject shop owner verification
    Route::post('/verification/{id}/reject', 'accountCreationRefused')
        ->middleware('auth:sanctum');

    // Delete shop owner verification
    Route::delete('/verification/{id}/delete', 'delete')
        ->middleware('auth:sanctum');

    Route::put('/{id}/status', 'updateShopStatus')
        ->middleware('auth:sanctum');

    Route::get('/get-shop-owner-verification-data', 'getShopOwnerVerificationData')
        ->middleware('auth:sanctum');
});

// Country routes
Route::group([
    'prefix' => 'countries',
    'controller' => CountryController::class,
], function () {

    // Get all countries
    Route::get('/get-all', 'getAllCountries');

    // Get countries for select
    Route::get('/get-for-select', 'getCountriesForSelect');

    // Create a country
    Route::post('/store', 'store');

    // Update a country
    Route::put('/update/{id}', 'update');

    // Delete a country
    Route::delete('/delete/{id}', 'destroy');
});

// City routes
Route::group([
    'prefix' => 'cities',
    'controller' => CityController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get cities by country
    Route::get('/get-cities-by-country/{id}', 'getCitiesByCountry');

    // Get all cities
    Route::get('/get-all', 'getAllCities');

    // Create a city
    Route::post('/store', 'store');

    // Update a city
    Route::put('/update/{id}', 'update');

    // Delete a city
    Route::delete('/delete/{id}', 'destroy');
});

// Service routes
Route::group([
    'prefix' => 'services',
    'controller' => ServiceController::class,
], function () {

    // Get all services
    Route::get('/get-all', 'getAllServices');

    // Get services for select
    Route::get('/get-for-select', 'getServicesForSelect');

    // Create a service
    Route::post('/store', 'store');

    // Update a service
    Route::put('/update/{id}', 'update');

    // Delete a service
    Route::delete('/delete/{id}', 'destroy');
});

// Brand routes
Route::group([
    'prefix' => 'brands',
    'controller' => BrandController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all brands
    Route::get('/get-all', 'getAllBrands');

    // Get brands for select
    Route::get('/get-for-select', 'getBrandsForSelect');

    // Create a brand
    Route::post('/store', 'store');

    // Get brand details
    Route::get('/show/{id}', 'show');

    // Update a brand
    Route::put('/update/{id}', 'update');

    // Delete a brand
    Route::delete('/delete/{id}', 'destroy');
});

// Device model routes
Route::group([
    'prefix' => 'device-models',
    'controller' => DeviceModelController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all device models
    Route::get('/get-all', 'getAllDeviceModels');

    // Create a device model
    Route::post('/store', 'store');

    // Update a device model
    Route::put('/update/{id}', 'update');

    // Delete a device model
    Route::delete('/delete/{id}', 'destroy');
});

// Category routes
Route::group([
    'prefix' => 'categories',
    'controller' => CategoryController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all categories
    Route::get('/get-all', 'getAllCategories');

    // Get categories for select
    Route::get('/get-for-select', 'getCategoriesForSelect');

    // Create a category
    Route::post('/store', 'store');

    // Get category details
    Route::get('/show/{id}', 'show');

    // Update a category
    Route::put('/update/{id}', 'update');

    // Delete a category
    Route::delete('/delete/{id}', 'destroy');
});

// Product routes
Route::group([
    'prefix' => 'products',
    'controller' => ProductController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all products
    Route::get('/get-all', 'getAllProducts');

    // Create a product
    Route::post('/store', 'store');

    // Update a product
    Route::put('/update/{id}', 'update');

    // Delete a product
    Route::delete('/delete/{id}', 'destroy');
});

// Shop product routes
Route::group([
    'prefix' => 'shop-products',
    'controller' => ShopProductController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all shop products
    Route::get('/get-all', 'getAllShopProducts');

    // Create a shop product
    Route::post('/store', 'store');

    // Update a shop product
    Route::put('/update/{id}', 'update');

    // Delete a shop product
    Route::delete('/delete/{id}', 'destroy');
});

// Favorite routes
Route::group([
    'prefix' => 'favorites',
    'controller' => FavoriteController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Add shop to favorites
    Route::post('/add', 'addToFavorites');

    // Get user's favorites
    Route::get('/get-my-favorites', 'getMyFavorites');

    // Remove shop from favorites
    Route::delete('/remove/{shopId}', 'removeFromFavorites');
});

// Customer review routes
Route::group([
    'prefix' => 'reviews',
    'controller' => CustomerReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Create a shop review
    Route::post('/{shopId}/reviews/store', 'store');

    // Update a review
    Route::put('/update/{id}', 'update');

    // Delete a review
    Route::delete('/delete/{id}', 'destroy');
});

// Shop review routes
Route::group([
    'prefix' => 'shops',
    'controller' => ShopReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get shop reviews
    Route::get('/{shopId}/reviews', 'index');
});

// Shop owner review routes
Route::group([
    'prefix' => 'shop-owner/reviews',
    'controller' => ShopOwnerReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get shop owner reviews
    Route::get('/get-all', 'index');

    // Reply to a review
    Route::patch('/reply/{id}', 'reply');
});

// Admin review routes
Route::group([
    'prefix' => 'admin/reviews',
    'controller' => AdminReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Delete a review
    Route::delete('/delete/{id}', 'destroy');
});

// Home routes
Route::group([
    'prefix' => 'home',
    'controller' => ShopOwnerController::class,
], function () {

    // Get all shops
    Route::get('/get-all-shop', 'getAllShop');
    Route::get('/{id}/shop-details', 'shopDetails')
        ->middleware('auth:sanctum');
});