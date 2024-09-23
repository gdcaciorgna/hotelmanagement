<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cleaning;
use App\Models\Rate;
use App\Models\Room;
use Carbon\Carbon;

class CleaningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rateIds = Rate::pluck('id')->toArray();
        $roomIds = Room::pluck('id')->toArray();

        // Crear 30 cleanings
        for ($i = 0; $i < 30; $i++) {
            $requestedDateTime = Carbon::now()->subDays(rand(1, 14));
            $startDateTime = Carbon::now()->subDays(rand(1, 14));
            $endDateTime = Carbon::now()->subDays(rand(1, 14));

            $randomFinishedCleaning = rand(1, 100) <= 70;
            $endDateTime = ($randomFinishedCleaning && $endDateTime->greaterThan($startDateTime)) ? $endDateTime : null;

            Cleaning::create([
                'requestedDateTime' => $requestedDateTime,
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                'room_id' => $roomIds[array_rand($roomIds)],
            ]);
        }
    }       
}
