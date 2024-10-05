<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cleaning;
use Carbon\Carbon;

class CleaningController extends Controller
{
    public function create(Request $request){
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
}
