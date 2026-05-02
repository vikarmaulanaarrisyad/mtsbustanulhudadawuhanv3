<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Public Path: " . public_path() . "\n";
echo "Storage Path: " . storage_path() . "\n";

$logo = "mail/2RsDGs0SyB5yX2I8HHOqXciLvdXjK8041nKoV05W.png";
$path1 = public_path('storage/' . $logo);
$path2 = storage_path('app/public/' . $logo);

echo "Path 1: $path1 (exists: " . (file_exists($path1) ? 'YES' : 'NO') . ")\n";
echo "Path 2: $path2 (exists: " . (file_exists($path2) ? 'YES' : 'NO') . ")\n";
