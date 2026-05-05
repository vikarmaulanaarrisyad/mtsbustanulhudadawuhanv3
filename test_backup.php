<?php
require __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$php = PHP_BINARY;
$artisan = base_path('artisan');
exec("\"$php\" \"$artisan\" backup:run --only-db --only-to-disk=local 2>&1", $output, $exitCode);
print_r($output);
echo "Exit: $exitCode\n";
