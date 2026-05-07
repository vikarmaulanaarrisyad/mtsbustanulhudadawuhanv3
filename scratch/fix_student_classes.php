<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ClassGroup;
use App\Models\Student;

echo "--- Class Groups ---\n";
foreach (ClassGroup::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->group_name}\n";
}

$firstClass = ClassGroup::first();
if ($firstClass) {
    echo "\nFound a valid class: ID {$firstClass->id} ({$firstClass->group_name})\n";
    echo "Updating students with ID 1 to ID {$firstClass->id}...\n";
    $affected = Student::where('student_class_group_id', '1')->update(['student_class_group_id' => $firstClass->id]);
    echo "Fixed $affected students.\n";
} else {
    echo "\nNo classes found in DB! Creating a default class...\n";
    $newClass = ClassGroup::create([
        'class_group' => 'VII',
        'sub_class_group' => 'A',
        'group_name' => 'VII A',
        'academic_year_id' => \App\Models\AcademicYear::where('is_active', true)->first()->id ?? 1
    ]);
    echo "Created Class: ID {$newClass->id}\n";
    Student::where('student_class_group_id', '1')->update(['student_class_group_id' => $newClass->id]);
}
