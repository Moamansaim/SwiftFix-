<?php

use App\Features\Ai\Controllers\ShopRecommendationController;
use App\Features\Auth\Controllers\AuthController;
use App\Features\Brand\Controllers\BrandController;
use App\Features\Category\Controllers\CategoryController;
use App\Features\City\Controllers\CityController;
use App\Features\Complaint\Controllers\ComplaintController;
use App\Features\Contact\Controllers\ContactController;
use App\Features\Country\Controllers\CountryController;
use App\Features\CustomerRepairRequests\Controllers\CustomerRepairRequestController;
use App\Features\DeviceModel\Controllers\DeviceModelController;
use App\Features\Favorite\Controller\FavoriteController;
use App\Features\FeatureShop\Controllers\FeatureShopController;
use App\Features\Home\PlatformController;
use App\Features\Product\Controllers\ProductController;
use App\Features\Review\Controllers\AdminReviewController;
use App\Features\Review\Controllers\CustomerReviewController;
use App\Features\Review\Controllers\ShopOwnerReviewController;
use App\Features\Review\Controllers\ShopReviewController;
use App\Features\Role\Controllers\RoleController;
use App\Features\SearchShopMap\Controllers\SearchShopMapController;
use App\Features\Services\Controllers\ServiceController;
use App\Features\ShopOwner\Controllers\AdminShopOwnerController;
use App\Features\ShopOwner\Controllers\ShopOwnerController;
use App\Features\ShopProduct\Controllers\ShopProductController;
use App\Features\UserSettings\Controllers\UserSettingsController;
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

    // Update shop status
    Route::put('/{id}/status', 'updateShopStatus')
        ->middleware('auth:sanctum');

    // Get shop owner verification data
    Route::get('/get-shop-owner-verification-data', 'getShopOwnerVerificationData')
        ->middleware('auth:sanctum');

    // Get all shop owner verification requests
    Route::post('/{id}/verify',  'verifyShop')
        ->middleware('auth:sanctum');
});
// Country routes
Route::group([
    'prefix' => 'countries',
    'controller' => CountryController::class,

], function () {

    // Get all countries
    Route::get('/get-all', 'getAllCountries')
        ->middleware('auth:sanctum');

    // Get countries for select
    Route::get('/get-for-select', 'getCountriesForSelect');

    // Create a country
    Route::post('/store', 'store')
        ->middleware('auth:sanctum');

    // Update a country
    Route::put('/update/{id}', 'update')
        ->middleware('auth:sanctum');

    // Delete a country
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('auth:sanctum');
});

// City routes
Route::group([
    'prefix' => 'cities',
    'controller' => CityController::class,

], function () {

    // Get cities by country
    Route::get('/get-cities-by-country/{id}', 'getCitiesByCountry')
        ->middleware('auth:sanctum');

    // Get all cities
    Route::get('/get-all', 'getAllCities');

    // Create a city
    Route::post('/store', 'store')
        ->middleware('auth:sanctum');

    // Update a city
    Route::put('/update/{id}', 'update')
        ->middleware('auth:sanctum');

    // Delete a city
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('auth:sanctum');
});

