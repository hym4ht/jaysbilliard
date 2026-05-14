<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \App\Models\Table::all();
foreach ($tables as $table) {
    echo "ID: {$table->id} | Name: {$table->name} | Price: {$table->price_per_hour}\n";
}
