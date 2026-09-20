<?php

use App\Features\Auth\Controllers\AuthController;
use App\Features\Brand\Controllers\BrandController;
use App\Features\Category\Controllers\CategoryController;
use App\Features\City\Controllers\CityController;
use App\Features\Contact\Controllers\ContactController;
use App\Features\Country\Controllers\CountryController;
use App\Features\CustomerRepairRequests\Controllers\CustomerRepairRequestController;
use App\Features\DeviceModel\Controllers\DeviceModelController;
use App\Features\Favorite\Controller\FavoriteController;
use App\Features\FeatureShop\Controllers\FeatureShopController;
use App\Features\Product\Controllers\ProductController;
use App\Features\Review\Controllers\AdminReviewController;
use App\Features\Review\Controllers\CustomerReviewController;
use App\Features\Review\Controllers\ShopOwnerReviewController;
use App\Features\Review\Controllers\ShopReviewController;
use App\Features\Services\Controllers\ServiceController;
use App\Features\ShopOwner\Controllers\AdminShopOwnerController;
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

    // Save or update shop profile
    Route::post('/shop-profile/save-or-update', 'saveOrUpdateProfile')
        ->middleware('auth:sanctum');

    // Get authenticated shop profile
    Route::get('/get/shop-profile', 'getShopProfile')
        ->middleware('auth:sanctum');

    // Update shop status
    Route::put('/{id}/status', 'updateShopStatus')
        ->middleware('auth:sanctum');
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
        // توثيق الورشة
        Route::patch('/shop/{shopId}/verify', 'verifyShop');
    });
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

// Favorite routes
Route::group(['prefix' => 'favorites', 'controller' => FavoriteController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::post('/add', 'addToFavorites');
    Route::get('/get-my-favorites', 'getMyFavorites');
    Route::delete('/remove/{shopId}', 'removeFromFavorites');
});

// Customer review routes
Route::group(['prefix' => 'reviews', 'controller' => CustomerReviewController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::post('/{shopId}/reviews/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Shop review routes
Route::group(['prefix' => 'shops', 'controller' => ShopReviewController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{shopId}/reviews', 'index');
});

// Shop owner review routes
Route::group(['prefix' => 'shop-owner/reviews', 'controller' => ShopOwnerReviewController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all', 'index');
    Route::patch('/reply/{id}', 'reply');
});

// Admin review routes
Route::group(['prefix' => 'admin/reviews', 'controller' => AdminReviewController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::put('/delete/{id}', 'destroy');
});

// Home routes
Route::group(['prefix' => 'home', 'controller' => ShopOwnerController::class], function () {
    Route::get('/get-all-shop', 'getAllShop');
    Route::get('/{id}/shop-details', 'shopDetails')
        ->middleware('auth:sanctum');
});

// Feature Shop routes
Route::group(['prefix' => 'features-shop', 'controller' => FeatureShopController::class], function () {
    Route::get('/get-all', 'getAllFeaturesShop');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Contact routes
Route::group(['prefix' => 'contact', 'controller' => ContactController::class], function () {
    Route::post('/store', 'store');
    Route::get('/get-all', 'index');
    Route::delete('/delete/{id}', 'destroy');
});

// Admin Shop Owner routes
Route::group(['prefix' => 'admin/shop-owners', 'controller' => AdminShopOwnerController::class, 'middleware' => ['auth:sanctum']], function () {
    Route::get('/get-all', 'index');
    Route::delete('/freeze/{id}', 'freeze');
    Route::post('/unfreeze/{id}', 'unfreeze');
    Route::delete('/delete/{id}', 'destroy');
});

// Customer repair request routes
Route::group(['prefix' => 'repair-requests', 'controller' => CustomerRepairRequestController::class, 'middleware' => 'auth:sanctum'], function () {
    Route::get('/get-all-data', 'getAllCustomerRepairRequests');
    Route::post('/store', 'store');
    Route::delete('/delete/{id}', 'destroy');
    Route::post('/approve/{id}', 'approve');
    Route::post('/reject/{id}', 'reject');
});