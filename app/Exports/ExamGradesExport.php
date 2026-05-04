<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\GradeSetting;
use App\Models\StudentGrade;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExamGradesExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $classId;
    protected $level;

    public function __construct($classId, $level)
    {
        $this->classId = $classId;
        $this->level = $level;
    }

    public function view(): View
    {
        $subjects = GradeSetting::where('level', $this->level)
            ->where('type', 'ujian_madrasah')
            ->with('subject')
            ->orderBy('order')
            ->get();

        $students = Student::where('student_class_group_id', $this->classId)->orderBy('nama_lengkap')->get();
        
        $finalClass = 0;
        if ($this->level == 'MI') $finalClass = 6;
        elseif ($this->level == 'MTs') $finalClass = 9;
        elseif ($this->level == 'MA') $finalClass = 12;

        $grades = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('type', 'ujian_madrasah')
            ->where('class_level', $finalClass)
            ->get();

        return view('admin.grades.exports.exam', [
            'subjects' => $subjects,
            'students' => $students,
            'grades' => $grades,
            'level' => $this->level
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->getProtection()->setPassword('madrasah');
                $sheet->getProtection()->setSheet(true);

                $subjectsCount = GradeSetting::where('level', $this->level)
                    ->where('type', 'ujian_madrasah')
                    ->count();
                $studentsCount = Student::where('student_class_group_id', $this->classId)->count();

                if ($subjectsCount > 0 && $studentsCount > 0) {
                    $startCol = 5; // Column E
                    $endColIndex = 4 + $subjectsCount;
                    $startRow = 3;
                    $endRow = 2 + $studentsCount;

                    $startColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
                    $endColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endColIndex);
                    
                    $range = "{$startColLetter}{$startRow}:{$endColLetter}{$endRow}";
                    
                    $sheet->getStyle($range)->getProtection()->setLocked(
                        \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                    );

                    // --- AESTHETICS ---
                    for ($i = $endColIndex + 1; $i <= 100; $i++) {
                        $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setVisible(false);
                    }
                    
                    for ($i = $endRow + 1; $i <= $endRow + 100; $i++) {
                        $sheet->getRowDimension($i)->setVisible(false);
                    }

                    $sheet->getStyle("A1:{$endColLetter}2")->getFont()->setBold(true);
                    $sheet->setShowGridlines(false);

                    // Freeze panes (lock columns A-D and rows 1-2)
                    $sheet->freezePane('E3');
                }
            },
        ];
    }
}
