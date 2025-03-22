<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'geoname_id' => 'integer',
    ];
}
