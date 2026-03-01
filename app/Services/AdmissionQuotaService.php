<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\AdmissionQuotas;
use App\Models\AdmissionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmissionQuotaService
{
    public function getAdmissionTypes()
    {
        return AdmissionType::all();
    }

    public function getAll()
    {
        return AdmissionQuotas::with(['academicYear', 'admissionTypes'])->get();
    }

    public function find($id)
    {
        return AdmissionQuotas::findOrFail($id);
    }

    public function validateStore(array $data)
    {
        return Validator::make($data, [
            'admission_types_id' => 'required|exists:admission_types,id',
            'quota'              => 'required|integer|min:0',
        ]);
    }

    public function validateUpdate(array $data)
    {
        return Validator::make($data, [
            'admission_types_id' => 'required|exists:admission_types,id',
            'quota'              => 'required|integer|min:0',
        ]);
    }

    protected function getCurrentAdmissionAcademicYear()
    {
        return AcademicYear::where('current_semester', 1)
            ->where('admission_semester', 1)
            ->first();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $academicYear = $this->getCurrentAdmissionAcademicYear();

            if (!$academicYear) {
                throw new \Exception('Academic year not found.');
            }

            $exists = AdmissionQuotas::where('academic_year_id', $academicYear->id)
                ->where('admission_types_id', $data['admission_types_id'])
                ->exists();

            if ($exists) {
                throw new \Exception('Admission quota already exists.');
            }

            return AdmissionQuotas::create([
                'academic_year_id'   => $academicYear->id,
                'admission_types_id' => $data['admission_types_id'],
                'quota'              => $data['quota'],
            ]);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $quota = AdmissionQuotas::findOrFail($id);

            $academicYear = $this->getCurrentAdmissionAcademicYear();

            if (!$academicYear) {
                throw new \Exception('Academic year not found.');
            }

            $exists = AdmissionQuotas::where('academic_year_id', $academicYear->id)
                ->where('admission_types_id', $data['admission_types_id'])
                ->where('id', '!=', $quota->id)
                ->exists();

            if ($exists) {
                throw new \Exception('Admission quota already exists.');
            }

            $quota->update([
                'admission_types_id' => $data['admission_types_id'],
                'quota'              => $data['quota'],
            ]);

            return $quota;
        });
    }

    public function destroy($id)
    {
        $quota = AdmissionQuotas::findOrFail($id);
        $quota->delete();

        return true;
    }
}
