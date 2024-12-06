<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cleaning;
 use App\Models\User;
use Carbon\Carbon;

class CleaningController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $rooms = Room::orderBy('code')->get();
        $query = Cleaning::query();
    
        $query->whereNull('endDateTime');
        // Filter by booking ID
        if ($request->filled('cleaning_id')) {
            $query->where('id', $request->input('cleaning_id'));
        }

        // Filter by room code
        if ($request->filled('room_code')) {
            $query->whereHas('room', function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->room_code . '%');
            });
        }         
    
        // Sort by room code
        $query->join('rooms', 'cleanings.room_id', '=', 'rooms.id')
            ->orderBy('rooms.code');
    
        $activeCleanings = $query->select('cleanings.*')->simplePaginate(10);
    
        return view('cleanings.index', compact('activeCleanings', 'rooms'));
    }
    
    
    
    public function requestCleaning(Request $request){

        $roomId = $request->room_id;
        $room = Room::findOrFail($roomId);

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
            'room_id' => $roomId
        ]);
        
        return redirect()->route('bookings.index')
                         ->with('success', "Limpieza creada exitosamente para la habitación #{$room->code}.");
    }

    public function finishCleaningAsAdmin(Request $request){
        $roomId = $request->room_id;
        $cleanerId = $request->cleaner_id;

        //Update Cleanings
        $activeCleanings = Cleaning::where('room_id', $roomId)
        ->whereNull('endDateTime')
        ->get();

        // Actualizar cada limpieza activa
        foreach ($activeCleanings as $cleaning) {
            $cleaning->user_id = $cleanerId;
            $cleaning->startDateTime = Carbon::now(); 
            $cleaning->endDateTime = Carbon::now();
            $cleaning->save();
        }

        //Update room status
        $room = Room::findOrFail($roomId);

        return redirect()->route('bookings.index')
                         ->with('success', "Limpieza finalizada exitosamente para la habitación #{$room->code}");
    }

    public function startCleaningAsCleaner($id, Request $request){

        $cleaning = Cleaning::findOrFail($id);
        $cleaning->user_id = auth()->user()->id;
        $cleaning->startDateTime = Carbon::now(); 
        $cleaning->save();

        // Recargar el modelo desde la base de datos para asegurar que los cambios estén actualizados
        $cleaning->refresh();
        sleep(1); // Espera de 1 segundo para permitir que la base de datos procese
                
        return redirect()->route('cleanercleanings.index')
                         ->with('success', "Limpieza iniciada exitosamente para la habitación #{$cleaning->room->code}.");
    }

    public function finishCleaningAsCleaner($id, Request $request){

        $cleaning = Cleaning::findOrFail($id);
        $cleaning->user_id = auth()->user()->id;
        $cleaning->endDateTime = Carbon::now(); 
        $cleaning->save();

        // Recargar el modelo desde la base de datos para asegurar que los cambios estén actualizados
        $cleaning->refresh();
        sleep(1); // Espera de 1 segundo para permitir que la base de datos procese

                
        return redirect()->route('cleanercleanings.index')
                         ->with('success', "Limpieza iniciada exitosamente para la habitación #{$cleaning->room->code}.");
    }

}
