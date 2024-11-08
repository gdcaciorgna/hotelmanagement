<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalService extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'dateTime',
        'title',
        'price',
        'booking_id'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
