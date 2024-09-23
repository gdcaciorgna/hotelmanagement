<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commodity extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'title',
        'description',
        'currentPrice'
    ];
    public function priceHistory()
    {
        return $this->hasMany(CommodityPriceHistory::class)->latest();
    }

    public function getCurrentPriceAttribute()
    {
        $latestPrice = $this->priceHistory()->first();
        return $latestPrice ? $latestPrice->price : null;
    }

    public function updateCurrentPrice($newPrice)
    {
        $this->priceHistory()->create([
            'price' => $newPrice,
        ]);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_commodity');
    }

    public function rates()
    {
        return $this->belongsToMany(Rate::class, 'rate_commodity');
    }

}
