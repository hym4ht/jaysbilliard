<?php
namespace Database\Seeders;
use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run()
    {
        Rate::insert([
            [
                'time_period' => 'afternoon',
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'hourly_rate' => 25000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'time_period' => 'evening',
                'start_time' => '18:00:00',
                'end_time' => '01:00:00',
                'hourly_rate' => 35000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}