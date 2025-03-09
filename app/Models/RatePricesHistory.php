<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatePricesHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate_id',
        'price',
        'created_at'
    ];

    public $timestamps = false;
}
