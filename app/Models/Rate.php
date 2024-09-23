<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'title',
        'description',
    ];

    public function priceHistory()
    {
        return $this->hasMany(RatePricesHistory::class)->latest();
    }

    public function additionalServices()
    {
        return $this->hasMany(AdditionalService::class);
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

    public function commodities()
    {
        return $this->belongsToMany(Commodity::class, 'commodity_rate');
    }
}
