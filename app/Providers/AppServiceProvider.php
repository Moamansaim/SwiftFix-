<?php

namespace App\Providers;

use App\Features\Auth\InterFaces\AuthRepositoryInterFace;
use App\Features\Auth\InterFaces\SendEamilInterFace;
use App\Features\Auth\Repositories\AuthRepository;
use App\Features\Auth\Repositories\SendEamilRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterFace::class, AuthRepository::class);
        $this->app->bind(SendEamilInterFace::class, SendEamilRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}