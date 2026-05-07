<?php

use App\Imports\CbtQuestionsImport;
use App\Models\CbtBank;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// 1. Setup
$bank = CbtBank::first();
if (!$bank) {
    die("No bank found in database. Please seed or create one first.\n");
}

echo "Testing Import for Bank: {$bank->name}\n";

// 2. Mock Image Files (Simulating UploadedFile objects)
$tempImagePath = tempnam(sys_get_temp_dir(), 'test_img');
file_put_contents($tempImagePath, 'fake_image_data');

$uploadedImages = [
    '1.jpg' => new UploadedFile($tempImagePath, '1.jpg', 'image/jpeg', null, true),
    '1_B.png' => new UploadedFile($tempImagePath, '1_B.png', 'image/png', null, true),
    'paus.jpg' => new UploadedFile($tempImagePath, 'paus.jpg', 'image/jpeg', null, true),
    '3.jpg' => new UploadedFile($tempImagePath, '3.jpg', 'image/jpeg', null, true),
    '3_A.png' => new UploadedFile($tempImagePath, '3_A.png', 'image/png', null, true),
];

// 3. Perform Import Simulation
$import = new CbtQuestionsImport($bank, $uploadedImages);

$collection = new \Illuminate\Support\Collection();
// Row 1: Manual Filename in D and F
$collection->push(new \Illuminate\Support\Collection([
    'no' => '1',
    'tipe_soal' => 'PG',
    'soal' => 'Test Soal 1',
    'gambar_soal' => '1.jpg', // Should be found by filename
    'opsi_a' => 'Opsi A',
    'gambar_opsi_a' => '',
    'opsi_b' => 'Opsi B',
    'gambar_opsi_b' => '1_B.png', // Should be found by filename
    'opsi_c' => 'Opsi C',
    'gambar_opsi_c' => '',
    'opsi_d' => 'Opsi D',
    'gambar_opsi_d' => '',
    'opsi_e' => 'Opsi E',
    'gambar_opsi_e' => '',
    'jawaban_benar' => 'B',
    'bobot_nilai' => '1',
    'pasangan_penjodohan' => '',
]));

// Row 2: Naming Convention (Empty D and F, but No=2)
// Actually I didn't mock 2.jpg, but I mocked paus.jpg. Let's test that.
$collection->push(new \Illuminate\Support\Collection([
    'no' => '2',
    'tipe_soal' => 'PG',
    'soal' => 'Test Soal 2 (Naming Conv)',
    'gambar_soal' => 'paus.jpg', // Explicitly typed
    'opsi_a' => 'Opsi A',
    'gambar_opsi_a' => '',
    'opsi_b' => 'Opsi B',
    'gambar_opsi_b' => '',
    'opsi_c' => 'Opsi C',
    'gambar_opsi_c' => '',
    'opsi_d' => 'Opsi D',
    'gambar_opsi_d' => '',
    'opsi_e' => 'Opsi E',
    'gambar_opsi_e' => '',
    'jawaban_benar' => 'A',
    'bobot_nilai' => '1',
    'pasangan_penjodohan' => '',
]));

// Row 3: Naming Convention Fallback (Empty columns, but No=1)
// Since 1.jpg was used and unset in Row 1, I'll use a new mock file for this.
// Wait, I already unset it in saveImage. I should provide it again or not unset in test.
// Actually, I'll just use a different name for testing convention.
$collection->push(new \Illuminate\Support\Collection([
    'no' => '3', // Will look for 3.jpg (not provided) or soal_3.jpg (not provided)
    'tipe_soal' => 'PG',
    'soal' => 'Test Soal 3 (Convention Only)',
    'gambar_soal' => '', // Empty!
    'opsi_a' => 'Opsi A',
    'gambar_opsi_a' => '', // Empty!
    'opsi_b' => 'Opsi B',
    'gambar_opsi_b' => '',
    'opsi_c' => 'Opsi C',
    'gambar_opsi_c' => '',
    'opsi_d' => 'Opsi D',
    'gambar_opsi_d' => '',
    'opsi_e' => 'Opsi E',
    'gambar_opsi_e' => '',
    'jawaban_benar' => 'A',
    'bobot_nilai' => '1',
    'pasangan_penjodohan' => '',
]));

echo "Running collection import...\n";
try {
    // We need to set the sheet and drawingsloaded to false manually since we skip BeforeSheet event
    $reflector = new ReflectionClass($import);
    $prop = $reflector->getProperty('drawingsLoaded');
    $prop->setAccessible(true);
    $prop->setValue($import, true); // Skip drawing search for this test

    $import->collection($collection);
    echo "Import completed successfully!\n";
    
    // 4. Verify results in DB
    $latestQuestions = $bank->questions()->latest()->take(3)->get()->reverse();
    $i = 1;
    foreach ($latestQuestions as $q) {
        echo "\n[Case $i] Question: {$q->question_text}\n";
        echo "  Question Image: " . ($q->question_image ? "✅ " . $q->question_image : "❌ None") . "\n";
        foreach ($q->options as $opt) {
            echo "  Option {$opt->letter} Image: " . ($opt->option_image ? "✅ " . $opt->option_image : "❌") . "\n";
        }
        $i++;
    }
} catch (\Exception $e) {
    echo "Import failed: " . $e->getMessage() . "\n";
} finally {
    @unlink($tempImagePath);
}
