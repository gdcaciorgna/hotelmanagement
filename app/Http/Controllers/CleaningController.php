<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Cleaning;
 use App\Models\User;
use Carbon\Carbon;

class CleaningController extends Controller
{

    public function index()
    {
        $user = auth()->user();
    
        // Consulta base para obtener las limpiezas activas
        $activeCleanings = Cleaning::query()
            ->whereNull('endDateTime')
            ->orderBy('requestedDateTime');
    
        // Aplicar filtro de usuario si es de tipo "Cleaner"
        if ($user->userType === 'Cleaner') {
            $activeCleanings->where('user_id', $user->id);
        }
    
        // Paginación y obtención de resultados finales
        $activeCleanings = $activeCleanings->simplePaginate(10);
    
        return view('cleanings.index', compact('activeCleanings'));
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
