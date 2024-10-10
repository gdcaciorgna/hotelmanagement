<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cleaning;
use Carbon\Carbon;

class CleaningController extends Controller
{

    public function index($id = null){

        $activeCleanings = Cleaning::query()
        ->whereNull('endDateTime')
        ->orderBy('requestedDateTime')
        ->simplePaginate(10);

        $historyCleanings = Cleaning::query()
        ->whereNotNull('endDateTime')
        ->orderBy('requestedDateTime')
        ->simplePaginate(10);
        return view('cleanings.index', compact('activeCleanings', 'historyCleanings'));
    }
    
    public function requestCleaning(Request $request){
        $roomId = $request->room_id;

        Cleaning::create([
            'requestedDateTime' => now(),
            'room_id' => $roomId
        ]);
        
        $room = Room::findOrFail($roomId);
        $room->status = 'Cleaning';
        $room->save();

        return redirect()->route('bookings.index')
                         ->with('success', "Limpieza creada exitosamente para la habitación #{$room->code}");
    }

    public function finishCleaningAsAdmin(Request $request){
        $roomId = $request->room_id;
        $cleaning = Cleaning::where('room_id', $roomId)
            ->orderBy('requestedDateTime', 'desc')
            ->first();
        
        $room = Room::findOrFail($roomId);

        $roomWithActiveBookings = $room->bookings
                                    ->whereNull('actualEndDate')
                                    ->where('startDate', '<', now());
        
        //If it is an active booking then set room as Unavailable else set as Available
        if($roomWithActiveBookings->count() > 0){
            $room->status = 'Unavailable';
        }
        else{
            $room->status = 'Available';
        }
        $room->save();

        return redirect()->route('bookings.index')
                         ->with('success', "Limpieza finalizada exitosamente para la habitación #{$room->code}");
    }
}
