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
        $cleanings = [];

        // Crear 30 cleanings
        for ($i = 0; $i < 15; $i++) {
            $requestedDateTime = Carbon::now()->subDays(rand(1, 14));
            $requestedDateTime = $requestedDateTime->hour(rand(7, 14))->minute(rand(0, 59))->second(rand(0, 59));

            $hasStartDateTime = rand(1, 100) <= 70;
            $startDateTime = $hasStartDateTime ? $requestedDateTime->copy()->addHours(rand(1, 48)) : null;

            if ($startDateTime) {
                if ($startDateTime->greaterThanOrEqualTo(Carbon::now())) {
                    $startDateTime = Carbon::now()->subHours(rand(1, 48));
                }
                if ($startDateTime->hour < 7) {
                    $startDateTime->hour(7)->minute(rand(0, 59))->second(rand(0, 59));
                } elseif ($startDateTime->hour > 15) {
                    $startDateTime->hour(14)->minute(rand(0, 59))->second(rand(0, 59));
                }

                $hasEndDateTime = rand(1, 100) <= 30;
                $endDateTime = $hasEndDateTime ? $startDateTime->copy()->addDays(1) : null;

                if ($endDateTime) {
                    if ($endDateTime->lessThanOrEqualTo($startDateTime)) {
                        $endDateTime = $startDateTime->copy()->addHours(rand(1, 48));
                    }
                    $endDateTime = $endDateTime->hour(rand(7, 14))->minute(rand(0, 59))->second(rand(0, 59));
                    $updatedAt = $endDateTime->copy();
                } else {
                    $updatedAt = $requestedDateTime->copy();
                }
            } else {
                $endDateTime = null;
                $updatedAt = $requestedDateTime->copy();
            }

            $availableRoomIds = array_diff($roomIds, $usedRoomIds);
            if (empty($availableRoomIds)) {
                break;
            }
            $selectedRoomId = $availableRoomIds[array_rand($availableRoomIds)];
            $usedRoomIds[] = $selectedRoomId;

            $cleanerId = null;
            if (!empty($endDateTime)) {
                $cleanerId = $userIds[array_rand($userIds)];
            }

            $cleanings[] = [
                'requestedDateTime' => $requestedDateTime,
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                'room_id' => $selectedRoomId,
                'user_id' => $cleanerId,
                'created_at' => $requestedDateTime,
                'updated_at' => $updatedAt
            ];
        }

        // Ordenar las cleanings por requestedDateTime
        usort($cleanings, function ($a, $b) {
            return $a['requestedDateTime']->greaterThan($b['requestedDateTime']);
        });

        // Insertar las cleanings en la base de datos
        foreach ($cleanings as $cleaning) {
            Cleaning::create($cleaning);
        }
    }
}