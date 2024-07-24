<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates['Essential'] = 'Incluye servicio de limpieza de la habitación y el acceso a la pileta climatizada del hotel.';
        $rates['Extra'] = 'Incluye servicio de limpieza de la habitación y el acceso a la pileta climatizada del hotel y servicio de desayuno en los horarios establecidos.';
        $rates['Premium'] = 'Incluye servicio de limpieza de la habitación y el acceso a la pileta climatizada del hotel, servicio de desayuno en los horarios establecidos, quincho de reuniones, gimnasio, sala de juegos y jacuzzi.';

        foreach ($rates as $title => $value) {
            DB::table('rates')->insert([
                'title' => $title,
                'description' => $value,
            ]);
        }
    }            
}
