<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class StudentImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            $row = array_map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $row);

            // Skip jika NIS kosong
            if (empty($row['nis'])) {
                return null;
            }

            // Cek duplikat NIS
            $exists = Student::where('nis', $row['nis'])->first();
            if ($exists) {
                DB::commit();
                return null; // Skip duplikat
            }

            // Cari relasi berdasarkan nama (opsional)
            $classGroupId = null;
            if (!empty($row['kelas'])) {
                $parts = explode(' ', $row['kelas'], 2);
                $classGroup = \App\Models\ClassGroup::where('class_group', $parts[0] ?? '')
                    ->where('sub_class_group', $parts[1] ?? '')
                    ->first();
                $classGroupId = $classGroup?->id;
            }

            $academicYearId = null;
            if (!empty($row['tahun_pelajaran'])) {
                $ay = \App\Models\AcademicYear::where('academic_year', $row['tahun_pelajaran'])->first();
                $academicYearId = $ay?->id;
            }

            $statusId = null;
            if (!empty($row['status_siswa'])) {
                $status = \App\Models\StudentStatus::where('student_status_name', $row['status_siswa'])->first();
                $statusId = $status?->id;
            }

            // Parse tanggal lahir
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    if (is_numeric($row['tanggal_lahir'])) {
                        // Excel serial date
                        $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
                    } else {
                        $tanggalLahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
                    }
                } catch (\Exception $e) {
                    $tanggalLahir = null;
                }
            }

            $tanggalMasuk = null;
            if (!empty($row['tanggal_masuk'])) {
                try {
                    if (is_numeric($row['tanggal_masuk'])) {
                        $tanggalMasuk = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_masuk'])->format('Y-m-d');
                    } else {
                        $tanggalMasuk = date('Y-m-d', strtotime($row['tanggal_masuk']));
                    }
                } catch (\Exception $e) {
                    $tanggalMasuk = null;
                }
            }

            // Buat student
            $student = Student::create([
                'nis' => $row['nis'],
                'nisn' => $row['nisn'] ?? null,
                'nik' => $row['nik'] ?? null,
                'no_kk' => $row['no_kk'] ?? null,
                'nama_lengkap' => $row['nama_lengkap'],
                'nama_panggilan' => $row['nama_panggilan'] ?? null,
                'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? 'L'),
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => $tanggalLahir,
                'anak_ke' => $row['anak_ke'] ?? null,
                'jumlah_saudara' => $row['jumlah_saudara'] ?? null,
                'academic_year_id' => $academicYearId,
                'student_status_id' => $statusId,
                'student_class_group_id' => $classGroupId,
                'tanggal_masuk' => $tanggalMasuk,
                'asal_sekolah' => $row['asal_sekolah'] ?? null,
                'is_active' => true,
            ]);

            // Buat profile
            $student->profile()->create([
                'alamat' => $row['alamat'] ?? null,
                'rt' => $row['rt'] ?? null,
                'rw' => $row['rw'] ?? null,
                'desa' => $row['desa'] ?? null,
                'kecamatan' => $row['kecamatan'] ?? null,
                'kabupaten' => $row['kabupaten'] ?? null,
                'provinsi' => $row['provinsi'] ?? null,
                'kode_pos' => $row['kode_pos'] ?? null,
                'no_hp' => $row['no_hp'] ?? null,
            ]);

            // Buat parent
            $student->parents()->create([
                'father_name' => $row['nama_ayah'] ?? null,
                'father_phone' => $row['hp_ayah'] ?? null,
                'mother_name' => $row['nama_ibu'] ?? null,
                'mother_phone' => $row['hp_ibu'] ?? null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
