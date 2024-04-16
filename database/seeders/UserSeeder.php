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
            
            $basicString = strtolower(str_replace(' ', '.', $fullName));
            $basicString = iconv('UTF-8', 'ASCII//TRANSLIT', $basicString);
            $basicString = preg_replace('/[^a-zA-Z0-9.]/', '', $basicString);            
            $email = $basicString . '@gmail.com';
            
                        
            $dni = mt_rand(10000000, 99999999);
            $userType = $userTypes[array_rand($userTypes)];
            $status = rand(0, 5) === 5 ? false : true;
            $disabledStartDate = $status ? null : now();
            $disabledReason = $status ? null : 'Motivo de inhabilitacion en testeo';
            $bornDateTimestamp = mt_rand(strtotime('1950-01-01'), strtotime('2005-12-31'));
            $bornDate = date('Y-m-d', $bornDateTimestamp);    

            DB::table('users')->insert([
                'dni' => $dni,
                'fullName' => $fullName,
                'bornDate' => $bornDate,
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
