<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class FlightRoute extends Model
{
    protected $fillable = [
        'departure_airport',
        'departure_iata',
        'departure_icao',
        'departure_timezone',
        'departure_time',
        'departure_terminal',
        'arrival_airport',
        'arrival_iata',
        'arrival_icao',
        'arrival_timezone',
        'arrival_time',
        'arrival_terminal',
        'airline_name',
        'airline_callsign',
        'airline_iata',
        'airline_icao',
        'flight_number',
    ];
}
