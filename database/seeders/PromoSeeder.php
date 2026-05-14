<?php
namespace Database\Seeders;
use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run()
    {
        Promo::truncate();
        Promo::insert([
            [
                'title' => 'MALAM PELAJAR',
                'badge' => 'WAKTU TERBATAS',
                'description' => 'Dapatkan diskon 20% untuk setiap meja sebelum pukul 6 sore pada hari kerja.',
                'image' => 'promos/student-night.png',
                'cta_text' => 'Pelajari Lebih Lanjut',
                'cta_url' => '#',
                'is_active' => true,
            ],
            [
                'title' => 'TANGGA MINGGUAN',
                'badge' => 'TURNAMEN',
                'description' => 'Bergabunglah dalam kompetisi setiap hari Jumat. Hadiah hingga Rp 5.000.000.',
                'image' => 'promos/weekly-ladder.png',
                'cta_text' => 'Pelajari Lebih Lanjut',
                'cta_url' => '#',
                'is_active' => true,
            ],
        ]);
    }
}