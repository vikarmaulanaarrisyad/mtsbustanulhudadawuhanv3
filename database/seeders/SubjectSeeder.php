<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Al-Qur\'an Hadits', 'code' => 'QH', 'category' => 'Keagamaan'],
            ['name' => 'Akidah Akhlak', 'code' => 'AA', 'category' => 'Keagamaan'],
            ['name' => 'Fikih', 'code' => 'FIQ', 'category' => 'Keagamaan'],
            ['name' => 'Sejarah Kebudayaan Islam', 'code' => 'SKI', 'category' => 'Keagamaan'],
            ['name' => 'Bahasa Arab', 'code' => 'BAR', 'category' => 'Keagamaan'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN', 'category' => 'Kelompok A'],
            ['name' => 'Bahasa Inggris', 'code' => 'BIG', 'category' => 'Kelompok A'],
            ['name' => 'Matematika', 'code' => 'MTK', 'category' => 'Kelompok A'],
            ['name' => 'IPA', 'code' => 'IPA', 'category' => 'Kelompok A'],
            ['name' => 'IPS', 'code' => 'IPS', 'category' => 'Kelompok A'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKN', 'category' => 'Kelompok A'],
            ['name' => 'PJOK', 'code' => 'PJOK', 'category' => 'Kelompok B'],
            ['name' => 'Seni Budaya dan Prakarya', 'code' => 'SBDP', 'category' => 'Kelompok B'],
            ['name' => 'TIK', 'code' => 'TIK', 'category' => 'Kelompok B'],
            ['name' => 'Ke-NU-an', 'code' => 'NU', 'category' => 'Muatan Lokal'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['code' => $subject['code']],
                $subject
            );
        }
    }
}
