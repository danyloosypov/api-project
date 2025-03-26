<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = [
        'fleet_average_age',
        'airline_id',
        'callsign',
        'hub_code',
        'iata_code',
        'icao_code',
        'country_iso2',
        'date_founded',
        'iata_prefix_accounting',
        'airline_name',
        'country_name',
        'fleet_size',
        'status',
        'type',
    ];
}
