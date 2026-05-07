<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$setting = \App\Models\Setting::first();
if ($setting) {
    $setting->update([
        'pwa_icon' => '/storage/pwa/icons/icon-192x192.png',
        'pwa_version' => '1.0.1'
    ]);
    echo "Settings updated.\n";
} else {
    echo "No settings record found.\n";
}
