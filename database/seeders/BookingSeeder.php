<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Rate;
use App\Models\Room;
use App\Models\User;
use App\Models\Commodity;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rateIds = Rate::pluck('id')->toArray();
        $roomIds = Room::pluck('id')->toArray();
        $userIds = User::where('userType', 'Guest')->pluck('id')->toArray();
        $commodityIds = Commodity::pluck('id')->toArray();
    
        // Crear 50 bookings
        for ($i = 0; $i < 50; $i++) {
            $bookingDate = Carbon::now()->subDays(rand(1, 14));
            $startDate = Carbon::now()->subDays(rand(1, 14));
            $agreedEndDate = $startDate->copy()->addDays(rand(1, 14));
            $actualEndDate = $agreedEndDate->isPast() ? $agreedEndDate : null;
    
            $randomFinalPrice = null;
            if (!empty($actualEndDate)) {
                $randomFinalPrice = mt_rand(300 * 100, 5000 * 100) / 100;
            }
    
            // Generar un periodo de tiempo para el booking actual
            $bookingPeriod = CarbonPeriod::create($startDate, $agreedEndDate);
    
            // Verificar disponibilidad de usuario y habitación en el periodo
            $availableUserIds = $this->getAvailableUsers($userIds, $bookingPeriod);
            $availableRoomIds = $this->getAvailableRooms($roomIds, $bookingPeriod);
    
            // Continuar solo si hay usuarios y habitaciones disponibles
            if (empty($availableUserIds) || empty($availableRoomIds)) {
                continue; // Saltar esta iteración si no hay disponibilidad
            }
    
            $booking = Booking::create([
                'bookingDate' => $bookingDate,
                'startDate' => $startDate,
                'agreedEndDate' => $agreedEndDate,
                'actualEndDate' => $actualEndDate,
                'finalPrice' => $randomFinalPrice,
                'numberOfPeople' => rand(1, 5),
                'returnDeposit' => (bool)rand(0, 1),
                'rate_id' => $rateIds[array_rand($rateIds)],
                'room_id' => $availableRoomIds[array_rand($availableRoomIds)],
                'user_id' => $availableUserIds[array_rand($availableUserIds)],
            ]);
    
            // Asignar commodities aleatorias a cada booking
            $randomCommodityIds = $this->getRandomCommodityIds($commodityIds);
            $booking->commodities()->attach($randomCommodityIds);
        }
    }
    
    private function getAvailableUsers(array $userIds, CarbonPeriod $bookingPeriod): array
    {
        // Obtener los usuarios con reservas en conflicto con el periodo
        $conflictingUserIds = Booking::whereIn('user_id', $userIds)
            ->where(function ($query) use ($bookingPeriod) {
                $query->whereBetween('startDate', [$bookingPeriod->getStartDate(), $bookingPeriod->getEndDate()])
                      ->orWhereBetween('agreedEndDate', [$bookingPeriod->getStartDate(), $bookingPeriod->getEndDate()]);
            })
            ->pluck('user_id')
            ->toArray();
    
        // Filtrar los usuarios disponibles
        return array_diff($userIds, $conflictingUserIds);
    }
    
    private function getAvailableRooms(array $roomIds, CarbonPeriod $bookingPeriod): array
    {
        // Obtener las habitaciones con reservas en conflicto con el periodo
        $conflictingRoomIds = Booking::whereIn('room_id', $roomIds)
            ->where(function ($query) use ($bookingPeriod) {
                $query->whereBetween('startDate', [$bookingPeriod->getStartDate(), $bookingPeriod->getEndDate()])
                      ->orWhereBetween('agreedEndDate', [$bookingPeriod->getStartDate(), $bookingPeriod->getEndDate()]);
            })
            ->pluck('room_id')
            ->toArray();
    
        // Filtrar las habitaciones disponibles
        return array_diff($roomIds, $conflictingRoomIds);
    }
    
    private function getRandomCommodityIds(array $commodityIds, $max = 15)
    {
        shuffle($commodityIds);
        return array_slice($commodityIds, 0, rand(1, $max));
    }
       
}
