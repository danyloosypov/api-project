<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'country_id',
        'country_name',
        'capital',
        'currency_code',
        'currency_name',
        'fips_code',
        'country_iso2',
        'country_iso3',
        'country_iso_numeric',
        'phone_prefix',
        'continent',
        'population',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'population' => 'integer',
    ];
}
