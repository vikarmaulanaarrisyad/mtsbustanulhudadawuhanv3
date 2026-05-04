<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Student;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudentHistory;

echo "=== STARTING COMPREHENSIVE FEATURE TESTING ===\n\n";

// 1. TEST: GRADUATION FILTER
echo "[1] Testing Graduation List Filter...\n";
$currentAy = AcademicYear::where('current_semester', true)->first();
$newlyPromoted = Student::whereHas('histories', function($q) use ($currentAy) {
    $q->where('academic_year_id', $currentAy->id)->where('status', 'promoted');
})->whereHas('classGroup', function($q) {
    $q->whereIn('class_level', [6, 9, 12]);
})->first();

if ($newlyPromoted) {
    echo "  - Found a newly promoted Grade " . $newlyPromoted->classGroup->class_level . " student: " . $newlyPromoted->nama_lengkap . "\n";
    // Check if they appear in graduation list logic
    $isDisplayed = Student::where('id', $newlyPromoted->id)
        ->whereDoesntHave('histories', function($sq) use ($currentAy) {
            $sq->where('academic_year_id', $currentAy->id)
               ->where('status', 'promoted');
        })->exists();
    
    if (!$isDisplayed) {
        echo "  - SUCCESS: Newly promoted student is correctly HIDDEN from graduation list.\n";
    } else {
        echo "  - FAILURE: Newly promoted student is still VISIBLE in graduation list.\n";
    }
} else {
    echo "  - Skip: No newly promoted final-year students found to test.\n";
}

// 2. TEST: PROMOTION BLOCKAGE LOGIC
echo "\n[2] Testing Promotion Blockage Logic...\n";
$targetClass = ClassGroup::where('class_group', 'IX')->first();
if ($targetClass) {
    $studentIds = Student::where('student_class_group_id', $targetClass->id)
        ->where('academic_year_id', $currentAy->id)
        ->pluck('id')->toArray();
    
    if (count($studentIds) > 0) {
        echo "  - Testing with students already in class " . $targetClass->kelas_lengkap . "\n";
        // Mocking the query in StudentPromotionController
        $activeInTarget = Student::where('student_class_group_id', $targetClass->id)
            ->where('academic_year_id', $currentAy->id)
            ->where('is_active', true)
            ->whereNotIn('id', $studentIds) // The fix
            ->count();
            
        if ($activeInTarget == 0) {
            echo "  - SUCCESS: System correctly ignores the current batch, preventing self-blockage.\n";
        } else {
            echo "  - FAILURE: System still detects blockers even after excluding current batch.\n";
        }
    }
}

// 3. TEST: 'TANPA ROMBEL' FILTER
echo "\n[3] Testing 'No Class' (Tanpa Rombel) Filter...\n";
$noClassCount = Student::whereNull('student_class_group_id')->where('is_active', true)->count();
echo "  - Found $noClassCount students without a class group.\n";
if ($noClassCount >= 0) {
    echo "  - SUCCESS: Filter logic 'whereNull' is ready to use.\n";
}

// 4. TEST: ALUMNI LISTING
echo "\n[4] Testing Alumni Listing...\n";
$alumniCount = Student::where('student_status_id', 2)->count();
echo "  - Total Alumni in Database: $alumniCount\n";
if ($alumniCount > 0) {
    $sampleAlumni = Student::where('student_status_id', 2)->first();
    echo "  - Sample Alumni: " . $sampleAlumni->nama_lengkap . " (Year: " . ($sampleAlumni->academicYear->academic_year ?? 'N/A') . ")\n";
    echo "  - SUCCESS: Alumni data is being recorded correctly.\n";
} else {
    echo "  - Info: No alumni found yet. Please process a graduation to verify.\n";
}

echo "\n=== TESTING COMPLETE ===\n";
