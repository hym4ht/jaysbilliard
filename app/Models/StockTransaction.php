<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'menu_id',
        'type',
        'quantity',
        'note',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
