<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookingDate',
        'startDate',
        'agreedEndDate',
        'actualEndDate',
        'numberOfPeople',
        'returnDeposit',
        'rate_id',
        'room_id',
        'user_id',
    ];

    public function rate()
    {
        return $this->belongsTo(Rate::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function commodities()
    {
        return $this->belongsToMany(Commodity::class, 'booking_commodity');
    }

}
