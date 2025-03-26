<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class FlightFutureSchedule extends Model
{
    protected $fillable = [
        'weekday',
        'flight_number',
        'flight_iata_number',
        'flight_icao_number',
        'airline_name',
        'airline_iata_code',
        'airline_icao_code',
        'aircraft_model_code',
        'aircraft_model_text',
        'departure_iata_code',
        'departure_icao_code',
        'departure_terminal',
        'departure_gate',
        'departure_scheduled_time',
        'arrival_iata_code',
        'arrival_icao_code',
        'arrival_terminal',
        'arrival_gate',
        'arrival_scheduled_time',
        'codeshare_airline_name',
        'codeshare_airline_iata_code',
        'codeshare_airline_icao_code',
        'codeshare_flight_number',
        'codeshare_flight_iata_number',
        'codeshare_flight_icao_number',
    ];
}
