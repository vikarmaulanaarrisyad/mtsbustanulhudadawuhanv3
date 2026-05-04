<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use Illuminate\Database\Seeder;

class ClassLevelFixerSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5, 'VI' => 6,
            'VII' => 7, 'VIII' => 8, 'IX' => 9,
            'X' => 10, 'XI' => 11, 'XII' => 12,
            'Kelas 1' => 1, 'Kelas 2' => 2, 'Kelas 3' => 3, 'Kelas 4' => 4, 'Kelas 5' => 5, 'Kelas 6' => 6,
            'Kelas 7' => 7, 'Kelas 8' => 8, 'Kelas 9' => 9,
            'Kelas 10' => 10, 'Kelas 11' => 11, 'Kelas 12' => 12,
        ];

        foreach ($map as $name => $lvl) {
            ClassGroup::where('class_group', $name)->update(['class_level' => $lvl]);
        }

        // Catch all level 0 that start with numbers
        for ($i = 1; $i <= 12; $i++) {
            ClassGroup::where('class_level', 0)
                ->where('class_group', 'like', $i . '%')
                ->update(['class_level' => $i]);
        }

        $this->command->info('Class levels have been normalized.');
    }
}
