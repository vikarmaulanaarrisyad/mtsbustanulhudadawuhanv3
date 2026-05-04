<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\StudyPeriod;

echo "--- LIST ALL STUDY PERIODS (JAM PELAJARAN) ---\n";
$sps = StudyPeriod::orderBy('period_number')->get();
foreach ($sps as $sp) {
    echo "ID: " . $sp->id . " | Period: " . $sp->period_number . " | Time: " . $sp->start_time . " - " . $sp->end_time . " | Break: " . ($sp->is_break ? 'YES' : 'NO') . "\n";
}
