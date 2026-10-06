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

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'SwiftFix API is alive.',
        'data' => [
            'status' => 'ok',
            'time' => now()->toDateTimeString(),
        ],
    ]);
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Shop Owner Routes
|--------------------------------------------------------------------------
*/

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
        ->middleware([
            'auth:sanctum',
            'permission:الموافقة على طلب تحقق صاحب الورشة',
        ]);

    // Reject shop owner verification
    Route::post('/verification/{id}/reject', 'accountCreationRefused')
        ->middleware([
            'auth:sanctum',
            'permission:رفض طلب تحقق صاحب الورشة',
        ]);

    // Delete shop owner verification
    Route::delete('/verification/{id}/delete', 'delete')
        ->middleware([
            'auth:sanctum',
            'permission:حذف طلب تحقق صاحب الورشة',
        ]);

    // Update shop status
    Route::put('/{id}/status', 'updateShopStatus')
        ->middleware([
            'auth:sanctum',
            'permission:تعديل ملف الورشة',
        ]);

    // Get shop owner verification data
    Route::get('/get-shop-owner-verification-data', 'getShopOwnerVerificationData')
        ->middleware([
            'auth:sanctum',
            'permission:عرض طلبات تحقق أصحاب الورش',
        ]);

    // Verify shop
    Route::post('/{id}/verify', 'verifyShop')
        ->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| Country Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'countries',
    'controller' => CountryController::class,
], function () {

    // Get all countries
    Route::get('/get-all', 'getAllCountries')
        ->middleware([
            'auth:sanctum',
            'permission:عرض الدول',
        ]);

    // Get countries for select
    Route::get('/get-for-select', 'getCountriesForSelect');

    // Create a country
    Route::post('/store', 'store')
        ->middleware([
            'auth:sanctum',
            'permission:إضافة دولة',
        ]);

    // Update a country
    Route::put('/update/{id}', 'update')
        ->middleware([
            'auth:sanctum',
            'permission:تعديل الدولة',
        ]);

    // Delete a country
    Route::delete('/delete/{id}', 'destroy')
        ->middleware([
            'auth:sanctum',
            'permission:حذف الدولة',
        ]);
});

