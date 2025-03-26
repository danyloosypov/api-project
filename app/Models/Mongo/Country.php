<?php

namespace App\Models\Mongo;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;


class Country extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'countries';

    protected $fillable = [
        'guid',
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
}
