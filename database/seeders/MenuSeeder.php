<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Nasi Goreng Jay\'s',
                'price' => 22000,
                'category' => 'Hidangan Utama',
                'description' => 'Nasi goreng spesial dengan telur, ayam, dan bumbu rumahan.',
                'image' => 'images/fnb/nasi-goreng.png',
            ],
            [
                'name' => 'Classic Burger',
                'price' => 28000,
                'category' => 'Hidangan Utama',
                'description' => 'Burger roti lembut dengan patty, sayur segar, dan saus spesial.',
                'image' => 'images/fnb/burger.png',
            ],
            [
                'name' => 'Club Sandwich',
                'price' => 26000,
                'category' => 'Hidangan Utama',
                'description' => 'Sandwich isi ayam, telur, sayur, dan saus mayo.',
                'image' => 'images/fnb/sandwich.png',
            ],
            [
                'name' => 'Chicken Wings',
                'price' => 30000,
                'category' => 'Hidangan Utama',
                'description' => 'Sayap ayam renyah dengan pilihan saus gurih pedas.',
                'image' => 'images/fnb/chicken-wings.png',
            ],
            [
                'name' => 'French Fries',
                'price' => 18000,
                'category' => 'Camilan',
                'description' => 'Kentang goreng renyah cocok buat teman main billiard.',
                'image' => 'images/fnb/french-fries.png',
            ],
            [
                'name' => 'Chitato Lite',
                'price' => 12000,
                'category' => 'Camilan',
                'description' => 'Keripik kentang ringan dengan rasa gurih.',
                'image' => 'images/fnb/Chitato Lite.jpg',
            ],
            [
                'name' => 'Chitato Party',
                'price' => 18000,
                'category' => 'Camilan',
                'description' => 'Snack sharing size untuk main bareng teman.',
                'image' => 'images/fnb/Chitato Party.jpg',
            ],
            [
                'name' => 'Chiki Balls',
                'price' => 10000,
                'category' => 'Camilan',
                'description' => 'Camilan ringan rasa keju yang gurih.',
                'image' => 'images/fnb/Chiki Balls.jpg',
            ],
            [
                'name' => 'TARO',
                'price' => 10000,
                'category' => 'Camilan',
                'description' => 'Snack renyah favorit untuk menemani permainan.',
                'image' => 'images/fnb/TARO.jpg',
            ],
            [
                'name' => 'Pocky',
                'price' => 12000,
                'category' => 'Camilan',
                'description' => 'Biskuit stik manis dengan lapisan cokelat.',
                'image' => 'images/fnb/Pocky.jpg',
            ],
            [
                'name' => 'Beng Beng',
                'price' => 8000,
                'category' => 'Camilan',
                'description' => 'Wafer cokelat renyah untuk camilan cepat.',
                'image' => 'images/fnb/Beng Beng.jpeg',
            ],
            [
                'name' => 'Beng Beng Jajan',
                'price' => 10000,
                'category' => 'Camilan',
                'description' => 'Cokelat wafer praktis untuk teman santai.',
                'image' => 'images/fnb/Beng Beng Jajan.jpg',
            ],
            [
                'name' => 'Le Minerale',
                'price' => 7000,
                'category' => 'Minuman',
                'description' => 'Air mineral dingin untuk menyegarkan permainan.',
                'image' => 'images/fnb/Le Minerale.jpg',
            ],
            [
                'name' => 'Cola',
                'price' => 12000,
                'category' => 'Minuman',
                'description' => 'Minuman soda dingin dengan rasa cola klasik.',
                'image' => 'images/fnb/cola.jpg',
            ],
            [
                'name' => 'Pocari Sweat',
                'price' => 12000,
                'category' => 'Minuman',
                'description' => 'Minuman ion untuk bantu tetap segar.',
                'image' => 'images/fnb/Pocari Sweat.jpg',
            ],
            [
                'name' => 'Teh Kotak',
                'price' => 8000,
                'category' => 'Minuman',
                'description' => 'Teh manis praktis dan menyegarkan.',
                'image' => 'images/fnb/Teh kotak.jpg',
            ],
            [
                'name' => 'Mocktail Fresh',
                'price' => 20000,
                'category' => 'Minuman',
                'description' => 'Minuman segar dengan rasa buah yang ringan.',
                'image' => 'images/fnb/mocktail.png',
            ],
            [
                'name' => 'Espresso',
                'price' => 16000,
                'category' => 'Kopi',
                'description' => 'Kopi hitam pekat untuk dorongan fokus.',
                'image' => 'images/fnb/espresso.png',
            ],
            [
                'name' => 'Cappuccino',
                'price' => 22000,
                'category' => 'Kopi',
                'description' => 'Kopi susu creamy dengan foam lembut.',
                'image' => 'images/fnb/cappuccino.png',
            ],
            [
                'name' => 'Iced Coffee',
                'price' => 20000,
                'category' => 'Kopi',
                'description' => 'Kopi dingin segar untuk sesi bermain panjang.',
                'image' => 'images/fnb/iced-coffee.png',
            ],
            [
                'name' => 'Nescafe Latte',
                'price' => 13000,
                'category' => 'Kopi',
                'description' => 'Kopi latte kemasan yang praktis dan dingin.',
                'image' => 'images/fnb/Nescafe Latte.jpg',
            ],
            [
                'name' => 'Good Day Cappuccino',
                'price' => 10000,
                'category' => 'Kopi',
                'description' => 'Kopi instan creamy dengan rasa cappuccino.',
                'image' => 'images/fnb/Good Day.jpeg',
            ],
            [
                'name' => 'Good Day Freeze',
                'price' => 10000,
                'category' => 'Kopi',
                'description' => 'Kopi dingin manis untuk teman nongkrong.',
                'image' => 'images/fnb/Good day.jpg',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name']],
                $menu + ['status' => 'available']
            );
        }
    }
}
