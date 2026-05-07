<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = \App\Models\Student::all();
        $this->command->info('Mulai membuat akun untuk ' . $students->count() . ' siswa...');

        foreach ($students as $student) {
            $username = $student->nisn ?? $student->nis;
            
            if (empty($username)) {
                $this->command->warn('Siswa ' . $student->nama_lengkap . ' tidak memiliki NISN/NIS, dilewati.');
                continue;
            }

            // Cari atau buat user
            $user = \App\Models\User::where('username', $username)->first();
            
            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $student->nama_lengkap,
                    'username' => $username,
                    'email' => $username . '@mtsbustanulhuda.sch.id',
                    'password' => \Illuminate\Support\Facades\Hash::make($username),
                ]);
                $user->assignRole('Siswa');
            }

            // Link ke student
            $student->user_id = $user->id;
            $student->save();
        }

        $this->command->info('Selesai membuat akun siswa.');
    }
}
