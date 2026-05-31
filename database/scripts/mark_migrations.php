<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$max = Illuminate\Support\Facades\DB::table('migrations')->max('batch') ?: 0;
$batch = $max + 1;

$migrations = [
    '2024_01_01_000000_create_paniers_table',
    '2026_04_08_165655_create_produits_table',
];

foreach ($migrations as $m) {
    if (! Illuminate\Support\Facades\DB::table('migrations')->where('migration', $m)->exists()) {
        Illuminate\Support\Facades\DB::table('migrations')->insert(['migration' => $m, 'batch' => $batch]);
        echo "Inserted: {$m}\n";
    } else {
        echo "Exists: {$m}\n";
    }
}
