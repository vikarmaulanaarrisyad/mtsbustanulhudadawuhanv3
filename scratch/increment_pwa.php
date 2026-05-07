<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\Setting::first();
if ($s) {
    $newVersion = time();
    $s->update(['pwa_version' => $newVersion]);
    echo "PWA Version updated to: " . $newVersion . "\n";
} else {
    echo "Setting not found\n";
}
