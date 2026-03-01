<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Support\Facades\DB;

class StudentService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE STUDENT (PPDB / Input Manual)
    |--------------------------------------------------------------------------
    */
    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data) {

            $student = Student::create($data);

            // Auto create history (enrolled)
            StudentHistory::create([
                'student_id'       => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id'   => $student->student_class_group_id,
                'status'           => 'enrolled',
                'entry_date'       => $student->tanggal_masuk ?? now(),
            ]);

            return $student;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STUDENT
    |--------------------------------------------------------------------------
    */
    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {

            $student->update($data);

            return $student;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE STUDENT (Soft Delete)
    |--------------------------------------------------------------------------
    */
    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {

            $student->update([
                'is_active'      => false,
                'tanggal_keluar' => now(),
            ]);

            $student->delete();

            // Create history record
            StudentHistory::create([
                'student_id'       => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id'   => $student->student_class_group_id,
                'status'           => 'dropped_out',
                'exit_date'        => now(),
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE STUDENT
    |--------------------------------------------------------------------------
    */
    public function restore(int $id): Student
    {
        return DB::transaction(function () use ($id) {

            $student = Student::withTrashed()->findOrFail($id);

            $student->restore();

            $student->update([
                'is_active' => true,
                'tanggal_keluar' => null,
            ]);

            return $student;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FORCE DELETE (Permanent)
    |--------------------------------------------------------------------------
    */
    public function forceDelete(int $id): void
    {
        DB::transaction(function () use ($id) {

            $student = Student::withTrashed()->findOrFail($id);

            $student->forceDelete();
        });
    }
}
