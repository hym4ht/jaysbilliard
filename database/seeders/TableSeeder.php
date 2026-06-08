<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            [
                'name' => 'Meja 01',
                'type' => 'regular',
                'capacity' => 4,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja standar untuk permainan santai.',
            ],
            [
                'name' => 'Meja 02',
                'type' => 'vip',
                'capacity' => 6,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja VIP dengan area lebih private.',
            ],
            [
                'name' => 'Meja 03',
                'type' => 'regular',
                'capacity' => 4,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja standar untuk permainan harian.',
            ],
            [
                'name' => 'Meja 04',
                'type' => 'vip',
                'capacity' => 6,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja VIP untuk sesi bermain eksklusif.',
            ],
        ];

        foreach ($tables as $table) {
            Table::updateOrCreate(
                ['name' => $table['name']],
                $table + ['image' => null]
            );
        }

        $user = User::where('email', 'user@example.com')->first();
        $bookedTables = Table::whereIn('name', ['Meja 01', 'Meja 02', 'Meja 03', 'Meja 04'])
            ->orderBy('name')
            ->get();
        $today = Carbon::today()->toDateString();

        foreach ($bookedTables as $index => $table) {
            $startHour = 14 + $index;
            // Use afternoon rate (25000) for seeding
            $rate = \App\Models\Rate::where('time_period', 'afternoon')->first();
            $hourlyRate = $rate ? $rate->hourly_rate : 25000;

            Booking::updateOrCreate(
                [
                    'table_id' => $table->id,
                    'booking_date' => $today,
                    'start_time' => sprintf('%02d:00:00', $startHour),
                ],
                [
                    'user_id' => $user?->id,
                    'customer_name' => $user?->name ?? 'Customer Seeder',
                    'phone' => $user?->phone ?? '080000000000',
                    'end_time' => sprintf('%02d:00:00', $startHour + 1),
                    'total_price' => $hourlyRate,
                    'status' => 'pending',
                ]
            );
        }
    }
}
