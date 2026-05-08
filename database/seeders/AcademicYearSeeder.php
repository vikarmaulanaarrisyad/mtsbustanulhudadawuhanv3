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
        $years = [
            [
                'academic_year' => '2023/2024',
                'semester_id' => 2, // Genap
                'current_semester' => 0,
                'admission_semester' => 1,
            ],
            [
                'academic_year' => '2024/2025',
                'semester_id' => 1, // Ganjil
                'current_semester' => 1, // Aktif
                'admission_semester' => 1,
            ],
        ];

        foreach ($years as $year) {
            AcademicYear::updateOrCreate(
                ['academic_year' => $year['academic_year']],
                $year
            );
        }
    }
}
