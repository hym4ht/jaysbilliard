<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'table_id','customer_name','phone',
        'booking_date','start_time','end_time','total_price','status','payment_method'
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
}
