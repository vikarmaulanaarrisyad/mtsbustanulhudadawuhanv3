<?php
// Script to generate PWA icons from public/img/logo.png
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

$source = public_path('img/logo.png');
$iconDir = storage_path('app/public/pwa/icons');

if (!file_exists($iconDir)) {
    mkdir($iconDir, 0755, true);
}

if (!file_exists($source)) {
    echo "Source logo not found at $source\n";
    exit(1);
}

$image = imagecreatefromstring(file_get_contents($source));
$sizes = [
    'icon-192x192.png'          => 192,
    'icon-512x512.png'          => 512,
    'icon-192x192-maskable.png' => 192,
];

foreach ($sizes as $filename => $size) {
    $resized = imagecreatetruecolor($size, $size);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
    imagefill($resized, 0, 0, $transparent);

    $srcW = imagesx($image);
    $srcH = imagesy($image);
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $size, $size, $srcW, $srcH);
    imagepng($resized, $iconDir . '/' . $filename, 9);
    imagedestroy($resized);
    echo "Generated: $filename\n";
}
imagedestroy($image);
echo "PWA Icons generated successfully.\n";
