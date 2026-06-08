<?php
namespace Database\Seeders;
use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run()
    {
        $rates = [
            [
                'time_period' => 'afternoon',
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'hourly_rate' => 25000,
            ],
            [
                'time_period' => 'evening',
                'start_time' => '18:00:00',
                'end_time' => '01:00:00',
                'hourly_rate' => 35000,
            ],
        ];

        foreach ($rates as $rate) {
            Rate::updateOrCreate(
                ['time_period' => $rate['time_period']],
                $rate
            );
        }
    }
}