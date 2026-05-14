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
            TableSeeder::class,
            RateSeeder::class,
            PromoSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
