<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = [
        'gmt',
        'airport_id',
        'iata_code',
        'city_iata_code',
        'icao_code',
        'country_iso2',
        'geoname_id',
        'latitude',
        'longitude',
        'airport_name',
        'country_name',
        'phone_number',
        'timezone',
    ];
}
