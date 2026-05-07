<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\CbtExam;
use App\Models\Student;
use Carbon\Carbon;

$studentUser = \App\Models\User::find(11);
if ($studentUser) {
    echo "User ID 11: " . $studentUser->name . "\n";
    $student = Student::where('user_id', 11)->first();
    if ($student) {
        echo "Student ID: " . $student->id . " | ClassID: '" . $student->student_class_group_id . "'\n";
    }
}

$today = Carbon::today()->toDateString();
echo "Today: $today\n";

$allExams = CbtExam::all();
echo "All Exams in DB (" . $allExams->count() . "):\n";
foreach ($allExams as $e) {
    echo "- Name: " . $e->name . " | Date: " . $e->exam_date . " | Active: " . ($e->is_active ? '1' : '0') . "\n";
}
echo "-------------------\n";

$exams = CbtExam::where('exam_date', $today)->get();
echo "Exams for today (" . $exams->count() . "):\n";
foreach ($exams as $e) {
    $classes = $e->classes->pluck('class_group_id')->toArray();
    echo "- Name: " . $e->name . "\n";
    echo "  ID: " . $e->id . "\n";
    echo "  Active: " . ($e->is_active ? 'Yes' : 'No') . "\n";
    echo "  Classes: " . implode(',', $classes) . "\n";
}

$students = Student::take(5)->get();
echo "\nSample Students:\n";
foreach ($students as $s) {
    echo "- " . $s->nama_lengkap . " (ClassID: " . $s->class_group_id . ", UserID: " . $s->user_id . ")\n";
}
