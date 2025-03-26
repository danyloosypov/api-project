<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class FlightSchedule extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'flight_schedules';

    protected $fillable = [
        'guid',
        'airline',
        'arrival',
        'codeshared',
        'departure',
        'flight',
        'status',
        'type',
    ];

    // Cast nested arrays to JSON or MongoDB object
    protected $casts = [
        'airline' => 'array',
        'arrival' => 'array',
        'codeshared' => 'array',
        'departure' => 'array',
        'flight' => 'array',
    ];
}
