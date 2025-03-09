<?php

namespace App\Http\Controllers;

use App\Models\AdditionalService;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cleaning;
use App\Models\User;
use Carbon\Carbon;

class AdditionalServiceController extends Controller
{
    public function store(Request $request){
        AdditionalService::create(
            [
                'title' => $request['title'],
                'price' => $request['price'],
                'dateTime' => Carbon::now(),
                'booking_id' => $request['booking_id']
            ]
        );

        $priceFormatted = '$ ' . number_format( $request['price'], 2);
        return redirect()->route('bookings.index')
            ->with('success', "El servicio adicional {$request['title']} ({$priceFormatted}) ha sido agregado exitosamente a la reserva #{$request['booking_id']}.");        
    }
}