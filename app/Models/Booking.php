<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes; 

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

    public function getFormattedStartDate()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->startDate)->format('d/m/Y H:i:s');
    }

    public function getFormattedAgreedEndDate()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->agreedEndDate)->format('d/m/Y H:i:s');
    }

    public function getFormattedActualEndDate()
    {
        if(!empty($this->actualEndDate)){
            $endDateFormatted = Carbon::createFromFormat('Y-m-d H:i:s', $this->actualEndDate)->format('d/m/Y H:i:s');
        }
        else
            $endDateFormatted = 'Sin determinar';

        return $endDateFormatted;
    }

    public function getReturnDepositText()
    {
        if ($this->returnDeposit)  
            return 'Sí';
        else return 'No';
    }


}
