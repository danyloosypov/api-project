<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Flights\FlightRepositoryInterface;
use App\Repositories\Flights\FlightMongoRepositoryInterface;
use App\Repositories\Flights\FlightRepository;
use App\Repositories\Flights\FlightRepositoryMongo;

class FlightServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Bind the interface to the implementation
        $this->app->bind(FlightRepositoryInterface::class, FlightRepository::class);
        $this->app->bind(FlightMongoRepositoryInterface::class, FlightRepositoryMongo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
