<?php

namespace App\Imports\Emis;

use App\Models\Student;
use App\Models\StudentProfile;
use App\Models\StudentParent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip empty rows
            if (!isset($row['nama_lengkap'])) {
                continue;
            }

            // Using NISN or NIK as the unique identifier for Upsert
            $nisn = $row['nisn'] ?? null;
            $nik = $row['nik'] ?? null;
            
            if (!$nisn && !$nik) {
                continue; // Cannot process without unique key
            }

            // Find or Create Student
            $student = Student::where('nisn', $nisn)->first();
            
            if (!$student && $nik) {
                // Try finding by NIK in profile
                $profile = StudentProfile::where('nik', $nik)->first();
                if ($profile) {
                    $student = $profile->student;
                }
            }

            if (!$student) {
                $student = new Student();
                $student->nisn = $nisn;
            }

            $student->name = $row['nama_lengkap'];
            $student->gender = $row['jenis_kelamin'] ?? null;
            $student->save();

            // Profile Data
            $profile = StudentProfile::firstOrNew(['student_id' => $student->id]);
            $profile->nik = $nik;
            $profile->birth_place = $row['tempat_lahir'] ?? null;
            if (isset($row['tanggal_lahir'])) {
                try {
                    $profile->birth_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $profile->birth_date = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            }
            $profile->religion = $row['agama'] ?? null;
            $profile->phone = $row['no_hpwa'] ?? null;
            $profile->address = $row['alamat'] ?? null;
            $profile->save();

            // Parent Data
            $parents = StudentParent::firstOrNew(['student_id' => $student->id]);
            $parents->mother_name = $row['nama_ibu_kandung'] ?? null;
            $parents->father_name = $row['nama_ayah_kandung'] ?? null;
            $parents->save();
        }
    }
}
