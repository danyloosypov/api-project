<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Mongo\FlightFutureSchedule as MongoFlightFutureSchedule;
use App\Models\MySql\FlightFutureSchedule as MySqlFlightFutureSchedule;

class FetchFlightFutureScheduleJob implements ShouldQueue
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
        foreach ($this->flights as $flight) {
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
    }
}
