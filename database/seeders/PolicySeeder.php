<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('policies')->insert([
            'description' => 'basePricePerPersonPerDay',
            'value' => '150',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);    
        DB::table('policies')->insert([
            'description' => 'damageDeposit',
            'value' => '65',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);    
        DB::table('policies')->insert([
            'description' => 'cleaningWorkingHoursFrom',
            'value' => '07:00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);    
        DB::table('policies')->insert([
            'description' => 'cleaningWorkingHoursTo',
            'value' => '15:00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);    
    }
}
