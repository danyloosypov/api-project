<?php

namespace App\Providers;

use App\Models\MySql\Order;
use App\Models\MySql\Transfer;
use App\Policies\OrderPolicy;
use App\Policies\TransferPolicy;
use Illuminate\Support\ServiceProvider;
use App\Services\AviationstackService;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Transfer::class, TransferPolicy::class);

        Passport::hashClientSecrets();

        $this->app->bind('aviationstack', function () {
            return new AviationstackService();
        });
    }
}
