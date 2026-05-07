<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$headers = ['No', 'Jenis', 'Soal', 'Gambar Soal', 'Opsi A', 'Gambar A', 'Opsi B', 'Gambar B', 'Opsi C', 'Gambar C', 'Opsi D', 'Gambar D', 'Opsi E', 'Gambar E', 'Kunci', 'Bobot'];
$sheet->fromArray($headers, NULL, 'A1');

// Data
$data = [
    ['1', 'PG', 'Apa ibu kota Indonesia?', '', 'Jakarta', '', 'Bandung', '', 'Surabaya', '', 'Medan', '', 'Makassar', '', 'A', '2'],
    ['2', 'PG', 'Siapa penemu lampu?', 'edison.jpg', 'Tesla', '', 'Edison', '', 'Newton', '', 'Galileo', '', 'Einstein', '', 'B', '2'],
    ['3', 'PG', 'Gambar apakah ini?', 'soal_3.jpg', 'Kucing', '3_A.jpg', 'Anjing', '', 'Kuda', '', 'Sapi', '', 'Kambing', '', 'A', '2'],
];
$sheet->fromArray($data, NULL, 'A2');

$writer = new Xlsx($spreadsheet);
$writer->save('scratch/test_questions.xlsx');
echo "Excel file created: scratch/test_questions.xlsx\n";
