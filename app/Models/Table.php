<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'capacity',
        'description',
        'status',
        'image',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

