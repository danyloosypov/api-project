<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AviationstackService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind('aviationstack', function () {
            return new AviationstackService();
        });
    }
}
