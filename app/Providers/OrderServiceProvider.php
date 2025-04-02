<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Orders\OrderRepositoryInterface;
use App\Repositories\Orders\OrderRepository;

class OrderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Bind the interface to the implementation
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
