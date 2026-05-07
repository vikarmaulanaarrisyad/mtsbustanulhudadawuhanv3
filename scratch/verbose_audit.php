<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\ClassGroup;

$students = Student::with('classGroup')->limit(10)->get();
echo "--- Student Data Audit ---\n";
foreach ($students as $s) {
    echo "Student: {$s->nama_lengkap}\n";
    echo "  Class ID in Student Table: '{$s->student_class_group_id}'\n";
    if ($s->classGroup) {
        echo "  Relation classGroup: FOUND\n";
        echo "  Class Name: '{$s->classGroup->class_group}' '{$s->classGroup->sub_class_group}'\n";
    } else {
        echo "  Relation classGroup: NOT FOUND (Orphaned ID)\n";
        $manual = ClassGroup::find($s->student_class_group_id);
        if ($manual) {
            echo "  Manual find(ID): FOUND (Check relationship logic)\n";
        } else {
            echo "  Manual find(ID): NOT FOUND (ID does not exist in class_groups table)\n";
        }
    }
    echo "--------------------------\n";
}

echo "\n--- All Classes in DB ---\n";
foreach (ClassGroup::all() as $c) {
    echo "ID: {$c->id} | Name: '{$c->class_group}' '{$c->sub_class_group}'\n";
}
