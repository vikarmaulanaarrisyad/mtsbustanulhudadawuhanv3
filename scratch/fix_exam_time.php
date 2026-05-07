<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CbtExam;

echo "Fixing Exam Times for 'UJAIN MADRASAH'...\n";
$exam = CbtExam::where('name', 'UJAIN MADRASAH')->first();
if ($exam) {
    $exam->update([
        'start_time' => '00:00:00',
        'end_time' => '23:59:59',
        'is_active' => true,
        'exam_date' => date('Y-m-d')
    ]);
    echo "Exam Updated! It is now OPEN all day today.\n";
    echo "Token: {$exam->token}\n";
} else {
    echo "Exam not found.\n";
}
