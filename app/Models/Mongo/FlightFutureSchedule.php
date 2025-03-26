<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class FlightFutureSchedule extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'flight_future_schedules';

    protected $fillable = [
        'guid',
        'weekday',
        'departure',
        'arrival',
        'aircraft',
        'airline',
        'flight',
        'codeshared',
    ];

    // Cast nested arrays to JSON or MongoDB object
    protected $casts = [
        'departure' => 'array',
        'arrival' => 'array',
        'aircraft' => 'array',
        'airline' => 'array',
        'flight' => 'array',
        'codeshared' => 'array',
    ];
}
