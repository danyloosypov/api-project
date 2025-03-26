<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'people_qty',
        'order_date',
        'flight_num',
        'status',
        'phone',
        'email',
        'luggage_qty',
        'total',
        'arrival_date',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'order_date' => 'datetime',
        'arrival_date' => 'datetime',
    ];
}
