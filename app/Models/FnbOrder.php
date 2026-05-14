<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FnbOrder extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'subtotal',
        'tax',
        'total',
        'status',
        'payment_method',
        'items',
        'midtrans_payload',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'midtrans_payload' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
