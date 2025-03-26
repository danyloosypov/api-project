<?php

namespace App\Console\Commands\Aviationstack;

use Illuminate\Console\Command;
use App\Facades\AviationstackFacade;
use App\Models\Mongo\FlightSchedule as MongoFlightSchedule;
use App\Models\MySql\FlightSchedule as MySqlFlightSchedule;

class FetchFlightSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-flight-schedule';

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
            $data = AviationstackFacade::fetchFlightScheduleData(['offset' => $offset, 'limit' => $limit]);

            // Check if data is not empty
            if (!empty($data['data'])) {
                foreach ($data['data'] as $flight) {
                    // MongoDB: Update or create flight record based on flight number
                    MongoFlightSchedule::updateOrCreate(
                        ['flight.flight_number' => $flight['flight']['number']], // Unique key based on flight number
                        [
                            'airline' => $flight['airline'],
                            'flight' => $flight['flight'],
                            'departure' => $flight['departure'],
                            'arrival' => $flight['arrival'],
                            'status' => $flight['status'],
                            'type' => $flight['type'],
                            'codeshare' => $flight['codeshared'] ?? null,
                        ]
                    );

                    // MySQL: Update or create flight record based on flight number
                    MySqlFlightSchedule::updateOrCreate(
                        ['flight_number' => $flight['flight']['number']], // Unique key based on flight number
                        [
                            'weekday' => $flight['weekday'] ?? null,
                            'flight_iata_number' => $flight['flight']['iataNumber'] ?? null,
                            'flight_icao_number' => $flight['flight']['icaoNumber'] ?? null,
                            'airline_name' => $flight['airline']['name'] ?? null,
                            'airline_iata_code' => $flight['airline']['iataCode'] ?? null,
                            'airline_icao_code' => $flight['airline']['icaoCode'] ?? null,
                            'aircraft_model_code' => $flight['aircraft']['modelCode'] ?? null,
                            'aircraft_model_text' => $flight['aircraft']['modelText'] ?? null,
                            'departure_iata_code' => $flight['departure']['iataCode'] ?? null,
                            'departure_icao_code' => $flight['departure']['icaoCode'] ?? null,
                            'departure_terminal' => $flight['departure']['terminal'] ?? null,
                            'departure_gate' => $flight['departure']['gate'] ?? null,
                            'departure_scheduled_time' => $flight['departure']['scheduledTime'] ?? null,
                            'arrival_iata_code' => $flight['arrival']['iataCode'] ?? null,
                            'arrival_icao_code' => $flight['arrival']['icaoCode'] ?? null,
                            'arrival_terminal' => $flight['arrival']['terminal'] ?? null,
                            'arrival_gate' => $flight['arrival']['gate'] ?? null,
                            'arrival_scheduled_time' => $flight['arrival']['scheduledTime'] ?? null,
                            'codeshare_airline_name' => $flight['codeshared']['airline']['name'] ?? null,
                            'codeshare_airline_iata_code' => $flight['codeshared']['airline']['iataCode'] ?? null,
                            'codeshare_airline_icao_code' => $flight['codeshared']['airline']['icaoCode'] ?? null,
                            'codeshare_flight_number' => $flight['codeshared']['flight']['number'] ?? null,
                            'codeshare_flight_iata_number' => $flight['codeshared']['flight']['iataNumber'] ?? null,
                            'codeshare_flight_icao_number' => $flight['codeshared']['flight']['icaoNumber'] ?? null,
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

        $this->info('Flights data fetch completed successfully.');
    }
}
