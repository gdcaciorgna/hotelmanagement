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
        'finalPrice',
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
    
    public function additionalServices()
    {
        return $this->hasMany(AdditionalService::class);
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

    public function getCalculatedBookingPrice(): float{
        $basePricePerPersonPerDay = $this->getBasePricePerPersonPerDay();
        $basePricePerRatePerDay = $this->getBasePricePerRatePerDay();
        $returnDepositValue = $this->getReturnDepositValue();
        
        
        $additionalServices = $this->getAdditionalServicesPrice();
        $additionalCommoditiesPricePerDay = $this->getAdditionalCommoditiesPrice();
        $totalPrice = ($basePricePerPersonPerDay + $basePricePerRatePerDay + $additionalCommoditiesPricePerDay) * $this->numberOfPeople * $this->getStayDays() + $additionalServices - $returnDepositValue;
        return $totalPrice;
    }

    public function getStayDays(){
        $startDateCarbon = Carbon::parse($this->startDate);
        $agreedEndDateCarbon = Carbon::parse($this->agreedEndDate);
        return $agreedEndDateCarbon->diffInDays($startDateCarbon);
    }

    public function getBasePricePerPersonPerDay(){
        return Policy::where('description', 'basePricePerPersonPerDay')->first()->value;
    }

    public function getBasePricePerRatePerDay(){
        $rate = Rate::find($this->rate_id);
        return $rate ? $rate->getCurrentPriceAttribute() : 0;    
    }  
    
    public function getReturnDepositValue(){
        $currentReturnDepositAmount = Policy::where('description', 'damageDeposit')->first();
        return (isset($this->returnDeposit) && $this->returnDeposit == true) ? $currentReturnDepositAmount->value : 0;
    }

    public function getAdditionalServicesPrice(){
        $additionalServices = $this->additionalServices()->get();
        $sum = 0;
        foreach($additionalServices as $addSer){
            $sum += $addSer->price;
        }

        return $sum;
    }

    public function getAdditionalCommoditiesPrice(){
        $additionalCommodities = $this->commodities()->get();
        $additionalCommoditiesSum = 0;
        foreach($additionalCommodities as $addCom){
            $additionalCommoditiesSum += $addCom->getCurrentPriceAttribute();
        }
        return $additionalCommoditiesSum;
    }

}
