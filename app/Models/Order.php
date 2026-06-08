<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['booking_id', 'order_id', 'total_price_fnb', 'status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
