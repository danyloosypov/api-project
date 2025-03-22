<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airplane extends Model
{
    protected $fillable = [
        'iata_type',
        'airplane_id',
        'airline_iata_code',
        'iata_code_long',
        'iata_code_short',
        'airline_icao_code',
        'construction_number',
        'delivery_date',
        'engines_count',
        'engines_type',
        'first_flight_date',
        'icao_code_hex',
        'line_number',
        'model_code',
        'registration_number',
        'test_registration_number',
        'plane_age',
        'plane_class',
        'model_name',
        'plane_owner',
        'plane_series',
        'plane_status',
        'production_line',
        'registration_date',
        'rollout_date',
    ];
}
