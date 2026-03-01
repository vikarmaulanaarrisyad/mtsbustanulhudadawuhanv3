<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\AdmissionPhase;
use App\Models\StudentAdmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmissionPhaseService
{
    public function getCurrentAcademicYear()
    {
        return AcademicYear::with('semester')
            ->where('current_semester', 1)
            ->first();
    }

    public function getAdmissionSemesterYear()
    {
        return AcademicYear::where('current_semester', 1)
            ->where('admission_semester', 1)
            ->first();
    }

    public function getStudentAdmission($academicYearId)
    {
        return StudentAdmission::where('academic_year_id', $academicYearId)->first();
    }

    public function validate(array $data, $studentAdmission)
    {
        return Validator::make($data, [
            'phase_name' => 'required',
            'phase_start_date' => 'required|date|after_or_equal:' . $studentAdmission->admission_start_date,
            'phase_end_date' => 'required|date|after:phase_start_date',
        ]);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $academicYear = $this->getAdmissionSemesterYear();

            if (!$academicYear) {
                throw new \Exception('Academic year not found.');
            }

            $exists = AdmissionPhase::where('academic_year_id', $academicYear->id)->exists();

            if ($exists) {
                throw new \Exception('Admission phase for this academic year already exists.');
            }

            return AdmissionPhase::create([
                'academic_year_id' => $academicYear->id,
                'phase_name'       => $data['phase_name'],
                'phase_start_date' => $data['phase_start_date'],
                'phase_end_date'   => $data['phase_end_date'],
            ]);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $admissionPhase = AdmissionPhase::findOrFail($id);

            $admissionPhase->update([
                'phase_name'       => $data['phase_name'],
                'phase_start_date' => $data['phase_start_date'],
                'phase_end_date'   => $data['phase_end_date'],
            ]);

            return $admissionPhase;
        });
    }

    public function destroy($id)
    {
        $phase = AdmissionPhase::findOrFail($id);
        $phase->delete();

        return true;
    }
}