/*
|--------------------------------------------------------------------------
| City Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'cities',
    'controller' => CityController::class,
], function () {

    // Get cities by country
    Route::get('/get-cities-by-country/{id}', 'getCitiesByCountry')
        ->middleware('auth:sanctum');

    // Get all cities
    Route::get('/get-all', 'getAllCities')
        ->middleware([
            'auth:sanctum',
            'permission:عرض المدن',
        ]);

    // Create a city
    Route::post('/store', 'store')
        ->middleware([
            'auth:sanctum',
            'permission:إضافة مدينة',
        ]);

    // Update a city
    Route::put('/update/{id}', 'update')
        ->middleware([
            'auth:sanctum',
            'permission:تعديل المدينة',
        ]);

    // Delete a city
    Route::delete('/delete/{id}', 'destroy')
        ->middleware([
            'auth:sanctum',
            'permission:حذف المدينة',
        ]);
});

/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'services',
    'controller' => ServiceController::class,
], function () {

    // Get all services
    Route::get('/get-all', 'getAllServices')
        ->middleware([
            'auth:sanctum',
            'permission:عرض الخدمات',
        ]);

    // Get services for select
    Route::get('/get-for-select', 'getServicesForSelect');

    // Create a service
    Route::post('/store', 'store')
        ->middleware([
            'auth:sanctum',
            'permission:إضافة خدمة',
        ]);

    // Update a service
    Route::put('/update/{id}', 'update')
        ->middleware([
            'auth:sanctum',
            'permission:تعديل خدمة',
        ]);

    // Delete a service
    Route::delete('/delete/{id}', 'destroy')
        ->middleware([
            'auth:sanctum',
            'permission:حذف خدمة',
        ]);
});

/*
|--------------------------------------------------------------------------
| Brand Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'brands',
    'controller' => BrandController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all brands
    Route::get('/get-all', 'getAllBrands')
        ->middleware('permission:عرض العلامات التجارية');

    // Get brands for select
    Route::get('/get-for-select', 'getBrandsForSelect');

    // Create a brand
    Route::post('/store', 'store')
        ->middleware('permission:إضافة علامة تجارية');

    // Get brand details
    Route::get('/show/{id}', 'show')
        ->middleware('permission:عرض العلامات التجارية');

    // Update a brand
    Route::put('/update/{id}', 'update')
        ->middleware('permission:تعديل علامة تجارية');

    // Delete a brand
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف علامة تجارية');
});

/*
|--------------------------------------------------------------------------
| Device Model Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'device-models',
    'controller' => DeviceModelController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all device models
    Route::get('/get-all', 'getAllDeviceModels')
        ->middleware('permission:عرض الأجهزة');

    // Get device models for select
    Route::get('/for-select', 'getDeviceModelsForSelect');

    // Create a device model
    Route::post('/store', 'store')
        ->middleware('permission:إضافة جهاز');

    // Update a device model
    Route::put('/update/{id}', 'update')
        ->middleware('permission:تعديل جهاز');

    // Delete a device model
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف جهاز');
});

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'categories',
    'controller' => CategoryController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all categories
    Route::get('/get-all', 'getAllCategories')
        ->middleware('permission:عرض الفئات');

    // Get categories for select
    Route::get('/get-for-select', 'getCategoriesForSelect');

    // Create a category
    Route::post('/store', 'store')
        ->middleware('permission:إضافة فئة');

    // Get category details
    Route::get('/show/{id}', 'show')
        ->middleware('permission:عرض الفئات');

    // Update a category
    Route::put('/update/{id}', 'update')
        ->middleware('permission:تعديل الفئة');

    // Delete a category
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف الفئة');
});

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'products',
    'controller' => ProductController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all products
    Route::get('/get-all', 'getAllProducts')
        ->middleware('permission:عرض المنتجات');

    // Create a product
    Route::post('/store', 'store')
        ->middleware('permission:إضافة منتج');

    // Update a product
    Route::put('/update/{id}', 'update')
        ->middleware('permission:تعديل منتج');

    // Delete a product
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف منتج');
});

/*
|--------------------------------------------------------------------------
| Shop Product Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'shop-products',
    'controller' => ShopProductController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all shop products
    Route::get('/get-all', 'getAllShopProducts')
        ->middleware('permission:عرض منتجات الورشة');

    // Create a shop product
    Route::post('/store', 'store')
        ->middleware('permission:إضافة منتج للورشة');

    // Update a shop product
    Route::put('/update/{id}', 'update')
        ->middleware('permission:تعديل منتج الورشة');

    // Delete a shop product
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف منتج الورشة');
});

/*
|--------------------------------------------------------------------------
| Favorite Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Customer Review Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Shop Review Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'shops',
    'controller' => ShopReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get shop reviews
    Route::get('/{shopId}/reviews', 'index');
});

/*
|--------------------------------------------------------------------------
| Shop Owner Review Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'shop-owner/reviews',
    'controller' => ShopOwnerReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get shop owner reviews
    Route::get('/get-all', 'index')
        ->middleware('permission:عرض تقييمات الورشة');

    // Reply to a review
    Route::patch('/reply/{id}', 'reply')
        ->middleware('permission:الرد على التقييم');
});

/*
|--------------------------------------------------------------------------
| Admin Review Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'admin/reviews',
    'controller' => AdminReviewController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Delete a review
    Route::put('/delete/{id}', 'destroy')
        ->middleware('permission:حذف تعليق تقييم');

    // Delete a review
    Route::get('/get-all', 'destroy')
        ->middleware('permission:عرض تقييمات جميع الورش');
});

/*
|--------------------------------------------------------------------------
| Home Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'home',
    'controller' => ShopOwnerController::class,
], function () {

    // Get all shops
    Route::get('/get-all-shop', 'getAllShop');

    // Get shop details
    Route::get('/{id}/shop-details', 'shopDetails')
        ->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| Feature Shop Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'features-shop',
    'controller' => FeatureShopController::class,
    'middleware' => 'auth:sanctum',
], function () {

    Route::get('/get-all', 'getAllFeaturesShop')
        ->middleware('permission:عرض ميزات المتجر');

    Route::post('/store', 'store')
        ->middleware('permission:إضافة ميزة للمتجر');

    Route::put('/update/{id}', 'update')
        ->middleware('permission:تعديل ميزة للمتجر');

    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف ميزة للمتجر');
});

/*
|--------------------------------------------------------------------------
| Contact Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'contact',
    'controller' => ContactController::class,
], function () {

    // Submit a new contact message
    Route::post('/store', 'store');

    // Retrieve all contact messages
    Route::get('/get-all', 'index')
        ->middleware([
            'auth:sanctum',
            'permission:عرض رسائل التواصل',
        ]);

    // Delete a contact message
    Route::delete('/delete/{id}', 'destroy')
        ->middleware([
            'auth:sanctum',
            'permission:حذف رسائل التواصل',
        ]);

    // Reply to a contact message
    Route::post('/reply/{id}', 'reply')
        ->middleware([
            'auth:sanctum',
            'permission:الرد على رسائل التواصل',
        ]);
});

/*
|--------------------------------------------------------------------------
| Admin Shop Owner Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'admin/shop-owners',
    'controller' => AdminShopOwnerController::class,
    'middleware' => ['auth:sanctum'],
], function () {

    // Retrieve all shop owners
    Route::get('/get-all', 'index')
        ->middleware('permission:عرض أصحاب الورش');

    // Freeze a shop owner account and block the shop
    Route::delete('/freeze/{id}', 'freeze')
        ->middleware('permission:تجميد حساب صاحب الورشة');

    // Unfreeze a shop owner account and unblock the shop
    Route::post('/unfreeze/{id}', 'unfreeze')
        ->middleware('permission:فك تجميد حساب صاحب الورشة');

    // Permanently delete the shop owner account and shop
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف حساب صاحب الورشة');
});

/*
|--------------------------------------------------------------------------
| Customer Repair Request Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'repair-requests',
    'controller' => CustomerRepairRequestController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all repair requests
    Route::get('/get-all-data', 'getAllCustomerRepairRequests')
        ->middleware('permission:عرض طلبات الصيانة');

    // Create a new repair request
    Route::post('/store', 'store');

    // Delete a repair request
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف طلبات الصيانة');

    // Approve a repair request
    Route::post('/approve/{id}', 'approve')
        ->middleware('permission:الموافقة على طلبات الصيانة');

    // Reject a repair request
    Route::post('/reject/{id}', 'reject')
        ->middleware('permission:رفض طلبات الصيانة');

    // Get all notifications for the authenticated customer
    Route::get('/notifications', 'notificationsCustomerRepairRequests');

    // Mark a specific customer notification as read
    Route::post('/notifications/{id}/read', 'markAsRead');

    // Mark all unread notifications of the authenticated customer as read
    Route::post('/notifications/read-all', 'markAllAsRead');

    // Mark a customer repair request as completed
    Route::post('/customer-repair-requests/{id}/complete', 'complete');
});

/*
|--------------------------------------------------------------------------
| Search Shop Map Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'shops',
    'controller' => SearchShopMapController::class,
], function () {

    // Search shops by service or spare part
    Route::get('/search', 'search');
});

/*
|--------------------------------------------------------------------------
| Platform Statistics Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'platform',
    'controller' => PlatformController::class,
], function () {

    // Get public platform statistics
    Route::get('/statistics', 'getStatistics');
});

/*
|--------------------------------------------------------------------------
| AI Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'ai',
    'middleware' => 'auth:sanctum',
], function () {

    // Get shop recommendations
    Route::post('/shop-recommendations', ShopRecommendationController::class);
});

/*
|--------------------------------------------------------------------------
| Role Management Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'roles',
    'controller' => RoleController::class,
    'middleware' => 'auth:sanctum',
], function () {

    // Get all roles
    Route::get('/', 'getAllRoles')
        ->middleware('permission:عرض الأدوار');

    // Get roles for select inputs
    Route::get('/select', 'getRolesForSelect')
        ->middleware('permission:عرض الأدوار');

    // Create a new role
    Route::post('/', 'store')
        ->middleware('permission:إضافة دور');

    // Update an existing role
    Route::put('/{id}', 'update')
        ->middleware('permission:تعديل دور');

    // Delete a role
    Route::delete('/{id}', 'destroy')
        ->middleware('permission:حذف دور');

    // Get all permissions for a specific role
    Route::get('/{roleId}/permissions', 'showPermissions')
        ->middleware('permission:عرض صلاحيات الدور');

    // Give a permission to a role
    Route::post('/{roleId}/permissions/{permissionId}', 'givePermission')
        ->middleware('permission:إضافة صلاحية للدور');

    // Revoke a permission from a role
    Route::delete('/{roleId}/permissions/{permissionId}', 'revokePermission')
        ->middleware('permission:إزالة صلاحية من الدور');
});

/*
|--------------------------------------------------------------------------
| Complaint Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'complaints',
    'controller' => ComplaintController::class,
    'middleware' => 'auth:sanctum',
], function () {

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    // Submit a new complaint against a shop
    Route::post('/store', 'store');

    // Get complaints submitted by the authenticated customer
    Route::get('/my-complaints', 'myComplaints');

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    // Get all complaints
    Route::get('/get-all', 'index')
        ->middleware('permission:عرض الشكاوى');

    // Reply to a complaint and send the reply by email
    Route::post('/reply/{id}', 'reply')
        ->middleware('permission:الرد على الشكاوى');

    // Delete a complaint
    Route::delete('/delete/{id}', 'destroy')
        ->middleware('permission:حذف الشكاوى');
});

/*
|--------------------------------------------------------------------------
| User Settings Routes
|--------------------------------------------------------------------------
*/

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

    // Get active account deletion reasons
    Route::get('/get/deletion-reasons', 'getDeletionReasons');

    // Get all account deletion reasons
    Route::get('/get/all-deletion-reasons', 'getAllDeletionReasons');

    // Toggle account deletion reason status
    Route::post('/deletion-reasons/{id}/toggle', 'toggleDeletionReason')
        ->middleware('permission:تفعيل وإلغاء تفعيل أسباب حذف الحساب');
});

/*
|--------------------------------------------------------------------------
| Customer Repair Requests Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'customer/repair-requests',
    'middleware' => 'auth:sanctum',
    'controller' => CustomerRepairRequestController::class,
], function () {

    // Get authenticated customer's repair requests
    Route::get('/', 'getMyRepairRequests');
});