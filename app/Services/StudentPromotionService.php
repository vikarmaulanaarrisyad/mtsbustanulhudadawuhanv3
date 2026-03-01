<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Support\Facades\DB;

class StudentPromotionService
{
    /*
    |--------------------------------------------------------------------------
    | PROMOTE STUDENT (Naik Kelas)
    |--------------------------------------------------------------------------
    */
    public function promote(Student $student, $newAcademicYearId, $newClassGroupId)
    {
        DB::transaction(function () use ($student, $newAcademicYearId, $newClassGroupId) {

            StudentHistory::create([
                'student_id'      => $student->id,
                'academic_year_id' => $newAcademicYearId,
                'class_group_id'  => $newClassGroupId,
                'status'          => 'promoted',
                'entry_date'      => now(),
            ]);

            $student->update([
                'academic_year_id'       => $newAcademicYearId,
                'student_class_group_id' => $newClassGroupId,
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RETAIN STUDENT (Tidak Naik Kelas)
    |--------------------------------------------------------------------------
    */
    public function retain(Student $student, $academicYearId, $sameClassGroupId)
    {
        DB::transaction(function () use ($student, $academicYearId, $sameClassGroupId) {

            StudentHistory::create([
                'student_id'      => $student->id,
                'academic_year_id' => $academicYearId,
                'class_group_id'  => $sameClassGroupId,
                'status'          => 'retained',
                'entry_date'      => now(),
            ]);

            $student->update([
                'academic_year_id' => $academicYearId,
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSFER CLASS (Pindah Kelas)
    |--------------------------------------------------------------------------
    */
    public function transferClass(Student $student, $newClassGroupId)
    {
        DB::transaction(function () use ($student, $newClassGroupId) {

            StudentHistory::create([
                'student_id'      => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id'  => $newClassGroupId,
                'status'          => 'transferred',
                'entry_date'      => now(),
            ]);

            $student->update([
                'student_class_group_id' => $newClassGroupId,
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GRADUATE STUDENT (Lulus)
    |--------------------------------------------------------------------------
    */
    public function graduate(Student $student)
    {
        DB::transaction(function () use ($student) {

            StudentHistory::create([
                'student_id'      => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id'  => $student->student_class_group_id,
                'status'          => 'graduated',
                'exit_date'       => now(),
            ]);

            $student->update([
                'student_status_id' => 3, // misal ID status = Graduate
                'is_active'         => false,
            ]);
        });
    }
}
