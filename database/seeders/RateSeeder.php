<?php
namespace Database\Seeders;
use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run()
    {
        Rate::insert([
            ['label' => 'Regular Table', 'price_per_hour' => 25000,  'description' => 'Standard play area, perfect for casual games.'],
            ['label' => 'VIP Room',      'price_per_hour' => 75000,  'description' => 'Sound-proofed private room with dedicated service.'],
            ['label' => 'Tournament',    'price_per_hour' => 120000, 'description' => 'Full-spec competition table with Simonis cloth.'],
        ]);
    }
}