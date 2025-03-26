<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\Flight as MongoFlight;
use App\Models\MySql\Flight as MySqlFlight;

class FetchFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flights';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch flights data from Aviationstack API and store or update in MongoDB and MySQL';

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
            $data = AviationstackFacade::fetchFlightsData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $flightData) {
                    // MongoDB: Create or update the flight in MongoDB
                    MongoFlight::updateOrCreate(
                        [
                            'flight.flight_iata' => $flightData['flight']['iata'],
                            'flight_date' => $flightData['flight_date']
                        ],
                        $flightData
                    );

                    // MySQL: Prepare the MySQL data mapping
                    $mysqlFlightData = [
                        'flight_date' => $flightData['flight_date'],
                        'flight_status' => $flightData['flight_status'],
                        'departure_airport' => $flightData['departure']['airport'],
                        'departure_iata' => $flightData['departure']['iata'],
                        'departure_icao' => $flightData['departure']['icao'],
                        'departure_terminal' => $flightData['departure']['terminal'],
                        'departure_gate' => $flightData['departure']['gate'],
                        'departure_delay' => $flightData['departure']['delay'],
                        'departure_scheduled' => $flightData['departure']['scheduled'],
                        'departure_estimated' => $flightData['departure']['estimated'],
                        'departure_actual' => $flightData['departure']['actual'],
                        'departure_estimated_runway' => $flightData['departure']['estimated_runway'],
                        'departure_actual_runway' => $flightData['departure']['actual_runway'],
                        'arrival_airport' => $flightData['arrival']['airport'],
                        'arrival_iata' => $flightData['arrival']['iata'],
                        'arrival_icao' => $flightData['arrival']['icao'],
                        'arrival_terminal' => $flightData['arrival']['terminal'],
                        'arrival_gate' => $flightData['arrival']['gate'],
                        'arrival_baggage' => $flightData['arrival']['baggage'],
                        'arrival_delay' => $flightData['arrival']['delay'],
                        'arrival_scheduled' => $flightData['arrival']['scheduled'],
                        'arrival_estimated' => $flightData['arrival']['estimated'],
                        'arrival_actual' => $flightData['arrival']['actual'],
                        'arrival_estimated_runway' => $flightData['arrival']['estimated_runway'],
                        'arrival_actual_runway' => $flightData['arrival']['actual_runway'],
                        'airline_name' => $flightData['airline']['name'],
                        'airline_iata' => $flightData['airline']['iata'],
                        'airline_icao' => $flightData['airline']['icao'],
                        'flight_number' => $flightData['flight']['number'],
                        'flight_iata' => $flightData['flight']['iata'],
                        'flight_icao' => $flightData['flight']['icao'],
                        'flight_codeshared' => $flightData['flight']['codeshared'],
                        'aircraft' => $flightData['aircraft'],
                        'live' => $flightData['live'],
                    ];

                    // MySQL: Create or update the flight in MySQL
                    MySqlFlight::updateOrCreate(
                        [
                            'flight_iata' => $flightData['flight']['iata'],
                            'flight_date' => $flightData['flight_date']
                        ],
                        $mysqlFlightData
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

        $this->info('Flights data fetch completed successfully.');
    }
}
