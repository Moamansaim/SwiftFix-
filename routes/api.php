<?php

use App\Features\Auth\Controllers\AuthController;
use App\Features\Brand\Controllers\BrandController;
use App\Features\Category\Controllers\CategoryController;
use App\Features\City\Controllers\CityController;
use App\Features\Country\Controllers\CountryController;
use App\Features\DeviceModel\Controllers\DeviceModelController;
use App\Features\Product\Controllers\ProductController;
use App\Features\Services\Controllers\ServiceController;
use App\Features\ShopOwner\Controllers\ShopOwnerController;
use App\Features\ShopProduct\Controllers\ShopProductController;
use Illuminate\Support\Facades\Route;

// Health check
Route::get('/health', function () {
    return response()->json(['success' => true, 'message' => 'SwiftFix API is alive.', 'data' => ['status' => 'ok', 'time' => now()->toDateTimeString()]]);
});

// Auth Routes
Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('/register', 'register')->middleware(['guest.sanctum', 'throttle:5,1']);
    Route::post('/login', 'login')->middleware(['guest.sanctum', 'throttle:5,1']);
    Route::post('/password/send-code', 'sendPasswordResetCode')->middleware(['guest.sanctum', 'throttle:5,1']);
    Route::post('/password/reset', 'resetPassword')->middleware(['guest.sanctum', 'throttle:5,1']);
    Route::post('/email/send-verification', 'sendVerificationEmail')->middleware(['guest.sanctum', 'throttle:5,1']);
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->middleware(['signed'])->name('verification.verify');
    Route::post('/email/resend-verification', 'resendVerificationEmail')->middleware(['guest.sanctum', 'throttle:5,1']);
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::post('/change-password', 'changePassword')->middleware('auth:sanctum');
});

// Shop Owner Routes (Public & Authenticated Owner)
Route::group(['prefix' => 'shop-owner', 'controller' => ShopOwnerController::class], function () {
    Route::post('/store/shop-owner-verifications', 'storeShopOwnerVerifications');
    Route::post('/shop-profile/save-or-update', 'saveOrUpdateProfile')->middleware('auth:sanctum');
    Route::get('/get/shop-profile', 'getShopProfile')->middleware('auth:sanctum');
    Route::patch('{id}/status', 'updateShopStatus')->middleware('auth:sanctum');
});

// Admin Routes (Protected by Role: Admin)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::controller(ShopOwnerController::class)->group(function () {
        // قائمة الطلبات مع الفلترة
        Route::get('/shop-owner-verifications', 'getVerifications');
        // الموافقة (ID بالـ URL)
        Route::patch('/shop-owner/verification/{id}/approve', 'accountCreationApproval');
        // الرفض (ID بالـ URL)
        Route::patch('/shop-owner/verification/{id}/reject', 'accountCreationRefused');
        // الحذف
        Route::delete('/shop-owner/verification/{id}', 'delete');
        // كل بيانات الطلبات
        Route::get('/shop-owner/get-shop-owner-verification-data', 'getShopOwnerVerificationData');
    });

    // ⚠️ معطّل مؤقتاً: AdminReviewController غير موجود في origin/main (تم إبلاغ مؤمن)
});

// Public Resources
Route::group(['prefix' => 'countries', 'controller' => CountryController::class], function () {
    Route::get('/get-all', 'getAllCountries');
    Route::get('/get-for-select', 'getCountriesForSelect');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::group(['prefix' => 'services', 'controller' => ServiceController::class], function () {
    Route::get('/get-all', 'getAllServices');
    Route::get('/get-for-select', 'getServicesForSelect');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Authenticated Resources
Route::group(['prefix' => 'cities', 'controller' => CityController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-cities-by-country/{id}', 'getCitiesByCountry');
    Route::get('/get-all', 'getAllCities');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::group(['prefix' => 'brands', 'controller' => BrandController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all', 'getAllBrands');
    Route::get('/get-for-select', 'getBrandsForSelect');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::group(['prefix' => 'device-models', 'controller' => DeviceModelController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all', 'getAllDeviceModels');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::group(['prefix' => 'categories', 'controller' => CategoryController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all', 'getAllCategories');
    Route::get('/get-for-select', 'getCategoriesForSelect');
    Route::post('/store', 'store');
    Route::get('/show/{id}', 'show');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::group(['prefix' => 'products', 'controller' => ProductController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all', 'getAllProducts');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

Route::group(['prefix' => 'shop-products', 'controller' => ShopProductController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all', 'getAllShopProducts');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// ⚠️ User Features معطّلة مؤقتاً: controllers المفضلة والتقييمات
// غير موجودة في origin/main (تم إبلاغ مؤمن)

// Home
Route::group(['prefix' => 'home', 'controller' => ShopOwnerController::class], function () {
    Route::get('/get-all-shop', 'getAllShop');
});