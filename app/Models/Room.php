<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'code',
        'maxOfGuests',
        'description',
        'image',
    ];

    public function getStatusColor()
    {
        switch ($this->status) {
            case 'Available':
                return 'bg-success';
            case 'Cleaning':
                return 'bg-primary';
            case 'Unavailable':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    public function getStatusFormatted()
    {
        switch ($this->status) {
            case 'Available':
                return 'Disponible';
            case 'Cleaning':
                return 'En limpieza';
            case 'Unavailable':
                return 'Ocupado';
            default:
                return 'Estado desconocido';
        }
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function cleanings()
    {
        return $this->hasMany(Cleaning::class);
    }

    public function getStatusAttribute()
    {
        $roomWithActiveBookings = $this->bookings()
                                        ->whereNull('actualEndDate')
                                        ->where('startDate', '<', now())
                                        ->exists();

        if ($roomWithActiveBookings) {
            return 'Unavailable';
        }

        $roomWithActiveCleanings = $this->cleanings()
                                        ->whereNull('endDateTime')
                                        ->where('startDateTime', '<', now())
                                        ->exists();

        if ($roomWithActiveCleanings) {
            return 'Cleaning';
        }

        return 'Available';
    }
}
