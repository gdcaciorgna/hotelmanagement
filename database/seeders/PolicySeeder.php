<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('policies')->insert([
            'description' => 'basePricePerPersonPerDay',
            'value' => '70000'
        ]);    
        DB::table('policies')->insert([
            'description' => 'damageDeposit',
            'value' => '10000'
        ]);    
        DB::table('policies')->insert([
            'description' => 'cleaningWorkingHoursFrom',
            'value' => '07:00'
        ]);    
        DB::table('policies')->insert([
            'description' => 'cleaningWorkingHoursTo',
            'value' => '15:00'
        ]);    
    }
}
