<?php

namespace App\Models\MySql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use Notifiable;

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
        'id_transfers'
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

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'id_transfers');
    }

    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

}
