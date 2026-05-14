<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();
        $tables = Table::where('status', 'active')->orderBy('name')->get();

        if ($tables->isEmpty()) {
            return;
        }

        $customers = [
            ['name' => 'Ayu Alda', 'phone' => '081234100001'],
            ['name' => 'Rian Saputra', 'phone' => '081234100002'],
            ['name' => 'Zaenal Arif', 'phone' => '081234100003'],
            ['name' => 'Nadia Putri', 'phone' => '081234100004'],
            ['name' => 'Dimas Pratama', 'phone' => '081234100005'],
            ['name' => 'Fajar Nugroho', 'phone' => '081234100006'],
            ['name' => 'Salsa Amelia', 'phone' => '081234100007'],
            ['name' => 'Bayu Ramadhan', 'phone' => '081234100008'],
            ['name' => 'Maya Lestari', 'phone' => '081234100009'],
            ['name' => 'Raka Wijaya', 'phone' => '081234100010'],
            ['name' => 'Intan Permata', 'phone' => '081234100011'],
            ['name' => 'Kevin Hartono', 'phone' => '081234100012'],
        ];

        $transactions = [
            ['day' => 0, 'hour' => 10, 'duration' => 2, 'table' => 0, 'customer' => 0, 'fnb' => 52000],
            ['day' => 0, 'hour' => 12, 'duration' => 1, 'table' => 1, 'customer' => 1, 'fnb' => 35000],
            ['day' => 0, 'hour' => 14, 'duration' => 2, 'table' => 2, 'customer' => 2, 'fnb' => 68000],
            ['day' => 0, 'hour' => 16, 'duration' => 3, 'table' => 3, 'customer' => 3, 'fnb' => 85000],
            ['day' => 0, 'hour' => 19, 'duration' => 2, 'table' => 4, 'customer' => 4, 'fnb' => 42000],
            ['day' => 1, 'hour' => 11, 'duration' => 2, 'table' => 5, 'customer' => 5, 'fnb' => 30000],
            ['day' => 1, 'hour' => 15, 'duration' => 1, 'table' => 0, 'customer' => 6, 'fnb' => 22000],
            ['day' => 1, 'hour' => 20, 'duration' => 3, 'table' => 1, 'customer' => 7, 'fnb' => 76000],
            ['day' => 2, 'hour' => 13, 'duration' => 2, 'table' => 2, 'customer' => 8, 'fnb' => 47000],
            ['day' => 2, 'hour' => 18, 'duration' => 2, 'table' => 3, 'customer' => 9, 'fnb' => 60000],
            ['day' => 3, 'hour' => 17, 'duration' => 1, 'table' => 4, 'customer' => 10, 'fnb' => 18000],
            ['day' => 4, 'hour' => 21, 'duration' => 2, 'table' => 5, 'customer' => 11, 'fnb' => 54000],
        ];

        foreach ($transactions as $item) {
            $table = $tables[$item['table'] % $tables->count()];
            $customer = $customers[$item['customer'] % count($customers)];
            $date = Carbon::today()->subDays($item['day']);
            $startTime = sprintf('%02d:00:00', $item['hour']);
            $endTime = sprintf('%02d:00:00', $item['hour'] + $item['duration']);
            $tableRevenue = (int) $table->price_per_hour * $item['duration'];
            $fnbRevenue = (int) $item['fnb'];

            $booking = Booking::updateOrCreate(
                [
                    'table_id' => $table->id,
                    'booking_date' => $date->toDateString(),
                    'start_time' => $startTime,
                ],
                [
                    'user_id' => $user?->id,
                    'customer_name' => $customer['name'],
                    'phone' => $customer['phone'],
                    'end_time' => $endTime,
                    'total_price' => $tableRevenue + $fnbRevenue,
                    'status' => 'confirmed',
                ]
            );

            if ($fnbRevenue > 0) {
                Order::updateOrCreate(
                    ['booking_id' => $booking->id],
                    ['total_price_fnb' => $fnbRevenue]
                );
            }
        }
    }
}
