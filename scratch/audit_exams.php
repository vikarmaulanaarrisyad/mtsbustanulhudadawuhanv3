<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CbtExam;
use Carbon\Carbon;

echo "--- CBT Exam Audit ---\n";
$now = Carbon::now();
echo "Current Server Time: " . $now->toDateTimeString() . " (Time: " . $now->toTimeString() . ")\n";

$exams = CbtExam::all();
foreach ($exams as $e) {
    echo "Exam: {$e->name}\n";
    echo "  Date: {$e->exam_date}\n";
    echo "  Start: {$e->start_time} | End: {$e->end_time}\n";
    echo "  Token: {$e->token}\n";
    echo "  Is Active: " . ($e->is_active ? 'YES' : 'NO') . "\n";
    
    if ($now->toTimeString() < $e->start_time) {
        echo "  STATUS: TOO EARLY\n";
    } elseif ($now->toTimeString() > $e->end_time) {
        echo "  STATUS: TOO LATE\n";
    } else {
        echo "  STATUS: OPEN\n";
    }
}
