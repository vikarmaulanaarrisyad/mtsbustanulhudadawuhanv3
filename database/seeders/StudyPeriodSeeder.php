<?php

namespace Database\Seeders;

use App\Models\StudyPeriod;
use Illuminate\Database\Seeder;

class StudyPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = [
            ['period_number' => 1, 'start_time' => '07:00:00', 'end_time' => '07:35:00', 'is_break' => false],
            ['period_number' => 2, 'start_time' => '07:35:00', 'end_time' => '08:10:00', 'is_break' => false],
            ['period_number' => 3, 'start_time' => '08:10:00', 'end_time' => '08:45:00', 'is_break' => false],
            ['period_number' => 0, 'start_time' => '08:45:00', 'end_time' => '09:00:00', 'is_break' => true], // Istirahat 1
            ['period_number' => 4, 'start_time' => '09:00:00', 'end_time' => '09:35:00', 'is_break' => false],
            ['period_number' => 5, 'start_time' => '09:35:00', 'end_time' => '10:10:00', 'is_break' => false],
            ['period_number' => 6, 'start_time' => '10:10:00', 'end_time' => '10:45:00', 'is_break' => false],
            ['period_number' => 0, 'start_time' => '10:45:00', 'end_time' => '11:00:00', 'is_break' => true], // Istirahat 2
            ['period_number' => 7, 'start_time' => '11:00:00', 'end_time' => '11:35:00', 'is_break' => false],
            ['period_number' => 8, 'start_time' => '11:35:00', 'end_time' => '12:10:00', 'is_break' => false],
        ];

        foreach ($periods as $period) {
            StudyPeriod::updateOrCreate(
                ['period_number' => $period['period_number'], 'start_time' => $period['start_time']],
                $period
            );
        }
    }
}
