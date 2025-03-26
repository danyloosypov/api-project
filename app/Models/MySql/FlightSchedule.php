<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class FlightSchedule extends Model
{
    protected $fillable = [
        'airline_name',
        'airline_iata_code',
        'airline_icao_code',
        'flight_number',
        'flight_iata_number',
        'flight_icao_number',
        'departure_iata_code',
        'departure_icao_code',
        'departure_scheduled_time',
        'departure_estimated_time',
        'departure_actual_time',
        'departure_gate',
        'departure_delay',
        'departure_baggage',
        'departure_actual_runway',
        'departure_estimated_runway',
        'arrival_iata_code',
        'arrival_icao_code',
        'arrival_scheduled_time',
        'arrival_estimated_time',
        'arrival_actual_time',
        'arrival_gate',
        'arrival_baggage',
        'arrival_actual_runway',
        'arrival_estimated_runway',
        'codeshared',
        'status',
        'type',
    ];
}
