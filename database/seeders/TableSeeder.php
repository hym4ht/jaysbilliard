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
                'price_per_hour' => 25000,
                'capacity' => 4,
                'status' => 'active',
                'is_available' => false,
                'description' => 'Meja standar untuk permainan santai.',
            ],
            [
                'name' => 'Meja 02',
                'type' => 'vip',
                'price_per_hour' => 75000,
                'capacity' => 6,
                'status' => 'active',
                'is_available' => false,
                'description' => 'Meja VIP dengan area lebih private.',
            ],
            [
                'name' => 'Meja 03',
                'type' => 'regular',
                'price_per_hour' => 25000,
                'capacity' => 4,
                'status' => 'active',
                'is_available' => false,
                'description' => 'Meja standar untuk permainan harian.',
            ],
            [
                'name' => 'Meja 04',
                'type' => 'vip',
                'price_per_hour' => 75000,
                'capacity' => 6,
                'status' => 'active',
                'is_available' => false,
                'description' => 'Meja VIP untuk sesi bermain eksklusif.',
            ],
            [
                'name' => 'Meja 05',
                'type' => 'regular',
                'price_per_hour' => 25000,
                'capacity' => 4,
                'status' => 'maintenance',
                'is_available' => false,
                'description' => 'Meja standar sedang dalam maintenance.',
            ],
            [
                'name' => 'Meja 06',
                'type' => 'vip',
                'price_per_hour' => 75000,
                'capacity' => 6,
                'status' => 'maintenance',
                'is_available' => false,
                'description' => 'Meja VIP sedang dalam maintenance.',
            ],
            [
                'name' => 'Meja 07',
                'type' => 'regular',
                'price_per_hour' => 25000,
                'capacity' => 4,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja standar tersedia untuk dipesan.',
            ],
            [
                'name' => 'Meja 08',
                'type' => 'vip',
                'price_per_hour' => 75000,
                'capacity' => 6,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja VIP tersedia untuk dipesan.',
            ],
            [
                'name' => 'Meja 09',
                'type' => 'regular',
                'price_per_hour' => 25000,
                'capacity' => 4,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja standar tersedia untuk dipesan.',
            ],
            [
                'name' => 'Meja 10',
                'type' => 'regular',
                'price_per_hour' => 25000,
                'capacity' => 4,
                'status' => 'active',
                'is_available' => true,
                'description' => 'Meja standar tersedia untuk dipesan.',
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
                    'total_price' => (int) $table->price_per_hour,
                    'status' => 'pending',
                ]
            );
        }
    }
}
