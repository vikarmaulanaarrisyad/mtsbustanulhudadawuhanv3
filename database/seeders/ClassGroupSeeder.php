<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ClassGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = AcademicYear::where('current_semester', 1)->first();
        if (!$academicYear) {
            // Fallback if no active year found
            $academicYear = AcademicYear::first();
        }
        
        if (!$academicYear) return;

        $teachers = Teacher::all();
        
        // Jenjang MI (Kelas 1-6)
        $classes = [
            ['level' => 1, 'name' => '1', 'sub' => 'A'],
            ['level' => 1, 'name' => '1', 'sub' => 'B'],
            ['level' => 2, 'name' => '2', 'sub' => 'A'],
            ['level' => 2, 'name' => '2', 'sub' => 'B'],
            ['level' => 3, 'name' => '3', 'sub' => 'A'],
            ['level' => 3, 'name' => '3', 'sub' => 'B'],
            ['level' => 4, 'name' => '4', 'sub' => 'A'],
            ['level' => 4, 'name' => '4', 'sub' => 'B'],
            ['level' => 5, 'name' => '5', 'sub' => 'A'],
            ['level' => 5, 'name' => '5', 'sub' => 'B'],
            ['level' => 6, 'name' => '6', 'sub' => 'A'],
            ['level' => 6, 'name' => '6', 'sub' => 'B'],
        ];

        foreach ($classes as $index => $class) {
            ClassGroup::updateOrCreate(
                [
                    'class_group' => $class['name'],
                    'sub_class_group' => $class['sub'],
                    'academic_year_id' => $academicYear->id
                ],
                [
                    'class_level' => $class['level'],
                    'teacher_id' => $teachers->isNotEmpty() ? $teachers->random()->id : null,
                ]
            );
        }
    }
}
