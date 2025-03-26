<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'flight_date',
        'flight_status',
        'departure_airport',
        'departure_iata',
        'departure_icao',
        'departure_terminal',
        'departure_gate',
        'departure_delay',
        'departure_scheduled',
        'departure_estimated',
        'departure_actual',
        'departure_estimated_runway',
        'departure_actual_runway',
        'arrival_airport',
        'arrival_iata',
        'arrival_icao',
        'arrival_terminal',
        'arrival_gate',
        'arrival_baggage',
        'arrival_delay',
        'arrival_scheduled',
        'arrival_estimated',
        'arrival_actual',
        'arrival_estimated_runway',
        'arrival_actual_runway',
        'airline_name',
        'airline_iata',
        'airline_icao',
        'flight_number',
        'flight_iata',
        'flight_icao',
        'flight_codeshared',
        'aircraft',
        'live',
    ];
}
