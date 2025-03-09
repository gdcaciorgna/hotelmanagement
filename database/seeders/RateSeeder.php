<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Commodity;
use App\Models\Rate;
use Carbon\Carbon;

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
        $commodityIds = Commodity::pluck('id')->toArray();

        foreach ($rates as $title => $value) {
            $rate = Rate::create([
                'title' => $title,
                'description' => $value,
            ]);

            for ($i = 0; $i < 3; $i++) {
                // Asignar commodities aleatorias a cada rate
                $randomCommodityIds = $this->getRandomCommodityIds($commodityIds);
                $rate->commodities()->attach($randomCommodityIds, [
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }    
    
    private function getRandomCommodityIds(array $commodityIds, $max = 5)
    {
        shuffle($commodityIds);
        return array_slice($commodityIds, 0, rand(1, $max));
    }
}
