<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'driver_id',
        'luggage',
        'people_qty',
        'flight_num',
        'pickup',
        'unload',
        'gate',
        'destination',
        'status',
        'comment',
    ];

    /**
     * Get the driver associated with the transfer.
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
