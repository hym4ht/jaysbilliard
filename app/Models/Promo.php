<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = ['title','badge','description','image','cta_text','cta_url','is_active'];
    
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
}