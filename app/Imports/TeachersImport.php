<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Spatie\Permission\Models\Role;

class TeachersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            $row = array_map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            }, $row);

            // Skip jika nama kosong
            if (empty($row['nama'])) {
                return null;
            }

            $userId = null;

            // Jika email atau username ada, buat/cari user
            if (!empty($row['email']) || !empty($row['username'])) {
                $email = $row['email'] ?? ($row['username'] . '@example.com');
                $username = $row['username'] ?? explode('@', $email)[0];
                
                $user = User::where('email', $email)
                    ->orWhere('username', $username)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $row['nama'],
                        'email' => $email,
                        'username' => $username,
                        'password' => Hash::make($row['password'] ?? 'password'),
                    ]);

                    // Pastikan role 'Guru' ada
                    $role = Role::firstOrCreate(['name' => 'Guru']);
                    $user->assignRole($role);
                }

                $userId = $user->id;
            }

            // Buat data Guru
            $teacher = Teacher::create([
                'user_id' => $userId,
                'name' => $row['nama'],
                'nip' => $row['nip'] ?? null,
                'position' => $row['jabatan'] ?? null,
                'rank' => $row['pangkat'] ?? null,
            ]);

            DB::commit();
            return $teacher;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
