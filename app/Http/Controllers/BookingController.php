<?php

namespace App\Http\Controllers;
use App\Models\Room;
use App\Models\Rate;
use App\Models\User;
use App\Models\Commodity;
use App\Models\Cleaning;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Policy;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $rates = Rate::all();
        $rooms = Room::orderBy('code')->get();
        $users = User::where('userType', 'Guest')->orderBy('lastName')->orderBy('firstName')->get();
        $cleaners = User::where('userType', 'Cleaner')->orderBy('lastName')->orderBy('firstName')->get();
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

        //Filter by status
        if ($request->filled('status')) {
            $currentDate = now();
            if ($request->status == 'actives') {
                $query->whereDate('startDate', '<=', $currentDate)
                    ->whereNull('actualEndDate');
            } elseif ($request->status == 'finished') {
                $query->whereDate('actualEndDate', '<', $currentDate);
            }
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

        // Filter by main guest
        if ($request->filled('user_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('id', '=', $request->user_id);
            });
        }    

        // Sort and paginate result
        $bookings = $query->orderBy('id')->simplePaginate(30);
        sleep(2);

        $cleaningWorkingHoursFrom = (Policy::where('description', 'cleaningWorkingHoursFrom')->first())->value;
        $cleaningWorkingHoursTo = (Policy::where('description', 'cleaningWorkingHoursTo')->first())->value;

        return view('bookings.index', compact('bookings', 'rates', 'rooms', 'users', 'cleaners', 'cleaningWorkingHoursFrom', 'cleaningWorkingHoursTo'));
    }
    
    public function create(Request $request)
    {
        $action = $request->input('action', old('action'));
        $startDate = $request->input('startDate', old('startDate'));
        $agreedEndDate = $request->input('agreedEndDate', old('agreedEndDate'));
        $numberOfPeople = $request->input('numberOfPeople', old('numberOfPeople'));
        $rate_id = $request->input('rate_id', old('rate_id'));
        $user_id = $request->input('user_id', old('user_id'));
        $returnDeposit = $request->has('returnDeposit');
        $roomCode = $request->input('roomCode', old('roomCode'));
        $cleanTotalBookingPrice = $request->input('cleanTotalBookingPrice', old('cleanTotalBookingPrice'));

        $rates = Rate::all();
        $users = User::where('userType', 'Guest')
            ->where('status', 1)    
            ->get();

        $startDateCarbon = Carbon::parse($request->input('startDate', old('startDate')));
        $agreedEndDateCarbon = Carbon::parse($request->input('agreedEndDate', old('agreedEndDate')));

        $stayDays = $agreedEndDateCarbon->diffInDays($startDateCarbon);

        //Get Total Price
        if(isset($cleanTotalBookingPrice) && $cleanTotalBookingPrice){
            $totalBookingPrice = 0;
            $breakdown = [];
        }
        else{
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
        }    
        return view('bookings.createBooking', compact('action', 'startDate', 'agreedEndDate', 'numberOfPeople', 'rates', 'users','user_id', 'rate_id', 'returnDeposit', 'roomCode', 'stayDays', 'totalBookingPrice', 'breakdown'), ['action' => 'create']); 
    }

    public function edit($id, Request $request)
    {
        $booking = Booking::findOrFail($id);
        
        // Load related data
        $rates = Rate::all();
        $users = User::where('userType', 'Guest')
            ->where('status', 1)    
            ->get();
    
        // Extract values from the existing booking
        $bookingId = $id;
        $startDate = $booking->startDate;
        $agreedEndDate = $booking->agreedEndDate;
        $numberOfPeople = $booking->numberOfPeople;
        $rate_id = $booking->rate_id;
        $user_id = $booking->user_id;
        $returnDeposit = $booking->returnDeposit;
        $roomCode = $booking->room->code;
        $cleanTotalBookingPrice = (!empty($request->cleanTotalBookingPrice) && $request->cleanTotalBookingPrice == true) ? true : false; // To skip recalculation
    
        $startDateCarbon = Carbon::parse($startDate);
        $agreedEndDateCarbon = Carbon::parse($agreedEndDate);
        $stayDays = $agreedEndDateCarbon->diffInDays($startDateCarbon);
        
        $additionalServices = $booking->additionalServices()->get();
        $additionalServicesSum = 0;
        foreach($additionalServices as $addSer){
            $additionalServicesSum += $addSer->price;
        }

        $additionalCommodities = $booking->commodities()->get();
        $additionalCommoditiesSum = 0;
        foreach($additionalCommodities as $addCom){
            $additionalCommoditiesSum += $addCom->getCurrentPriceAttribute();
        }

        $breakdown = [
            'basePricePerPersonPerDay' => Policy::where('description', 'basePricePerPersonPerDay')->first()->value,
            'basePricePerRatePerDay' => $booking->rate->getCurrentPriceAttribute(),
            'numberOfPeople' => $numberOfPeople,
            'stayDays' => $stayDays,
            'returnDepositValue' => $returnDeposit ? Policy::where('description', 'damageDeposit')->first()->value : 0,
            'additionalServices' => $additionalServicesSum,
            'additionalCommodities' => $additionalCommoditiesSum
        ];
        // Calculation logic (similar to create)
        $totalBookingPrice = $this->calculateBookingTotalPrice($breakdown);
    
        return view('bookings.editBooking', compact(
            'booking', 'startDate', 'agreedEndDate', 'numberOfPeople', 'rates', 'users', 'user_id', 'rate_id', 'returnDeposit', 'roomCode', 'stayDays', 'totalBookingPrice', 'cleanTotalBookingPrice', 'breakdown', 'bookingId'
        ), ['action' => 'edit']);
    }    

    public function selectRoom(Request $request)
    {
        $query = Room::query();
        $bookingId = (!empty($request->bookingId)) ? $request->bookingId : '';
        $action = $request->action;
        $startDate = $request->startDate;
        $agreedEndDate = $request->agreedEndDate;
        $numberOfPeople = $request->numberOfPeople;
        $returnDeposit = $request->returnDeposit;
        $rate_id = $request->rate_id;
        $user_id = $request->user_id;
        if ($request->filled('numberOfPeople')) {
            $query->where('maxOfGuests', '=', $request->numberOfPeople);
        }

        //Filtrar habitaciones disponibles
        if ($request->filled('startDate') && $request->filled('agreedEndDate')) {
            $query->whereDoesntHave('bookings', function($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('startDate', '<', $request->agreedEndDate)
                    ->where('agreedEndDate', '>', $request->startDate);
                });
            });
        }
        $rooms = $query->orderBy('id')->simplePaginate(30);

        return view('bookings.selectRoom', compact('action', 'rooms', 'startDate', 'agreedEndDate', 'numberOfPeople', 'returnDeposit', 'rate_id', 'user_id', 'bookingId'));
    }

    public function store(Request $request)
    {        
        $validatedData = $request->validate([
            'startDate' => 'required|date|after_or_equal:today',
            'agreedEndDate' => 'required|date|after:startDate',
            'numberOfPeople' => 'required|integer|min:1|max:6',
            'rate_id' => 'required|exists:rates,id',
        ]);
    
        $startDate = Carbon::parse($validatedData['startDate']);
        $agreedEndDate = Carbon::parse($validatedData['agreedEndDate']);
    
        if ($agreedEndDate->lte($startDate)) {
            return redirect()->back()->withErrors(['agreedEndDate' => 'La fecha de fin debe ser posterior a la fecha de inicio.'])->withInput($request->input());
        }

        $returnDeposit = ($request->has('returnDeposit')) ? 1 : 0;
        // if user click "Select room"
        if ($request->input('action_type') === 'select_room') {
            $queryParams = http_build_query([
                'action' => 'create',
                'startDate' => $request->input('startDate'),
                'agreedEndDate' => $request->input('agreedEndDate'),
                'numberOfPeople' => $request->input('numberOfPeople'),
                'returnDeposit' => $returnDeposit,
                'rate_id' => $request->input('rate_id'),
                'user_id' => $request->input('user_id')
            ]);
    
            return redirect()->route('bookings.selectRoom', $queryParams)
            ->withInput($request->input());
        }
    
        // If user click "Create" or "Update" booking
        if ($request->input('action_type') === 'save_booking') {
            
            $validatedData = $request->validate([
                'startDate' => 'required|date|after_or_equal:today',
                'agreedEndDate' => 'required|date|after:startDate',
                'numberOfPeople' => 'required|integer|min:1',
                'rate_id' => 'required|exists:rates,id',
                'user_id' => 'required|exists:users,id',
                'room_code' => 'required|string',
            ]);

            $room = Room::where('code',  $validatedData['room_code'])->first();
            $returnDeposit = ($request->has('returnDeposit')) ? 1 : 0;
            
            $booking = Booking::create([
                'startDate' => $validatedData['startDate'],
                'agreedEndDate' => $validatedData['agreedEndDate'],
                'numberOfPeople' => $validatedData['numberOfPeople'],
                'rate_id' => $validatedData['rate_id'],
                'user_id' => $validatedData['user_id'],
                'room_id' => $room->id,
                'returnDeposit' => $returnDeposit,
                'bookingDate' => now()
            ]);
    
            // Redirigir a la vista de detalles de la reserva o cualquier otra página
            return redirect()->route('bookings.index', $booking->id)
                             ->with('success', 'Reserva creada exitosamente.');
        }
    }

    public function update($id, Request $request)
    {        
        $booking = Booking::findOrFail($id);
        $validatedData = $request->validate([
            'startDate' => 'required|date',
            'agreedEndDate' => 'required|date|after_or_equal:startDate',
            'numberOfPeople' => 'required|integer|min:1|max:6',
            'rate_id' => 'required|exists:rates,id',
        ]);
    
        $startDate = Carbon::parse($validatedData['startDate']);
        $agreedEndDate = Carbon::parse($validatedData['agreedEndDate']);
    
        if ($agreedEndDate->lte($startDate)) {
            return redirect()->back()->withErrors(['agreedEndDate' => 'La fecha de fin debe ser posterior a la fecha de inicio.'])->withInput($request->input());
        }
        // if user click "Select room"
        if ($request->input('action_type') === 'select_room') {
            return redirect()->route('bookings.selectRoom', [
                'action' => 'edit',
                'bookingId' => $id,
                'startDate' => $request->input('startDate'),
                'agreedEndDate' => $request->input('agreedEndDate'),
                'numberOfPeople' => $request->input('numberOfPeople'),
                'returnDeposit' => $request->input('returnDeposit'),
                'rate_id' => $request->input('rate_id'),
                'user_id' => $request->input('user_id'),
            ]);
        }
    
        // If user click "Create" or "Update" booking
        if ($request->input('action_type') === 'save_booking') {
            
            $validatedData = $request->validate([
                'startDate' => 'required|date',
                'agreedEndDate' => 'required|date|after_or_equal:startDate',
                'numberOfPeople' => 'required|integer|min:1',
                'rate_id' => 'required|exists:rates,id',
                'user_id' => 'required|exists:users,id',
                'room_code' => 'required|string',
            ]);
            $room = Room::where('code',  $validatedData['room_code'])->first();
            $returnDeposit = ($request->has('returnDeposit')) ? 1 : 0;
            $booking->update([
                'startDate' => $validatedData['startDate'],
                'agreedEndDate' => $validatedData['agreedEndDate'],
                'numberOfPeople' => $validatedData['numberOfPeople'],
                'rate_id' => $validatedData['rate_id'],
                'user_id' => $validatedData['user_id'],
                'room_id' => $room->id,
                'returnDeposit' => $returnDeposit
            ]);
    
            // Redirigir a la vista de detalles de la reserva o cualquier otra página
            return redirect()->route('bookings.index', $booking->id)
                             ->with('success', 'Reserva actualizada exitosamente.');
        }
    }

    public function setBookingAsFinished($id, Request $request) 
    {        
        $booking = Booking::findOrFail($id);

        $room = Room::findOrFail($booking->room_id);

        //Clear existing active cleanings for room
        $cleaningsForRoom = $room->cleanings()
                                ->where('requestedDateTime', '<', Carbon::now())
                                ->whereNull('endDateTime')
                                ->get();
        foreach($cleaningsForRoom as $cleaning){
            $cleaning->endDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $cleaning->save();
        }
        
        $cleaning = Cleaning::create([
            'requestedDateTime' =>  Carbon::now()->format('Y-m-d H:i:s'),
            'room_id' => $booking->room_id
        ]);

        $finishBookingDateTime = Carbon::now()->format('Y-m-d H:i:s');
        $booking->update([
            'actualEndDate' => $finishBookingDateTime,
            'finalPrice' => $request->actualFinalPrice
        ]);
        return redirect()->route('bookings.index', $booking->id)
        ->with('success', 'Reserva finalizada exitosamente.');        
    }

    public function viewExtraCommoditiesForBooking($bookingId)
    {
        // Encuentra el booking o lanza una excepción si no existe
        $booking = Booking::findOrFail($bookingId);
    
        // 1. Comodidades incluidas en la tarifa del booking
        $rateCommodities = $booking->rate->commodities;
    
        // 2. Comodidades adicionales directamente relacionadas al booking
        // Excluir las comodidades ya incluidas en la tarifa
        $bookingCommodities = $booking->commodities->diff($rateCommodities);
    
        // Combinar comodidades activas (rate y adicionales) y asegurarse de que sean únicas
        $activeCommodities = $rateCommodities->merge($bookingCommodities)->unique('id');
    
        // IDs de las comodidades activas
        $activeCommodityIds = $activeCommodities->pluck('id');
    
        // 3. Comodidades contratables (excluyendo las activas) y asegurarse de que sean únicas
        $availableCommodities = Commodity::whereNotIn('id', $activeCommodityIds)->get()->unique('id');
    
        return view('commodities.commoditiesForBooking', [
            'booking' => $booking,
            'rateCommodities' => $rateCommodities->unique('id'),  // Comodidades del rate
            'bookingCommodities' => $bookingCommodities->unique('id'), // Comodidades adicionales
            'availableCommodities' => $availableCommodities       // Comodidades contratables
        ]);
    }
    
    private function calculateBookingTotalPrice($breakdown){
        $basePricePerPersonPerDay = $breakdown['basePricePerPersonPerDay'] ?? 0; 
        $basePricePerRatePerDay = $breakdown['basePricePerRatePerDay'] ?? 0;
        $additionalsCommoditiesPricePerDay = $breakdown['additionalCommodities'] ?? 0;

        $numberOfPeople = $breakdown['numberOfPeople'] ?? 0;
        $stayDays = $breakdown['stayDays'] ?? 0;
        $additionalServices = $breakdown['additionalServices'] ?? 0;
        $returnDepositValue = $breakdown['returnDepositValue'] ?? 0;

        $totalPrice = ($basePricePerPersonPerDay + $basePricePerRatePerDay + $additionalsCommoditiesPricePerDay) * $numberOfPeople * $stayDays + $additionalServices - $returnDepositValue;
        return $totalPrice;
    }

    public function destroy(Request $request){
        $booking = Booking::findOrFail($request->id);
        $booking->delete();
        return to_route('bookings.index');
    }

    public function addCommodity(Request $request){
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'commodity_id' => 'required|exists:commodities,id',
        ]);
    
        // Obtener el Booking y Commodity por sus IDs
        $booking = Booking::findOrFail($request->booking_id);
        $commodity = Commodity::findOrFail($request->commodity_id);
    
        // Agregar la comodidad a la reserva (con la tabla intermedia)
        $booking->commodities()->attach($commodity->id);
        // Confirmación
        return redirect()->route('bookings.viewExtraCommoditiesForBooking', ['id' => $request->booking_id])
                         ->with('success', "Se ha añadido la comodidad \"{$commodity->title}\" correctamente para la reserva #{$request->booking_id}.");
    }

    public function addCommodityToBookingView(Request $request){
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'commodity_id' => 'required|exists:commodities,id',
        ]);
    
        // Obtener el Booking y Commodity por sus IDs
        $booking = Booking::findOrFail($request->booking_id);
        $commodity = Commodity::findOrFail($request->commodity_id);
    
        return view('bookings.addCommodityToBooking', compact('booking', 'commodity')); 
    }

    public function deleteCommodity(Request $request){
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'commodity_id' => 'required|exists:commodities,id',
        ]);
    
        // Obtener el Booking y Commodity por sus IDs
        $booking = Booking::findOrFail($request->booking_id);
        $commodity = Commodity::findOrFail($request->commodity_id);
        $booking->commodities()->detach($request->commodity_id);

        // Confirmación
        return redirect()->route('bookings.viewExtraCommoditiesForBooking', ['id' => $request->booking_id])
                         ->with('success', "Se ha eliminado la comodidad \"{$commodity->title}\" correctamente de la reserva #{$request->booking_id}.");
        }   

    public function showCheckout(Request $request){
        $booking = Booking::findOrFail($request->id);

        $startDateCarbon = Carbon::parse($booking->startDate);
        $agreedEndDateCarbon = Carbon::parse($booking->agreedEndDate);

        $agreedStayDays = $agreedEndDateCarbon->diffInDays($startDateCarbon);

        $today = Carbon::now();
        $actualStayDays =  $today->diffInDays($startDateCarbon);

        $basePricePerPersonPerDayPolicy = Policy::where('description', 'basePricePerPersonPerDay')->first();
        $basePricePerPersonPerDay = 0;
        if ($basePricePerPersonPerDayPolicy) {
            $basePricePerPersonPerDay = $basePricePerPersonPerDayPolicy->value;
        }
        
        $rate = Rate::find($booking->rate->id);
        $basePricePerRatePerDay = $rate ? $rate->getCurrentPriceAttribute() : 0;    
        
        $currentReturnDepositAmount = Policy::where('description', 'damageDeposit')->first();
        $returnDepositValue = (isset($booking->returnDeposit) && $booking->returnDeposit == true) ? $currentReturnDepositAmount->value : 0;
        //Harcoded commodities and additionalServices selected 
        $additionalServices = $booking->additionalServices()->get();
        $additionalServicesSum = 0;
        foreach($additionalServices as $addSer){
            $additionalServicesSum += $addSer->price;
        }

        $additionalCommodities = $booking->commodities()->get();
        $additionalCommoditiesSum = 0;
        foreach($additionalCommodities as $addCom){
            $additionalCommoditiesSum += $addCom->getCurrentPriceAttribute();
        }
        $breakdown = [
            'basePricePerPersonPerDay' => $basePricePerPersonPerDay,
            'basePricePerRatePerDay' => $basePricePerRatePerDay,
            'bookingCommodities' => $additionalCommoditiesSum,
            'bookingAdditionalServices' => $additionalServicesSum,
            'numberOfPeople' => $booking->numberOfPeople,
            'agreedStayDays' => $agreedStayDays,
            'actualStayDays' => $actualStayDays,
            'returnDepositValue' => $returnDepositValue,
        ];

        $totalBookingPrice = $this->calculateBookingTotalPrice($breakdown);

        return view('bookings.showCheckout', compact('booking', 'breakdown', 'additionalServices', 'additionalCommodities')); 
    }
}
