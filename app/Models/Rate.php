<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function priceHistory()
    {
        return $this->hasMany(RatePricesHistory::class)->latest();
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

}
