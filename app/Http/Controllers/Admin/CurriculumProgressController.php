<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ClassGroup;
use App\Models\CurriculumTarget;
use App\Models\TeachingJournal;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurriculumProgressController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        
        $academic_year_id = $request->academic_year_id ?? ($academicYears->first()->id ?? null);
        $semester = $request->semester ?? 1;

        $progressData = [];

        if ($academic_year_id) {
            // Get all subjects
            $subjects = Subject::orderBy('name')->get();

            foreach ($subjects as $subject) {
                // Total targets for this subject & academic year
                $totalTargets = CurriculumTarget::where('subject_id', $subject->id)
                    ->where('academic_year_id', $academic_year_id)
                    ->where('semester', $semester)
                    ->count();

                if ($totalTargets > 0) {
                    $classProgress = [];
                    
                    // Get all classes that have this subject in their schedule
                    $classes = ClassGroup::whereHas('schedules', function($q) use ($subject) {
                        $q->where('subject_id', $subject->id);
                    })->where('academic_year_id', $academic_year_id)->get();

                    foreach ($classes as $class) {
                        // Completed unique targets for this class
                        $completedTargets = TeachingJournal::where('subject_id', $subject->id)
                            ->where('class_group_id', $class->id)
                            ->whereHas('curriculumTarget', function($q) use ($academic_year_id, $semester) {
                                $q->where('academic_year_id', $academic_year_id)
                                  ->where('semester', $semester);
                            })
                            ->distinct('curriculum_target_id')
                            ->count('curriculum_target_id');

                        $percentage = round(($completedTargets / $totalTargets) * 100, 1);
                        
                        $classProgress[] = [
                            'class_name' => $class->kelas_lengkap,
                            'completed' => $completedTargets,
                            'total' => $totalTargets,
                            'percentage' => $percentage
                        ];
                    }

                    if (!empty($classProgress)) {
                        $progressData[] = [
                            'subject_name' => $subject->name,
                            'classes' => $classProgress
                        ];
                    }
                }
            }
        }

        return view('admin.curriculum-progress.index', compact('academicYears', 'progressData', 'academic_year_id', 'semester'));
    }
}
