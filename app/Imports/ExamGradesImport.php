<?php

namespace App\Imports;

use App\Models\StudentGrade;
use App\Models\GradeSetting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExamGradesImport implements ToCollection, WithStartRow
{
    protected $level;
    protected $finalClass;

    public function __construct($level)
    {
        $this->level = $level;
        if ($level == 'MI') $this->finalClass = 6;
        elseif ($level == 'MTs') $this->finalClass = 9;
        elseif ($level == 'MA') $this->finalClass = 12;
    }

    public function startRow(): int
    {
        return 3; // Data starts at row 3 for Exam
    }

    public function collection(Collection $rows)
    {
        $subjects = GradeSetting::where('level', $this->level)
            ->where('type', 'ujian_madrasah')
            ->orderBy('order')
            ->get();

        foreach ($rows as $row) {
            $nisn = $row[1]; // Column 1: NISN (ID SISWA)
            if (!$nisn) continue;

            $student = \App\Models\Student::where('nisn', $nisn)->first();
            if (!$student) continue;

            $studentId = $student->id;
            $colOffset = 4; // Scores start at column 4
            foreach ($subjects as $gs) {
                $score = $row[$colOffset] ?? 0;

                StudentGrade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $gs->subject_id,
                        'type' => 'ujian_madrasah',
                        'class_level' => $this->finalClass,
                    ],
                    ['score' => (float) $score]
                );
                $colOffset += 1; // Each subject has 1 score
            }
        }
    }
}
