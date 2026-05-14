<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'description',
    ];
}
