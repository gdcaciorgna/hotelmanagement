<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(PolicySeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(CommoditySeeder::class);
        $this->call(CommodityPricesSeeder::class);
        $this->call(RateSeeder::class);
        $this->call(RatePricesSeeder::class);
        $this->call(BookingSeeder::class);
        $this->call(CleaningSeeder::class);
    }
}

