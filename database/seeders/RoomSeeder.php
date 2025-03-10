<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $descriptionsAvailable = ['Diseñada pensando en el bienestar y la comodidad de toda la familia. Con 50 metros cuadrados, esta habitación espaciosa ofrece una cama matrimonial y dos camas individuales, separadas por un área de estar que incluye un sofá y una mesa de café. ', 'Es la elección perfecta para viajeros que buscan comodidad y estilo a un precio asequible. Con 35 metros cuadrados de espacio moderno y funcional, la habitación ofrece dos camas dobles y un área de trabajo ergonómica. ', 'Perfecta para una estancia breve o un viaje de negocios, ofrece todo lo necesario para un descanso reparador. Con 25 metros cuadrados, la habitación está equipada con una cama de dos plazas, un televisor LCD de 32 pulgadas y un escritorio funcional.', 'Diseñada para ofrecerte una experiencia de hospedaje que va más allá de lo ordinario. Con 150 metros cuadrados de espacio altamente decorado, esta suite cuenta con una sala de estar amplia, un gran comedor y una cocina completamente equipada.'  ];
        $imagesAvailable = ['room1.png', 'room2.png', 'room3.png'];
        $roomUniqueCodes = [];

        for ($i = 0; $i < 30; $i++) {
            $description  = $descriptionsAvailable[array_rand($descriptionsAvailable)];
            $code = mt_rand(1, 4) . mt_rand(0, 1) .  mt_rand(0, 9);
            //If exists room code, avoid creation
            if(in_array($code, $roomUniqueCodes)){
                continue;
            }
            $roomUniqueCodes[] = $code;
            $maxOfGuest = mt_rand(1,5);
            $image = $imagesAvailable[array_rand($imagesAvailable)];

            DB::table('rooms')->insert([
                'code' => $code,
                'description' => $description,
                'maxOfGuests' => $maxOfGuest,
                'image' => $image,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }            
}
