<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\GradeSetting;
use Illuminate\Database\Seeder;

class GradeManagementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define Standard Madrasah Subjects (Combined)
        $subjectsList = [
            'Al-Qur\'an Hadis',
            'Akidah Akhlak',
            'Fikih',
            'Sejarah Kebudayaan Islam (SKI)',
            'Bahasa Arab',
            'Pendidikan Pancasila dan Kewarganegaraan (PKn)',
            'Bahasa Indonesia',
            'Matematika',
            'Ilmu Pengetahuan Alam (IPA)',
            'Ilmu Pengetahuan Sosial (IPS)',
            'Seni Budaya dan Prakarya (SBdP)',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)',
            'Bahasa Jawa',
            'Ke-NU-an / Aswaja',
            'Prakarya',
            'Bahasa Inggris',
            'Informatika',
            'Fisika',
            'Kimia',
            'Biologi',
            'Ekonomi',
            'Sosiologi',
            'Geografi',
        ];

        $levels = ['MI', 'MTs', 'MA'];

        // 2. Insert Subjects and Create Grade Settings for all levels
        foreach ($subjectsList as $index => $subjectName) {
            $subject = Subject::firstOrCreate(['name' => $subjectName]);

            foreach ($levels as $level) {
                // MI specific filter: Physics, Chem, Bio, Eco, Socio, Geo are usually for MA
                if ($level == 'MI' && in_array($subjectName, ['Fisika', 'Kimia', 'Biologi', 'Ekonomi', 'Sosiologi', 'Geografi', 'Bahasa Inggris'])) continue;
                
                // Add Raport Configuration
                GradeSetting::firstOrCreate([
                    'level' => $level,
                    'subject_id' => $subject->id,
                    'type' => 'raport',
                ], [
                    'order' => $index + 1
                ]);

                // Add Ujian Madrasah Configuration
                GradeSetting::firstOrCreate([
                    'level' => $level,
                    'subject_id' => $subject->id,
                    'type' => 'ujian_madrasah',
                ], [
                    'order' => $index + 1
                ]);
            }
        }

        // 3. Set Default Weights
        $setting = \App\Models\Setting::first();
        if ($setting) {
            $setting->update([
                'weight_raport' => 60,
                'weight_exam' => 40,
            ]);
        }

        $this->command->info('MI, MTs, and MA Grade Settings have been seeded successfully.');
    }
}
