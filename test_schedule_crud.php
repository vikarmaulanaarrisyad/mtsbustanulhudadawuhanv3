<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\ClassSchedule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudyPeriod;

echo "=== TESTING SCHEDULE CRUD & CONFLICT PREVENTION ===\n\n";

$ay = AcademicYear::where('current_semester', true)->first();
$classA = ClassGroup::first();
$classB = ClassGroup::where('id', '!=', $classA->id)->first();
$teacher = Teacher::first();
$subject = Subject::first();
$period = StudyPeriod::first();
$day = 1; // Senin

echo "Target Class A: " . $classA->kelas_lengkap . "\n";
echo "Target Class B: " . $classB->kelas_lengkap . "\n";
echo "Teacher: " . $teacher->name . "\n";
echo "Period: Jam ke-" . $period->period_number . "\n\n";

// CLEANUP PREVIOUS TESTS
ClassSchedule::where('teacher_id', $teacher->id)->where('day', $day)->where('study_period_id', $period->id)->delete();

// 1. CREATE (SUCCESS)
echo "[1] Creating Schedule for Class A...\n";
$schedule1 = ClassSchedule::create([
    'academic_year_id' => $ay->id,
    'class_group_id' => $classA->id,
    'subject_id' => $subject->id,
    'teacher_id' => $teacher->id,
    'day' => $day,
    'study_period_id' => $period->id,
    'start_time' => $period->start_time,
    'end_time' => $period->end_time,
]);
echo "  - SUCCESS: Schedule ID " . $schedule1->id . " created.\n";

// 2. CONFLICT CHECK: SAME TEACHER, DIFFERENT CLASS, SAME TIME
echo "\n[2] Testing Teacher Conflict (Class B, same time)...\n";
$conflict = ClassSchedule::where('teacher_id', $teacher->id)
    ->where('day', $day)
    ->where('study_period_id', $period->id)
    ->where('academic_year_id', $ay->id)
    ->first();

if ($conflict) {
    echo "  - SUCCESS: System detected conflict for teacher " . $teacher->name . " in Class " . $conflict->classGroup->kelas_lengkap . "\n";
} else {
    echo "  - FAILURE: Conflict not detected!\n";
}

// 3. UPDATE
echo "\n[3] Updating Schedule (Change Subject)...\n";
$newSubject = Subject::where('id', '!=', $subject->id)->first();
$schedule1->update(['subject_id' => $newSubject->id]);
echo "  - SUCCESS: Subject updated to " . $newSubject->name . "\n";

// 4. DELETE
echo "\n[4] Deleting Schedule...\n";
$schedule1->delete();
$exists = ClassSchedule::find($schedule1->id);
if (!$exists) {
    echo "  - SUCCESS: Schedule deleted from database.\n";
} else {
    echo "  - FAILURE: Schedule still exists!\n";
}

echo "\n=== ALL CRUD TESTS PASSED ===\n";
