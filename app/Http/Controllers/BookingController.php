<?php

namespace App\Http\Controllers;
use App\Models\Room;
use App\Models\Rate;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Policy;

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
    
    public function create(Request $request)
    {
        $startDate = $request->input('startDate', old('startDate'));
        $agreedEndDate = $request->input('agreedEndDate', old('agreedEndDate'));
        $numberOfPeople = $request->input('numberOfPeople', old('numberOfPeople'));
        $rate_id = $request->input('rate_id', old('rate_id'));
        $returnDeposit = $request->has('returnDeposit');
        $roomCode = $request->input('roomCode', old('roomCode'));

        $rates = Rate::all();

        $startDateCarbon = Carbon::parse($request->input('startDate', old('startDate')));
        $agreedEndDateCarbon = Carbon::parse($request->input('agreedEndDate', old('agreedEndDate')));

        $stayDays = $agreedEndDateCarbon->diffInDays($startDateCarbon);

        //Get Total Price
        $basePricePerPersonPerDayPolicy = Policy::where('description', 'basePricePerPersonPerDay')->first();
        $basePricePerPersonPerDay = 0;
        if ($basePricePerPersonPerDayPolicy) {
            $basePricePerPersonPerDay = $basePricePerPersonPerDayPolicy->value;
        }
        
        $rate = Rate::find($rate_id);
        $basePricePerRatePerDay = $rate ? $rate->getCurrentPriceAttribute() : 0;    
        
        $currentReturnDepositAmount = Policy::where('description', 'damageDeposit')->first();
        $returnDepositValue = (isset($returnDeposit) && $returnDeposit == true) ? $currentReturnDepositAmount->value : 0;

        $breakdown = [
            'basePricePerPersonPerDay' => $basePricePerPersonPerDay,
            'basePricePerRatePerDay' => $basePricePerRatePerDay,
            'numberOfPeople' => $numberOfPeople,
            'stayDays' => $stayDays,
            'returnDepositValue' => $returnDepositValue
        ];

        $totalBookingPrice = $this->calculateBookingTotalPrice($breakdown);
    
        return view('bookings.bookingInfo', compact('startDate', 'agreedEndDate', 'numberOfPeople', 'rates', 'rate_id', 'returnDeposit', 'roomCode', 'stayDays', 'totalBookingPrice', 'breakdown'), ['action' => 'create']); 
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
        $returnDeposit = $request->returnDeposit;
        $rate_id = $request->rate_id;
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

        return view('bookings.selectRoom', compact('rooms', 'startDate', 'agreedEndDate', 'numberOfPeople', 'returnDeposit', 'rate_id'));
    }
    

    public function store(Request $request)
    {        
        // if user click "Select room"
        if ($request->input('action_type') === 'select_room') {
            $queryParams = http_build_query([
                'startDate' => $request->input('startDate'),
                'agreedEndDate' => $request->input('agreedEndDate'),
                'numberOfPeople' => $request->input('numberOfPeople'),
                'returnDeposit' => $request->input('returnDeposit'),
                'rate_id' => $request->input('rate_id'),
            ]);
    
            return redirect()->route('bookings.selectRoom', $queryParams)
            ->withInput($request->input());
        }
    
        // If user click "Create" or "Update" booking

    }

    private function calculateBookingTotalPrice($breakdown){
        $basePricePerPersonPerDay = $breakdown['basePricePerPersonPerDay'] ?? 0; 
        $basePricePerRatePerDay = $breakdown['basePricePerRatePerDay'] ?? 0;
        $additionalsCommoditiesPricePerDay = 0;
        if(isset($breakdown['basePricePerAdditionalsCommoditiesPerDay']) && !empty($breakdown['basePricePerAdditionalsCommoditiesPerDay'] && is_array($breakdown['basePricePerAdditionalsCommoditiesPerDay']))){
            foreach($breakdown['basePricePerAdditionalsCommoditiesPerDay'] as $additionalCommodityPrice){
                $additionalsCommoditiesPricePerDay += $additionalCommodityPrice;
            }
        }
        $numberOfPeople = $breakdown['numberOfPeople'] ?? 0;
        $stayDays = $breakdown['stayDays'] ?? 0;
        $additionalServices = $breakdown['additionalServices'] ?? 0;
        $returnDepositValue = $breakdown['returnDepositValue'] ?? 0;

        $totalPrice = ($basePricePerPersonPerDay + $basePricePerRatePerDay + $additionalsCommoditiesPricePerDay) * $numberOfPeople * $stayDays + $additionalServices - $returnDepositValue;
        return $totalPrice;
    }

}
