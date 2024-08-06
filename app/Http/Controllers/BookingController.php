<?php

namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index(){
        $bookings = Booking::query()
        ->orderBy('id')
        ->simplePaginate(30);
        
        return view('bookings.index')->with('bookings', $bookings);
    }

}
