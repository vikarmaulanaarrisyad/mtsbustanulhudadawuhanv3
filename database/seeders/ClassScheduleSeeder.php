<?php

namespace Database\Seeders;

use App\Models\ClassSchedule;
use App\Models\ClassGroup;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\StudyPeriod;
use Illuminate\Database\Seeder;

class ClassScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = AcademicYear::where('current_semester', 1)->first();
        if (!$academicYear) {
             $academicYear = AcademicYear::first();
        }
        
        if (!$academicYear) return;

        $classes = ClassGroup::where('academic_year_id', $academicYear->id)->get();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $periods = StudyPeriod::where('is_break', false)->get();

        if ($classes->isEmpty() || $subjects->isEmpty() || $teachers->isEmpty() || $periods->isEmpty()) {
            return;
        }

        foreach ($classes as $class) {
            // Seed schedules for Monday (1) to Saturday (6)
            for ($day = 1; $day <= 6; $day++) {
                foreach ($periods as $period) {
                    $subject = $subjects->random();
                    $teacher = $teachers->random();

                    ClassSchedule::updateOrCreate(
                        [
                            'class_group_id' => $class->id,
                            'academic_year_id' => $academicYear->id,
                            'day' => $day,
                            'study_period_id' => $period->id,
                        ],
                        [
                            'subject_id' => $subject->id,
                            'teacher_id' => $teacher->id,
                            'start_time' => $period->start_time,
                            'end_time' => $period->end_time,
                        ]
                    );
                }
            }
        }
    }
}
