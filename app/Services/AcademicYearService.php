<?php

namespace App\Services;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcademicYearService
{
    /*
    |--------------------------------------------------------------------------
    | CREATE ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */
    public function create(array $data): AcademicYear
    {
        return DB::transaction(function () use ($data) {

            $exists = AcademicYear::where('academic_year', $data['academic_year'])
                ->where('semester_id', $data['semester_id'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'academic_year' => 'Kombinasi tahun ajaran dan semester sudah ada.'
                ]);
            }

            return AcademicYear::create([
                'academic_year'      => $data['academic_year'],
                'semester_id'        => $data['semester_id'],
                'current_semester'   => 0,
                'admission_semester' => 0,
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SET CURRENT SEMESTER
    |--------------------------------------------------------------------------
    */
    public function setCurrentSemester(int $id): AcademicYear
    {
        return DB::transaction(function () use ($id) {

            $academicYear = AcademicYear::findOrFail($id);

            if ($academicYear->current_semester) {
                throw ValidationException::withMessages([
                    'current_semester' => 'Semester ini sudah aktif.'
                ]);
            }

            // Nonaktifkan semua
            AcademicYear::where('current_semester', 1)
                ->update([
                    'current_semester' => 0,
                    'admission_semester' => 0
                ]);

            $academicYear->update([
                'current_semester' => 1
            ]);

            return $academicYear;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SET ADMISSION SEMESTER (PPDB)
    |--------------------------------------------------------------------------
    */
    public function setAdmissionSemester(int $id): AcademicYear
    {
        return DB::transaction(function () use ($id) {

            $academicYear = AcademicYear::findOrFail($id);

            if ($academicYear->semester_id == 2) {
                throw ValidationException::withMessages([
                    'semester' => 'Hanya semester ganjil yang bisa jadi semester PPDB.'
                ]);
            }

            if (!$academicYear->current_semester) {
                throw ValidationException::withMessages([
                    'semester' => 'Hanya semester aktif yang bisa jadi semester PPDB.'
                ]);
            }

            AcademicYear::where('admission_semester', 1)
                ->update(['admission_semester' => 0]);

            $academicYear->update([
                'admission_semester' => 1
            ]);

            return $academicYear;
        });
    }
}
