<?php

namespace App\Http\Controllers;
use App\Models\Room;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $rates = Rate::all();
        $rooms = Room::all();
        $query = Booking::query();

        // Filter by booking ID
        if ($request->filled('booking_id')) {
        $query->where('id', $request->input('booking_id'));
        }

        // Filter by number of people
        if ($request->filled('numberOfPeople')) {
            $guestNumbers = $request->input('numberOfPeople');
            
            $query->where(function ($q) use ($guestNumbers) {
                foreach ($guestNumbers as $guestNumber) {
                    if ($guestNumber == 5) {
                        $q->orWhere('numberOfPeople', '>=', 5);
                    } else {
                        $q->orWhere('numberOfPeople', $guestNumber);
                    }
                }
            });
        }
        
        // Filter by rate name
        if ($request->filled('rate_title')) {
            $query->whereHas('rate', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->rate_title . '%');
            });
        }

        // Filter by room code
        if ($request->filled('room_code')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->room_code . '%');
            });
        }    

        // Sort and paginate result
        $bookings = $query->orderBy('id')->simplePaginate(30);
        return view('bookings.index', compact('bookings', 'rates', 'rooms'));
    }
    
    public function create() {
        $rates = Rate::all();
        return view('bookings.bookingInfo', ['action' => 'create', 'rates' => $rates]);
    }

    public function edit($id) {
        $booking = Booking::findOrFail($id);
        $rates = Rate::all();

        return view('bookings.bookingInfo', ['booking' => $booking, 'rates' => $rates, 'action' => 'edit']);
    }

}
