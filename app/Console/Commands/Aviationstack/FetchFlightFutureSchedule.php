<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\FlightFutureSchedule as MongoFlightFutureSchedule;
use App\Models\MySql\FlightFutureSchedule as MySqlFlightFutureSchedule;

class FetchFlightFutureSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flight-future-schedule';

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
            $data = AviationstackFacade::fetchFlightFutureSchedulesData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $flight) {
                    MongoFlightFutureSchedule::create(
                        $flight
                    );

                    MySqlFlightFutureSchedule::create([
                        'weekday' => $flight['weekday'],
                        'flight_number' => $flight['flight']['number'],
                        'flight_iata_number' => $flight['flight']['iataNumber'],
                        'flight_icao_number' => $flight['flight']['icaoNumber'],
                        'airline_name' => $flight['airline']['name'],
                        'airline_iata_code' => $flight['airline']['iataCode'],
                        'airline_icao_code' => $flight['airline']['icaoCode'],
                        'aircraft_model_code' => $flight['aircraft']['modelCode'],
                        'aircraft_model_text' => $flight['aircraft']['modelText'],
                        'departure_iata_code' => $flight['departure']['iataCode'],
                        'departure_icao_code' => $flight['departure']['icaoCode'],
                        'departure_terminal' => $flight['departure']['terminal'],
                        'departure_gate' => $flight['departure']['gate'],
                        'departure_scheduled_time' => $flight['departure']['scheduledTime'],
                        'arrival_iata_code' => $flight['arrival']['iataCode'],
                        'arrival_icao_code' => $flight['arrival']['icaoCode'],
                        'arrival_terminal' => $flight['arrival']['terminal'],
                        'arrival_gate' => $flight['arrival']['gate'],
                        'arrival_scheduled_time' => $flight['arrival']['scheduledTime'],
                        'codeshare_airline_name' => $flight['codeshared']['airline']['name'] ?? null,
                        'codeshare_airline_iata_code' => $flight['codeshared']['airline']['iataCode'] ?? null,
                        'codeshare_airline_icao_code' => $flight['codeshared']['airline']['icaoCode'] ?? null,
                        'codeshare_flight_number' => $flight['codeshared']['flight']['number'] ?? null,
                        'codeshare_flight_iata_number' => $flight['codeshared']['flight']['iataNumber'] ?? null,
                        'codeshare_flight_icao_number' => $flight['codeshared']['flight']['icaoNumber'] ?? null,
                    ]);
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
