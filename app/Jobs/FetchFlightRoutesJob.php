<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\FlightRoute as MongoFlightRoute;
use App\Models\MySql\FlightRoute as MySqlFlightRoute;

class FetchFlightRoutesJob implements ShouldQueue
{
    use Queueable;

    protected $routes;

    /**
     * Create a new job instance.
     *
     * @param array $cities
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Execute the job.
     */
    public function routes(): void
    {
        foreach ($this->cities as $route) {
            MongoFlightRoute::updateOrCreate(
                ['flight.number' => $route['flight']['number']],
                $route
            );

            // Insert or update into MySQL
            MySqlFlightRoute::updateOrCreate(
                ['flight_number' => $route['flight']['number']],
                [
                    'departure_airport' => $route['departure']['airport'],
                    'departure_iata' => $route['departure']['iata'],
                    'departure_icao' => $route['departure']['icao'],
                    'departure_timezone' => $route['departure']['timezone'],
                    'departure_time' => $route['departure']['time'],
                    'departure_terminal' => $route['departure']['terminal'],
                    'arrival_airport' => $route['arrival']['airport'],
                    'arrival_iata' => $route['arrival']['iata'],
                    'arrival_icao' => $route['arrival']['icao'],
                    'arrival_timezone' => $route['arrival']['timezone'],
                    'arrival_time' => $route['arrival']['time'],
                    'arrival_terminal' => $route['arrival']['terminal'],
                    'airline_name' => $route['airline']['name'],
                    'airline_callsign' => $route['airline']['callsign'],
                    'airline_iata' => $route['airline']['iata'],
                    'airline_icao' => $route['airline']['icao'],
                    'flight_number' => $route['flight']['number'],
                ]
            );
        }
    }
}
