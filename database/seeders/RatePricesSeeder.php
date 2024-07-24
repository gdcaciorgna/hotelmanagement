<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rate;
use App\Models\RatePriceHistory;


class RatePricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rates = Rate::all();

        foreach ($rates as $rate) {
            $price = rand(40,150);
            RatePriceHistory::create([
                'rate_id' => $rate->id,
                'price' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
