<?php

namespace App\Providers;

use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Interfaces\SendEmailInterface;
use App\Features\Auth\Repositories\AuthRepository;
use App\Features\Auth\Repositories\SendEmailRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(SendEmailInterface::class, SendEmailRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
