<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'table_id','customer_name','phone',
        'booking_date','start_time','end_time','total_price','status'
    ];

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    /**
     * Auto-confirm paid bookings that have reached their scheduled start time.
     */
    public static function autoConfirmBookings()
    {
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $todayStr = $now->toDateString();
        $timeStr = $now->toTimeString();

        // Find all paid bookings ('booked') for today or earlier that have reached the start time
        self::where('status', 'booked')
            ->where(function ($query) use ($todayStr, $timeStr) {
                $query->where('booking_date', '<', $todayStr)
                      ->orWhere(function ($q) use ($todayStr, $timeStr) {
                          $q->where('booking_date', $todayStr)
                            ->where('start_time', '<=', $timeStr);
                      });
            })
            ->get()
            ->each(function ($booking) use ($now) {
                $booking->status = 'confirmed';
                // updated_at determines the start of the playing session for countdown timer
                $booking->updated_at = $now;
                $booking->save();
            });
    }
}