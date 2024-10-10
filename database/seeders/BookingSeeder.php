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

        // Crear 30 bookings
        for ($i = 0; $i < 30; $i++) {
            $bookingDate = Carbon::now()->subDays(rand(1, 14));
            $startDate = Carbon::now()->subDays(rand(1, 14));
            $agreedEndDate = $startDate->copy()->addDays(rand(1, 14));
            $actualEndDate = $agreedEndDate->isPast() ? $agreedEndDate : null;

            $randomFinalPrice = null;
            if(!empty($actualEndDate)){
                $randomFinalPrice = mt_rand(300 * 100, 5000 * 100) / 100;
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
                'room_id' => $roomIds[array_rand($roomIds)],
                'user_id' => $userIds[array_rand($userIds)],
            ]);

            // Asignar commodities aleatorias a cada booking
            $randomCommodityIds = $this->getRandomCommodityIds($commodityIds);
            $booking->commodities()->attach($randomCommodityIds);
        }
    }

    private function getRandomCommodityIds(array $commodityIds, $max = 5)
    {
        shuffle($commodityIds);
        return array_slice($commodityIds, 0, rand(1, $max));
    }
       
}
