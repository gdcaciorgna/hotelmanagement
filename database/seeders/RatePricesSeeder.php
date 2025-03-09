<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rate;
use App\Models\RatePricesHistory;
use Carbon\Carbon;

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
            RatePricesHistory::create([
                'rate_id' => $rate->id,
                'price' => $price,
                'created_at' => Carbon::now(),
                'updated_at' => null,
            ]);
        }
    }
}
