<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cleaning extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'requestedDateTime',
        'startDateTime',
        'endDateTime',
        'room_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
