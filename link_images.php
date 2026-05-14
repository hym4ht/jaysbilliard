<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$menuFiles = glob(storage_path('app/public/menus/*.*'));
foreach(\App\Models\Menu::all() as $i => $menu) {
    if (isset($menuFiles[$i])) {
        $menu->image = 'menus/' . basename($menuFiles[$i]);
        $menu->save();
    }
}

$tableFiles = glob(storage_path('app/public/tables/*.*'));
foreach(\App\Models\Table::all() as $j => $table) {
    if (isset($tableFiles[$j])) {
        $table->image = 'tables/' . basename($tableFiles[$j]);
        $table->save();
    }
}
echo "Done!\n";
