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

    public function edit($id, Request $request)
    {
        $booking = Booking::findOrFail($id);
        $rates = Rate::all();
    
        if ($request->filled('room_id')) {
            $booking->room_id = $request->room_id;
            $booking->save();
        }
    
        return view('bookings.bookingInfo', ['booking' => $booking, 'rates' => $rates, 'action' => 'edit']);
    }   

    public function selectRoom(Request $request)
    {
        $query = Room::query();
        $startDate = $request->startDate;
        $agreedEndDate = $request->agreedEndDate;
        $numberOfPeople = $request->numberOfPeople;
        if ($request->filled('numberOfPeople')) {
            $query->where('maxOfGuests', '>=', $request->numberOfPeople);
        }

        if ($request->filled('startDate') && $request->filled('agreedEndDate')) {
            $query->whereDoesntHave('bookings', function($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('startDate', '<=', $request->agreedEndDate)
                    ->where('agreedEndDate', '>=', $request->startDate);
                });
            });
        }
        $rooms = $query->orderBy('id')->simplePaginate(30);
        return view('bookings.selectRoom', compact('rooms', 'startDate', 'agreedEndDate', 'numberOfPeople'));
    }
    

    public function store(Request $request)
    {        
        // if user click "Select room"
        if ($request->input('action_type') === 'select_room') {
            $queryParams = http_build_query([
                'startDate' => $request->input('startDate'),
                'agreedEndDate' => $request->input('agreedEndDate'),
                'numberOfPeople' => $request->input('numberOfPeople'),
            ]);
    
            return redirect()->route('bookings.selectRoom', $queryParams)
            ->withInput($request->input());
        }
    
        // If user click "Create" or "Update" booking

    }
    
    

}
