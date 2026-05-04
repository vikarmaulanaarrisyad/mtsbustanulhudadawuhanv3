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

class RaportGradesExport implements FromView, ShouldAutoSize, WithEvents
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
            ->where('type', 'raport')
            ->with('subject')
            ->orderBy('order')
            ->get();

        $students = Student::where('student_class_group_id', $this->classId)->orderBy('nama_lengkap')->get();
        
        $classLevels = [];
        if ($this->level == 'MI') $classLevels = [4, 5, 6];
        elseif ($this->level == 'MTs') $classLevels = [7, 8, 9];
        elseif ($this->level == 'MA') $classLevels = [10, 11, 12];

        $grades = StudentGrade::whereIn('student_id', $students->pluck('id'))
            ->where('type', 'raport')
            ->get();

        return view('admin.grades.exports.raport', [
            'subjects' => $subjects,
            'students' => $students,
            'grades' => $grades,
            'classLevels' => $classLevels,
            'level' => $this->level
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Protect the sheet
                $sheet->getProtection()->setPassword('madrasah');
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setSort(true);
                $sheet->getProtection()->setInsertRows(true);
                $sheet->getProtection()->setFormatCells(true);

                $subjectsCount = GradeSetting::where('level', $this->level)
                    ->where('type', 'raport')
                    ->count();
                $studentsCount = Student::where('student_class_group_id', $this->classId)->count();

                if ($subjectsCount > 0 && $studentsCount > 0) {
                    $startCol = 5; // Column E
                    $endColIndex = 4 + ($subjectsCount * 6);
                    $startRow = 4;
                    $endRow = 3 + $studentsCount;

                    $startColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startCol);
                    $endColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endColIndex);
                    
                    $range = "{$startColLetter}{$startRow}:{$endColLetter}{$endRow}";
                    
                    // Unlock the score range
                    $sheet->getStyle($range)->getProtection()->setLocked(
                        \PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED
                    );

                    // --- AESTHETICS ---
                    // Hide unused columns (from endColIndex + 1 to 100)
                    for ($i = $endColIndex + 1; $i <= 100; $i++) {
                        $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setVisible(false);
                    }
                    
                    // Hide unused rows (from endRow + 1 to 1000)
                    for ($i = $endRow + 1; $i <= $endRow + 100; $i++) {
                        $sheet->getRowDimension($i)->setVisible(false);
                    }

                    // Set some styling for the headers
                    $sheet->getStyle("A1:{$endColLetter}3")->getFont()->setBold(true);
                    $sheet->setShowGridlines(false);

                    // Freeze panes (lock columns A-D and rows 1-3)
                    $sheet->freezePane('E4');
                }
            },
        ];
    }
}
