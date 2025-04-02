<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Transfers\TransferRepositoryInterface;
use App\Repositories\Transfers\TransferRepository;

class TransferServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Bind the interface to the implementation
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
