<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['booking_id', 'total_price_fnb'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
