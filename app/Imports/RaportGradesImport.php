<?php

namespace App\Imports;

use App\Models\StudentGrade;
use App\Models\GradeSetting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RaportGradesImport implements ToCollection, WithStartRow
{
    protected $level;
    protected $classLevels;

    public function __construct($level)
    {
        $this->level = $level;
        if ($level == 'MI') $this->classLevels = [4, 5, 6];
        elseif ($level == 'MTs') $this->classLevels = [7, 8, 9];
        elseif ($level == 'MA') $this->classLevels = [10, 11, 12];
    }

    public function startRow(): int
    {
        return 4; // Data starts at row 4
    }

    public function collection(Collection $rows)
    {
        $subjects = GradeSetting::where('level', $this->level)
            ->where('type', 'raport')
            ->orderBy('order')
            ->get();

        foreach ($rows as $row) {
            $nisn = trim((string) ($row[1] ?? ''));
            $nis = trim((string) ($row[2] ?? ''));
            
            if (!$nisn && !$nis) continue;

            $student = \App\Models\Student::where('nisn', $nisn)
                ->orWhere('nis', $nis)
                ->first();
                
            if (!$student) continue;

            $studentId = $student->id;
            $colOffset = 4; // Scores start at column 4
            foreach ($subjects as $gs) {
                $subColIndex = 0;
                foreach ($this->classLevels as $cl) {
                    foreach ([1, 2] as $sem) {
                        $scoreCol = $colOffset + $subColIndex;
                        $score = $row[$scoreCol] ?? 0;
                        
                        // Sanitize score
                        $score = is_numeric($score) ? round($score) : 0;

                        StudentGrade::updateOrCreate(
                            [
                                'student_id' => $studentId,
                                'subject_id' => $gs->subject_id,
                                'type' => 'raport',
                                'class_level' => $cl,
                                'semester' => $sem,
                            ],
                            ['score' => $score]
                        );
                        $subColIndex++;
                    }
                }
                $colOffset += 6; // Each subject has 6 scores
            }
        }
    }
}
