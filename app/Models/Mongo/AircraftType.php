<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class AircraftType extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'aircraft_types';

    protected $fillable = [
        'guid',
        'iata_code',
        'aircraft_name',
        'plane_type_id'
    ];
}
