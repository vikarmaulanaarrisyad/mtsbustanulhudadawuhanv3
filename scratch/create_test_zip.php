<?php
$images = ['edison.jpg', 'soal_3.jpg', '3_A.jpg'];
$tempDir = 'scratch/test_images';
if (!file_exists($tempDir)) mkdir($tempDir);

foreach ($images as $img) {
    $im = imagecreatetruecolor(100, 100);
    $text_color = imagecolorallocate($im, 233, 14, 91);
    imagestring($im, 1, 5, 5,  $img, $text_color);
    imagejpeg($im, "$tempDir/$img");
    imagedestroy($im);
}

$zip = new ZipArchive();
$zipName = 'scratch/test_images.zip';
if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    foreach ($images as $img) {
        $zip->addFile("$tempDir/$img", $img);
    }
    $zip->close();
    echo "ZIP file created: $zipName\n";
} else {
    echo "Failed to create ZIP\n";
}
