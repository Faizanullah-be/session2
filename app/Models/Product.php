<?php

namespace App\Models;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'discount_price', 'status'];
    // relations
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    public function scopeInActive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeHasDiscount($query)
    {
        return $query->whereNotNull('discount_price')
            ->where('discount_price', '<', DB::raw('price'));
    }

    public function scopeHasNoDiscount($query)
    {
        return $query->whereNull('discount_price')
            ->orWhere('discount_price', '>=', DB::raw('price'));
    }

    public function scopeInStock($query)
    {
        return $query->whereHas('inventories', function ($query) {
            $query->where('quantity', '>', 0);
        });
    }

    public function scopeOutOfStock($query)
    {
        return $query->whereHas('inventories', function ($query) {
            $query->where('quantity', '<=', 0);
        });
    }


    public function scopeExpired($query)
    {
        return $query->whereHas('inventories', function ($query) {
            $query->where('expiry_date', '<=', now());
        });
    }

    public function scopeNotExpired($query)
    {
        return $query->whereHas('inventories', function ($query) {
            $query->where('expiry_date', '>', now());
        });
    }

    public function scopeWithRatings($query, $stars = 0)
    {
        return $query->whereHas('reviews', function ($query) use ($stars) {
            $query->where('rating', $stars);
        });
    }
    // global scope

    protected static function booted()
    {
        static::addGlobalScope(new ActiveScope);
    }
}
