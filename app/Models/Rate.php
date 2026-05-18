<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'time_period',
        'start_time',
        'end_time',
        'hourly_rate',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get rate for a specific time
     */
    public static function getRateForTime(string $time): ?self
    {
        return self::where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->first();
    }

    /**
     * Get rate for a time range
     */
    public static function getRateForTimeRange(string $startTime, string $endTime): ?self
    {
        // For simplicity, use the rate at start time
        return self::getRateForTime($startTime);
    }
}
