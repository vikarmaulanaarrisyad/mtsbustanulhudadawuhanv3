<?php

namespace App\Imports\Emis;

use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Skip empty rows
            if (!isset($row['nama_lengkap'])) {
                continue;
            }

            // Using NIK or NUPTK as unique identifier
            $nik = $row['nik'] ?? null;
            $nuptk = $row['nuptk'] ?? null;

            if (!$nik && !$nuptk) {
                continue; // Skip if both are empty
            }

            // Find or Create Teacher
            $teacher = null;
            if ($nik) {
                $teacher = Teacher::where('nik', $nik)->first();
            }
            if (!$teacher && $nuptk) {
                $teacher = Teacher::where('nuptk', $nuptk)->first();
            }

            if (!$teacher) {
                $teacher = new Teacher();
            }

            $teacher->name = $row['nama_lengkap'];
            $teacher->nip = $row['nip'] ?? null;
            $teacher->nuptk = $nuptk;
            $teacher->npk = $row['npk'] ?? null;
            $teacher->nik = $nik;
            $teacher->birth_place = $row['tempat_lahir'] ?? null;
            
            if (isset($row['tanggal_lahir'])) {
                try {
                    $teacher->birth_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $teacher->birth_date = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            }

            $teacher->gender = $row['jenis_kelamin'] ?? null;
            $teacher->employment_status = $row['status_pegawai'] ?? null;
            $teacher->religion = $row['agama'] ?? null;
            $teacher->address = $row['alamat'] ?? null;
            $teacher->phone = $row['no_hpwa'] ?? null;
            
            $teacher->save();
        }
    }
}
