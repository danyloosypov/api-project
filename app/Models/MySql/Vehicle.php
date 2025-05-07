<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'model',
        'license',
        'people_qty',
        'luggage_qty'
    ];
}
