<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cleaning;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class CleaningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::where('userType', 'Cleaner')->pluck('id')->toArray();
        $roomIds = Room::pluck('id')->toArray();
        $usedRoomIds = [];

        // Crear 30 cleanings
        for ($i = 0; $i < 30; $i++) {
            $requestedDateTime = Carbon::now()->subDays(rand(1, 14));

            // Determina si este cleaning estará finalizado y establece el endDateTime
            $randomFinishedCleaning = rand(1, 100) <= 40;
            $endDateTime = $randomFinishedCleaning ? Carbon::now()->subDays(rand(1, 14)) : null;

            // Genera startDateTime basado en las condiciones:
            // Puede ser nulo o un valor mayor a requestedDateTime y menor que endDateTime o menor que hoy si endDateTime está vacío
            $startDateTime = rand(0, 1) ? null : $requestedDateTime->copy()->addHours(rand(1, 48));

            if ($startDateTime && $endDateTime && $startDateTime->greaterThan($endDateTime)) {
                $startDateTime = $endDateTime->copy()->subHours(rand(1, 24));
            } elseif ($startDateTime && !$endDateTime && $startDateTime->greaterThan(Carbon::now())) {
                $startDateTime = Carbon::now()->subHours(rand(1, 24));
            }

            // Selecciona un room_id aleatorio que no se haya utilizado
            $availableRoomIds = array_diff($roomIds, $usedRoomIds);
            if (empty($availableRoomIds)) {
                // Si no hay habitaciones disponibles, termina el ciclo
                break;
            }
            $selectedRoomId = $availableRoomIds[array_rand($availableRoomIds)];
            $usedRoomIds[] = $selectedRoomId; // Añadir la habitación utilizada a la lista
    
            //Seleccionar un usuario si solamente si ya finalizó la limpieza, sino evitarlo.
            $cleanerId =  null;
            if(!empty($endDateTime)){
                $cleanerId = $userIds[array_rand($userIds)];
            }

            // Crear el cleaning
            Cleaning::create([
                'requestedDateTime' => $requestedDateTime,
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                'room_id' => $selectedRoomId,
                'user_id' => $cleanerId
            ]);
        }
    }
}
