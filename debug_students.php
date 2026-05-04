<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Student;
use App\Models\ClassGroup;

echo "--- LIST ALL ACTIVE STUDENTS IN GRADE 9 (LEVEL 9) ---\n";
$grade9Students = Student::whereHas('classGroup', function($q) {
    $q->where('class_level', 9);
})->where('is_active', true)->get();

foreach ($grade9Students as $s) {
    echo "ID: " . $s->id . " | Name: " . $s->nama_lengkap . " | Class: " . ($s->classGroup->kelas_lengkap ?? 'NONE') . " | AY: " . $s->academic_year_id . "\n";
}
echo "Total: " . $grade9Students->count() . "\n";
