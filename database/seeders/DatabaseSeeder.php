<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        $this->call([
            UserSeeder::class,
            RateSeeder::class,
            TableSeeder::class,
            PromoSeeder::class,
            MenuSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
