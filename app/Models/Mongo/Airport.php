<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Airport extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'airports';

    protected $fillable = [
        'guid',
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
