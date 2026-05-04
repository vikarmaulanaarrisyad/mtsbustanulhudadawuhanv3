<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\AcademicYear;

echo "--- LIST ALL ACADEMIC YEARS ---\n";
$ays = AcademicYear::all();
foreach ($ays as $ay) {
    echo "ID: " . $ay->id . " | Year: " . $ay->academic_year . " | Semester: " . $ay->semester_id . " | Current: " . ($ay->current_semester ? 'YES' : 'NO') . "\n";
}
