<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commodity;
use App\Models\CommodityPriceHistory;


class CommodityPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commodities = Commodity::all();

        foreach ($commodities as $commodity) {
            $price = rand(40,150);
            CommodityPriceHistory::create([
                'commodity_id' => $commodity->id,
                'price' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        //
    }
}
