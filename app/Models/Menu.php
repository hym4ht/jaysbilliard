<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'category',
        'description',
        'status',
        'image',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    /**
     * Check if menu item is in stock
     */
    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduce stock
     */
    public function reduceStock(int $quantity): bool
    {
        if (!$this->isInStock($quantity)) {
            return false;
        }

        $this->decrement('stock', $quantity);
        
        // Auto set status to unavailable if stock is 0
        if ($this->fresh()->stock <= 0) {
            $this->update(['status' => 'unavailable']);
        }

        return true;
    }

    /**
     * Add stock
     */
    public function addStock(int $quantity): void
    {
        $this->increment('stock', $quantity);
        
        // Auto set status to available if stock > 0
        if ($this->fresh()->stock > 0 && $this->status === 'unavailable') {
            $this->update(['status' => 'available']);
        }
    }
}