// Service routes
Route::group([
    'prefix' => 'services',
    'controller' => ServiceController::class,
], function () {

    // Get all services
    Route::get('/get-all', 'getAllServices')
        ->middleware('auth:sanctum');

    // Get services for select
    Route::get('/get-for-select', 'getServicesForSelect');

    // Create a service
    Route::post('/store', 'store')
        ->middleware('auth:sanctum');

    // Update a service
    Route::put('/update/{id}', 'update')
        ->middleware('auth:sanctum');

    // Delete a service
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('auth:sanctum');
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

    // Get device models for select
    Route::get('/for-select', 'getDeviceModelsForSelect');

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
    Route::put('/delete/{id}', 'destroy');
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


// Feature Shop routes
Route::group([
    'prefix' => 'features-shop',
    'controller' => FeatureShopController::class,
], function () {
    Route::get('/get-all', 'getAllFeaturesShop');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'destroy');
});

// Contact routes
Route::group([
    'prefix' => 'contact',
    'controller' => ContactController::class,
], function () {

    // Submit a new contact message
    Route::post('/store', 'store');

    // Retrieve all contact messages
    Route::get('/get-all', 'index');

    // Delete a contact message
    Route::delete('/delete/{id}', 'destroy');

    // Reply to a contact message
    Route::post('/reply/{id}', 'reply');
});


// Admin Shop Owner routes
Route::group([
    'prefix' => 'admin/shop-owners',
    'controller' => AdminShopOwnerController::class,
    'middleware' => ['auth:sanctum'],
], function () {

    // Retrieve all shop owners
    Route::get('/get-all', 'index');

    // Freeze a shop owner account and block the shop
    Route::delete('/freeze/{id}', 'freeze');

    // Unfreeze a shop owner account and unblock the shop
    Route::post('/unfreeze/{id}', 'unfreeze');

    // Permanently delete the shop owner account and shop
    Route::delete('/delete/{id}', 'destroy');
});


// Customer repair request routes
Route::group([
    'prefix' => 'repair-requests',
    'controller' => CustomerRepairRequestController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all  repair request
    Route::get('/get-all-data', 'getAllCustomerRepairRequests');

    // Create a new repair request
    Route::post('/store', 'store');

    // Delete a repair request
    Route::delete('/delete/{id}', 'destroy');

    // Approve a repair request
    Route::post('/approve/{id}', 'approve');

    // Reject a repair request
    Route::post('/reject/{id}', 'reject');

    //Get all notifications for the authenticated customer,
    //including read and unread notifications.
    Route::get('/notifications', 'notificationsCustomerRepairRequests');

    // Mark a specific customer notification as read.
    Route::post('/notifications/{id}/read', 'markAsRead');

    // Mark all unread notifications of the authenticated customer as read.
    Route::post('/notifications/read-all', 'markAllAsRead');

    // Mark a customer repair request as completed.
    Route::post('/customer-repair-requests/{id}/complete', 'complete');
});


// Search shop map routes
Route::group([
    'prefix' => 'shops',
    'controller' => SearchShopMapController::class,
], function () {

    // Search shops by service or spare part
    Route::get('/search', 'search');
});



// Platform statistics routes

Route::group([
    'prefix' => 'platform',
    'controller' => PlatformController::class,
], function () {
    // Get public platform statistics
    Route::get('/statistics', 'getStatistics');
});


// Platform statistics routes

Route::group([
    'prefix' => 'ai',
    'middleware' => 'auth:sanctum',
], function () {
    // Get public platform statistics
    Route::post('/shop-recommendations', ShopRecommendationController::class);
});


// Role management routes
Route::group([
    'prefix' => 'roles',
    'controller' => RoleController::class,
], function () {

    // Get all roles
    Route::get('/', 'getAllRoles');

    // Get roles for select inputs
    Route::get('/select', 'getRolesForSelect');

    // Create a new role
    Route::post('/', 'store');

    // Update an existing role
    Route::put('/{id}', 'update');

    // Delete a role
    Route::delete('/{id}', 'destroy');

    // Get all permissions for a specific role
    Route::get('/{roleId}/permissions', 'showPermissions');

    // Give a permission to a role
    Route::post('/{roleId}/permissions/{permissionId}', 'givePermission');

    // Revoke a permission from a role
    Route::delete('/{roleId}/permissions/{permissionId}', 'revokePermission');
});


// Complaint routes
Route::group([
    'prefix' => 'complaints',
    'controller' => ComplaintController::class,
    'middleware' => 'auth:sanctum',
], function () {

    /*
    | Customer
    */

    // Submit a new complaint against a shop.
    Route::post('/store', 'store');

    // Get complaints submitted by the authenticated customer.
    Route::get('/my-complaints', 'myComplaints');

    /*
    | Admin
    */
    // Get all complaints.
    Route::get('/get-all', 'index');

    // Reply to a complaint and send the reply by email.
    Route::post('/reply/{id}', 'reply');

    // Delete a complaint.
    Route::delete('/delete/{id}', 'destroy');
});


Route::group([
    'prefix' => 'user-settings',
    'middleware' => 'auth:sanctum',
    'controller' => UserSettingsController::class,
], function () {

    // Get authenticated user's profile
    Route::get('/profile', 'getProfile');

    // Update authenticated user's profile
    Route::put('/profile', 'updateProfile');

    // Delete authenticated user's account
    Route::delete('/account', 'deleteAccount');

    // Delete authenticated user's account
    Route::get('/get/deletion-reasons', 'getDeletionReasons');

    Route::get('/get/all-deletion-reasons', 'getAllDeletionReasons');

    // Toggle account deletion reason status
    Route::post('/deletion-reasons/{id}/toggle', 'toggleDeletionReason');
});


Route::group([
    'prefix' => 'customer/repair-requests',
    'middleware' => 'auth:sanctum',
    'controller' => CustomerRepairRequestController::class,
], function () {
    Route::get('/', 'getMyRepairRequests');
});