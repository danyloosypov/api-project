<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class Flight extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'flights';

    protected $fillable = [
        'guid',
        'flight_date',
        'flight_status',
        'departure',
        'arrival',
        'airline',
        'flight',
        'aircraft',
        'live'
    ];

    // Cast nested arrays to JSON or MongoDB object
    protected $casts = [
        'departure' => 'array',
        'arrival' => 'array',
        'airline' => 'array',
        'flight' => 'array',
        'aircraft' => 'array',
        'live' => 'array'
    ];
}
