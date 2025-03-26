<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class FlightRoute extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'flight_routes';

    protected $fillable = [
        'guid',
        'departure',
        'arrival',
        'airline',
        'flight',
    ];

    // Cast nested arrays to arrays
    protected $casts = [
        'departure' => 'array',
        'arrival' => 'array',
        'airline' => 'array',
        'flight' => 'array',
    ];
}
