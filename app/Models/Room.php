<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
            case 'Cleaning requested':
                return 'bg-primary';
            case 'Cleaning in process':
                return 'bg-warning';
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
            case 'Unavailable':
                return 'Ocupada';
            case 'Cleaning in process':
                return 'En proceso de limpieza';
            case 'Cleaning requested':
                return 'Limpieza solicitada';
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

        $roomWithActiveCleanings = $this->cleanings()
                                        ->whereNull('endDateTime')
                                        ->whereNotNull('startDateTime')
                                        ->where('startDateTime', '<', Carbon::now()->format('Y-m-d H:i:s'))
                                        ->exists();

        if ($roomWithActiveCleanings) {
            return 'Cleaning in process';
        }

        $roomWithRequestedCleanings = $this->cleanings()
        ->whereNull('endDateTime')
        ->where('requestedDateTime', '<', Carbon::now()->format('Y-m-d H:i:s'))
        ->exists();

        if ($roomWithRequestedCleanings) {
            return 'Cleaning requested';
        }

        $roomWithActiveBookings = $this->bookings()
                                        ->whereNull('actualEndDate')
                                        ->where('startDate', '<', Carbon::now()->format('Y-m-d H:i:s'))
                                        ->exists();
        if ($roomWithActiveBookings) {
            return 'Unavailable';
        }
      
        return 'Available';
    }
}
