<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class City extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cities';

    protected $fillable = [
        'guid',
        'city_id',
        'iata_code',
        'country_iso2',
        'gmt',
        'city_name',
        'timezone',
        'latitude',
        'longitude',
        'geoname_id',
    ];
}
