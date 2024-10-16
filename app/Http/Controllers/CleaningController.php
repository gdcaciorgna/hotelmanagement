<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cleaning;
use App\Models\User;
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

        return redirect()->route('bookings.index')
                         ->with('success', "Limpieza creada exitosamente para la habitación #{$room->code}");
    }

    public function finishCleaningAsAdmin(Request $request){
        $roomId = $request->room_id;
        $cleanerId = $request->cleaner_id;

        //Update Cleaning
        $cleaning = Cleaning::where('room_id', $roomId)
            ->orderBy('requestedDateTime', 'desc')
            ->first();

        $cleaning->user_id = $cleanerId;
        $cleaning->startDateTime = now(); 
        $cleaning->endDateTime = now();
        $cleaning->save();

        //Update room status
        $room = Room::findOrFail($roomId);

        return redirect()->route('bookings.index')
                         ->with('success', "Limpieza finalizada exitosamente para la habitación #{$room->code}");
    }
}
