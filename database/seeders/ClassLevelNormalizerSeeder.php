<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use Illuminate\Database\Seeder;

class ClassLevelNormalizerSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            // MI
            '1' => 1, 'I ' => 1, 'I' => 1,
            '2' => 2, 'II ' => 2, 'II' => 2,
            '3' => 3, 'III ' => 3, 'III' => 3,
            '4' => 4, 'IV ' => 4, 'IV' => 4,
            '5' => 5, 'V ' => 5, 'V' => 5,
            '6' => 6, 'VI ' => 6, 'VI' => 6, 'Kelas 6' => 6,
            
            // MTs
            '7' => 7, 'VII ' => 7, 'VII' => 7,
            '8' => 8, 'VIII ' => 8, 'VIII' => 8,
            '9' => 9, 'IX ' => 9, 'IX' => 9,
            
            // MA
            '10' => 10, 'X ' => 10, 'X' => 10,
            '11' => 11, 'XI ' => 11, 'XI' => 11,
            '12' => 12, 'XII ' => 12, 'XII' => 12,
        ];

        foreach (ClassGroup::all() as $class) {
            $name = trim($class->class_group);
            
            foreach ($mappings as $key => $level) {
                if (stripos($name, (string)$key) === 0) {
                    $class->update(['class_level' => $level]);
                    break;
                }
            }
        }

        $this->command->info('Class levels have been normalized.');
    }
}
