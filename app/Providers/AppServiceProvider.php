<?php

namespace App\Providers;

use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Interfaces\SendEmailInterface;
use App\Features\Auth\Repositories\AuthRepository;
use App\Features\Auth\Repositories\SendEmailRepository;
use App\Features\Brand\Models\Brand;
use App\Features\Brand\Policies\BrandPolicy;
use App\Features\Category\Models\Category;
use App\Features\Category\Policies\CategoryPolicy;
use App\Features\City\Models\City;
use App\Features\City\Policies\CityPolicy;
use App\Features\Complaint\Models\Complaint;
use App\Features\Complaint\Policies\ComplaintPolicy;
use App\Features\Contact\Models\ContactMessage;
use App\Features\Contact\Policies\ContactMessagePolicy;
use App\Features\CustomerRepairRequests\Interfaces\CustomerRepairRequestInterface;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use App\Features\CustomerRepairRequests\Policies\CustomerRepairRequestPolicy;
use App\Features\CustomerRepairRequests\Repositories\CustomerRepairRequestRepository;
use App\Features\DeviceModel\Models\DeviceModel;
use App\Features\DeviceModel\Policies\DeviceModelPolicy;
use App\Features\FeatureShop\Models\FeatureShop;
use App\Features\FeatureShop\Policies\FeatureShopPolicy;
use App\Features\Product\Models\Product;
use App\Features\Product\Policies\ProductPolicy;
use App\Features\Review\Models\Review;
use App\Features\Review\Policies\ReviewPolicy;
use App\Features\Role\Policies\RolePolicy;
use App\Features\Services\Models\Service;
use App\Features\Services\Policies\ServicePolicy;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use App\Features\ShopOwner\Policies\ShopOwnerVerificationPolicy;
use App\Features\ShopOwner\Repositories\ShopOwnerVerificationsRepository;
use App\Features\ShopProduct\Models\ShopProduct;
use App\Features\ShopProduct\Policies\ShopProductPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );

        $this->app->bind(
            SendEmailInterface::class,
            SendEmailRepository::class
        );

        $this->app->bind(
            ShopOwnerVerificationsInterface::class,
            ShopOwnerVerificationsRepository::class
        );

        $this->app->bind(
            CustomerRepairRequestInterface::class,
            CustomerRepairRequestRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(City::class, CityPolicy::class);
        Gate::policy(CustomerRepairRequest::class, CustomerRepairRequestPolicy::class);
        Gate::policy(ShopOwnerVerification::class, ShopOwnerVerificationPolicy::class);
        Gate::policy(Complaint::class, ComplaintPolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
        Gate::policy(DeviceModel::class, DeviceModelPolicy::class);
        Gate::policy(FeatureShop::class, FeatureShopPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(ShopProduct::class, ShopProductPolicy::class);
        
    }
}