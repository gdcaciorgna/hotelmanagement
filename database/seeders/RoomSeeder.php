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
        $descriptionsAvailable = ['Diseñada pensando en el bienestar y la comodidad de toda la familia. Con 50 metros cuadrados, esta habitación espaciosa ofrece una cama matrimonial y dos camas individuales, separadas por un área de estar que incluye un sofá y una mesa de café. ', 'Es la elección perfecta para viajeros que buscan comodidad y estilo a un precio asequible. Con 35 metros cuadrados de espacio moderno y funcional, la habitación ofrece dos camas dobles y un área de trabajo ergonómica. ', 'Perfecta para una estancia breve o un viaje de negocios, ofrece todo lo necesario para un descanso reparador. Con 25 metros cuadrados, la habitación está equipada con una cama de dos plazas, un televisor LCD de 32 pulgadas y un escritorio funcional.', 'Diseñada para ofrecerte una experiencia de hospedaje que va más allá de lo ordinario. Con 150 metros cuadrados de espacio altamente decorado, esta suite cuenta con una sala de estar amplia, un gran comedor y una cocina completamente equipada.'  ];

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
