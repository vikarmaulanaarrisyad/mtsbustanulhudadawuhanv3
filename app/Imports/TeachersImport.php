<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class TeachersImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    /**
     * Kita buat rule 'nama' menjadi nullable agar tidak error saat validasi jika barisnya kosong.
     * Kita akan memfilternya di method model().
     */
    public function rules(): array
    {
        return [
            'nama' => 'nullable|max:150', // Diubah dari required ke nullable
            'nik'  => 'nullable|max:16',
            'nip'  => 'nullable|max:30',
            'jenis_kelamin' => 'nullable|in:L,P,l,p',
            'email' => 'nullable|email|max:150',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'jenis_kelamin.in' => 'Jenis Kelamin harus diisi L atau P.',
            'email.email'   => 'Format email tidak valid.',
        ];
    }

    public function model(array $row)
    {
        // Cleaning data
        $row = array_map(function ($value) {
            return is_string($value) ? trim($value) : $value;
        }, $row);

        // --- FILTER BARIS KOSONG ---
        // Jika kolom Nama kosong, maka baris ini diabaikan sepenuhnya (skip)
        if (empty($row['nama'])) {
            return null;
        }

        DB::beginTransaction();

        try {
            $searchCriteria = [];
            if (!empty($row['nik'])) {
                $searchCriteria = ['nik' => (string)$row['nik']];
            } elseif (!empty($row['nip'])) {
                $searchCriteria = ['nip' => (string)$row['nip']];
            } else {
                $searchCriteria = ['name' => $row['nama']];
            }

            $userId = null;

            if (!empty($row['email']) || !empty($row['username'])) {
                $email    = $row['email'] ?? ($row['username'] . '@example.com');
                $username = $row['username'] ?? explode('@', $email)[0];

                $user = User::where('email', $email)
                    ->orWhere('username', $username)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name'     => $row['nama'],
                        'email'    => $email,
                        'username' => $username,
                        'password' => Hash::make($row['password'] ?? 'password'),
                    ]);

                    $role = Role::firstOrCreate(['name' => 'Guru']);
                    $user->assignRole($role);
                }
                $userId = $user->id;
            }

            $certificationStatus = null;
            if (isset($row['sudah_sertifikasi'])) {
                $val = strtolower((string)$row['sudah_sertifikasi']);
                $certificationStatus = in_array($val, ['1', 'ya', 'yes', 'true']) ? 1 : 0;
            }

            $baseSalary = null;
            if (!empty($row['gaji_pokok'])) {
                $baseSalary = (float) preg_replace('/[^0-9]/', '', (string)$row['gaji_pokok']);
            }

            $teacher = Teacher::updateOrCreate($searchCriteria, [
                'user_id'              => $userId,
                'name'                 => $row['nama'],
                'nik'                  => !empty($row['nik']) ? (string)$row['nik'] : null,
                'nip'                  => !empty($row['nip']) ? (string)$row['nip'] : null,
                'nuptk'                => !empty($row['nuptk']) ? (string)$row['nuptk'] : null,
                'gender'               => isset($row['jenis_kelamin']) ? strtoupper($row['jenis_kelamin']) : null,
                'place_of_birth'       => $row['tempat_lahir'] ?? null,
                'date_of_birth'        => !empty($row['tanggal_lahir']) ? $row['tanggal_lahir'] : null,
                'address'              => $row['alamat'] ?? null,
                'phone'                => !empty($row['no_hp']) ? (string)$row['no_hp'] : null,
                'employment_status'    => $row['status_kepegawaian'] ?? null,
                'position'             => $row['jabatan'] ?? null,
                'additional_duty'      => $row['tugas_tambahan'] ?? null,
                'rank'                 => $row['pangkat_golongan'] ?? null,
                'start_date'           => !empty($row['tmt']) ? $row['tmt'] : null,
                'certification_status' => $certificationStatus,
                'education'            => $row['pendidikan_terakhir'] ?? null,
                'major'                => $row['jurusan'] ?? null,
                'university'           => $row['universitas'] ?? null,
                'bank_name'            => $row['nama_bank'] ?? null,
                'bank_account_number'  => !empty($row['no_rekening']) ? (string)$row['no_rekening'] : null,
                'bank_account_name'    => $row['nama_pemilik_rekening'] ?? null,
                'base_salary'          => $baseSalary,
            ]);

            DB::commit();
            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
