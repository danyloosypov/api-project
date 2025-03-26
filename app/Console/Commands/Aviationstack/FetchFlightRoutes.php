<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\FlightRoute as MongoFlightRoute;
use App\Models\MySql\FlightRoute as MySqlFlightRoute;

class FetchFlightRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flight-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $offset = 0;
        $limit = 100;
        $total = 0;

        do {
            // Fetch data from the API
            $data = AviationstackFacade::fetchFlightRoutesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $route) {
                    // Insert or update into MongoDB
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

                // Update the offset for the next API call
                $offset += $limit;
                $total = $data['pagination']['total'];

                // Output progress information
                $this->info("Fetched {$offset} of {$total} records...");
            } else {
                // No more data to fetch
                break;
            }
        } while ($offset < $total);

        $this->info('Flight Routes data fetch completed successfully.');
    }

}
