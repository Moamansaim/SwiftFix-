<?php

namespace App\Providers;

use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Interfaces\SendEmailInterface;
use App\Features\Auth\Repositories\AuthRepository;
use App\Features\Auth\Repositories\SendEmailRepository;
use App\Features\Brand\Models\Brand;
use App\Features\Brand\Policies\BrandPolicy;
use App\Features\CustomerRepairRequests\Interfaces\CustomerRepairRequestInterface;
use App\Features\CustomerRepairRequests\Repositories\CustomerRepairRequestRepository;
use App\Features\ShopOwner\Interfaces\ShopOwnerVerificationsInterface;
use App\Features\ShopOwner\Repositories\ShopOwnerVerificationsRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    }
}