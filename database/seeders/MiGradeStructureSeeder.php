<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\GradeSetting;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class MiGradeStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil Mata Pelajaran MI (yang dibuat di SubjectSeeder)
        $subjects = Subject::all();

        if ($subjects->isEmpty()) {
            $this->command->error('Data Mata Pelajaran tidak ditemukan! Pastikan SubjectSeeder sudah dijalankan.');
            return;
        }

        $this->command->info('Menyusun Struktur Penilaian Jenjang MI...');

        // Bersihkan pengaturan MI yang lama agar tidak duplikat
        GradeSetting::where('level', 'MI')->delete();

        // Daftar Urutan Penilaian Raport MI
        $order = 1;
        foreach ($subjects as $subject) {
            // A. Konfigurasi untuk Nilai Raport
            GradeSetting::create([
                'level' => 'MI',
                'subject_id' => $subject->id,
                'type' => 'raport',
                'order' => $order++,
            ]);

            // B. Konfigurasi untuk Nilai Ujian Madrasah (Opsional untuk semua mapel)
            GradeSetting::create([
                'level' => 'MI',
                'subject_id' => $subject->id,
                'type' => 'ujian_madrasah',
                'order' => $order++,
            ]);
        }

        // 2. Pengaturan Bobot Penilaian (Global)
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'weight_raport' => 60, // Bobot Rapor 60%
                'weight_exam' => 40,   // Bobot Ujian 40%
            ]);
        }

        $this->command->info('Struktur Penilaian MI (Rapor & Ujian Madrasah) berhasil dikonfigurasi.');
    }
}
