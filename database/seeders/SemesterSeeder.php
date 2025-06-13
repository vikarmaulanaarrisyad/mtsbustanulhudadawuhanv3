<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'semester_name' => 'Ganjil'],
            ['id' => 2, 'semester_name' => 'Genap'],
        ];

        foreach ($data as $item) {
            Semester::updateOrCreate(
                ['id' => $item['id']],
                ['semester_name' => $item['semester_name']]
            );
        }
    }
}
