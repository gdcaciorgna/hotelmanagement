<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = ['Receptionist', 'Cleaner', 'Guest'];

        for ($i = 0; $i < 30; $i++) {
            $fullName = $this->generateFullName();
            $email = strtolower(str_replace(' ', '.', $fullName)) . '@gmail.com';
            $dni = mt_rand(10000000, 99999999);
            $userType = $userTypes[array_rand($userTypes)];
            $status = rand(0, 5) === 5 ? false : true;
            $disabledStartDate = $status ? null : now();
            $disabledReason = $status ? null : 'Motivo de inhabilitacion en testeo';

            DB::table('users')->insert([
                'dni' => $dni,
                'fullName' => $fullName,
                'userType' => $userType,
                'status' => $status,
                'disabledStartDate' => $disabledStartDate,
                'disabledReason' => $disabledReason,
                'email' => $email,
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
    private function generateFullName()
    {
        $names = ['Juan', 'María', 'Carlos', 'Laura', 'Pedro', 'Ana', 'José', 'Sofía', 'Miguel', 'Lucía'];
        $lastNames = ['García', 'Fernández', 'Martínez', 'López', 'Sánchez', 'Pérez', 'González', 'Rodríguez', 'Gómez', 'Díaz'];

        $fullName = $names[array_rand($names)] . ' ' . $lastNames[array_rand($lastNames)];

        return $fullName;
    }
}
