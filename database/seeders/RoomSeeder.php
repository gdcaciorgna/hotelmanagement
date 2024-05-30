<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomStatusAvailable = ['Available', 'Cleaning', 'Unavailable'];
        $descriptionsAvailable = ['Habitación con 2 habitaciones,  baño en suite y vista al lago', 'Habitación simple con vista al parque'];

        for ($i = 0; $i < 30; $i++) {
            $status = $roomStatusAvailable[array_rand($roomStatusAvailable)];
            $description  = $descriptionsAvailable[array_rand($descriptionsAvailable)];
            $code = mt_rand(1, 4) . mt_rand(0, 1) .  mt_rand(0, 9);
            $maxOfGuest = mt_rand(1,5);
            DB::table('rooms')->insert([
                'code' => $code,
                'description' => $description,
                'status' => $status,
                'maxOfGuests' => $maxOfGuest,
            ]);
        }
    }            
}
