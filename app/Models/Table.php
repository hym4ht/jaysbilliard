<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'name',
        'type',
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

    /**
     * Get the hourly rate for this table at a specific time
     */
    public function getHourlyRate(string $time): int
    {
        $rate = Rate::getRateForTime($time);
        return $rate ? $rate->hourly_rate : 0;
    }
}

