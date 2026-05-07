<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

echo "--- Student Audit ---\n";
$total = Student::count();
$withoutClass = Student::whereNull('student_class_group_id')->orWhere('student_class_group_id', '')->count();

echo "Total Students: $total\n";
echo "Students without Class: $withoutClass\n";

if ($withoutClass > 0) {
    echo "\nSample students without class:\n";
    $samples = Student::whereNull('student_class_group_id')->orWhere('student_class_group_id', '')->limit(5)->get();
    foreach ($samples as $s) {
        echo "- ID: {$s->id} | Name: {$s->nama_lengkap} | NISN: {$s->nisn}\n";
    }
    
    // Auto-fix: Link them to a default class if requested? 
    // No, I should wait for instructions or check if there are classes available.
}

echo "\n--- Sample Student Accounts ---\n";
$students = Student::limit(5)->get();
foreach ($students as $s) {
    $class = \App\Models\ClassGroup::find($s->student_class_group_id);
    echo "- Name: {$s->nama_lengkap}\n";
    echo "  Raw Class ID: '{$s->student_class_group_id}'\n";
    echo "  Class Name: " . ($class->group_name ?? 'NOT FOUND IN DB') . "\n";
    echo "  Status: " . ($s->is_active ? 'Active' : 'Inactive') . "\n";
}
