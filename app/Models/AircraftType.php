<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AircraftType extends Model
{
    protected $fillable = [
        'iata_code',
        'aircraft_name',
        'plane_type_id',
    ];
}
