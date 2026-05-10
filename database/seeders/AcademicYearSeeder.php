<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset all current_semester to 0 first
        AcademicYear::where('id', '>', 0)->update(['current_semester' => 0, 'admission_semester' => 0]);

        $years = [
            [
                'academic_year' => '2023/2024',
                'semester_id' => 2, // Genap
                'current_semester' => 0,
                'admission_semester' => 0,
            ],
            [
                'academic_year' => '2024/2025',
                'semester_id' => 1, // Ganjil
                'current_semester' => 0,
                'admission_semester' => 0,
            ],
            [
                'academic_year' => '2024/2025',
                'semester_id' => 2, // Genap
                'current_semester' => 0,
                'admission_semester' => 0,
            ],
            [
                'academic_year' => '2025/2026',
                'semester_id' => 1, // Ganjil
                'current_semester' => 1, // Aktif
                'admission_semester' => 1,
            ],
        ];

        foreach ($years as $year) {
            AcademicYear::updateOrCreate(
                [
                    'academic_year' => $year['academic_year'],
                    'semester_id' => $year['semester_id']
                ],
                $year
            );
        }
    }
}
