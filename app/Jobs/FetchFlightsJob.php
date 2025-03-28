<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\Flight as MongoFlight;
use App\Models\MySql\Flight as MySqlFlight;

class FetchFlightsJob implements ShouldQueue
{
    use Queueable;

    protected $flights;

    /**
     * Create a new job instance.
     *
     * @param array $flights
     */
    public function __construct(array $flights)
    {
        $this->flights = $flights;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->flights as $flightData) {
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
                'flight_codeshared' => is_array($flightData['flight']['codeshared']) ? json_encode($flightData['flight']['codeshared']) : $flightData['flight']['codeshared'],
                // Convert aircraft to JSON or string if it’s an array
                'aircraft' => is_array($flightData['aircraft']) ? json_encode($flightData['aircraft']) : $flightData['aircraft'],
                'live' => is_array($flightData['live']) ? json_encode($flightData['live']) : $flightData['live'],
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
    }
}
