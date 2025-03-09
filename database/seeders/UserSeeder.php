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
        $docTypes = ['DNI', 'PAS'];

        for ($i = 0; $i < 40; $i++) {

            $phone = '549' . mt_rand(2000000000, 9999999999);
            $streets = ['Calle A', 'Calle B', 'Calle C'];
            $cities = ['Buenos Aires', 'Córdoba', 'Rosario'];
            $provinces = ['Buenos Aires', 'Córdoba', 'Santa Fe'];
            $address = mt_rand(1, 500) . ' ' . $streets[array_rand($streets)] . ', ' . $cities[array_rand($cities)] . ', ' . $provinces[array_rand($provinces)];
            $userType = $userTypes[array_rand($userTypes)];
            $disabledReasons = ["Ruidos molestos fuera del horario permitido", "Rotura sobre instalaciones del hotel"];

            $firstNames = ['Juan', 'María', 'Carlos', 'Laura', 'Pedro', 'Ana', 'José', 'Sofía', 'Miguel', 'Lucía', 'Luis', 'Eduardo', 'Sandra', 'Rosana', 'Mariela', 'Clara'];
            $lastNames = ['García', 'Fernández', 'Martínez', 'López', 'Sánchez', 'Pérez', 'González', 'Rodríguez', 'Gómez', 'Díaz', 'Falcón', 'Romero', 'Álvarez', 'Bustos'];    
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];

            $basicString = strtolower(str_replace(' ', '.', $firstName. ' ' . $lastName));
            $basicString = iconv('UTF-8', 'ASCII//TRANSLIT', $basicString);
            $basicString = preg_replace('/[^a-zA-Z0-9.]/', '', $basicString);            
            $email = $basicString . '@gmail.com';

            //ADMIN USER
            if($i == 0){
                $firstName = 'Gerardo';
                $lastName = 'Caciorgna';
                $userType = 'Receptionist';
                $email = 'gdecaciorgna@gmail.com';
            }

            //CLEANER USER
            if($i == 1){
                $firstName = 'Esther';
                $lastName = 'Sequeira';
                $userType = 'Cleaner';
                $email = 'esthersequeira@gmail.com';
            }

            //CLEANER USER
            if($i == 2){
                $firstName = 'Juan';
                $lastName = 'Perez';
                $userType = 'Guest';
                $email = 'juanperez@gmail.com';
            }
           
            $numDoc = mt_rand(10000000, 99999999);
            $docType = $docTypes[array_rand($docTypes)];
            $status = true;
            if($userType == "Guest"){
                $status = rand(0, 3) === 3 ? false : true;
            }min: 
            $disabledStartDate = $status ? null : now();
            $disabledReason = $status ? null : $disabledReasons[array_rand($disabledReasons)];
            $bornDateTimestamp = mt_rand(strtotime('1950-01-01'), strtotime('2005-12-31'));
            $bornDate = date('Y-m-d', $bornDateTimestamp);    

            $weekdayStartWorkHours = null;
            $weekdayEndWorkHours = null;
            $startEmploymentDate = null;
            if($userType != 'Guest'){
                $weekdayStartWorkHours = '07:00';
                $weekdayEndWorkHours = '15:00';

                $startEmploymentTimestamp = mt_rand(strtotime('1990-01-01'), strtotime(now()));
                $startEmploymentDate = date('Y-m-d', $startEmploymentTimestamp);
            }

            DB::table('users')->insert([
                'docType' => $docType,
                'numDoc' => $numDoc,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'bornDate' => $bornDate,
                'userType' => $userType,
                'status' => $status,
                'disabledStartDate' => $disabledStartDate,
                'disabledReason' => $disabledReason,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'weekdayStartWorkHours' => $weekdayStartWorkHours,
                'weekdayEndWorkHours' => $weekdayEndWorkHours,
                'startEmploymentDate' => $startEmploymentDate,
                'password' => bcrypt('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}