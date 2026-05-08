<?php

namespace App\Exports;

use App\Models\CbtStudentExam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CbtExamResultExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $examId;

    public function __construct($examId)
    {
        $this->examId = $examId;
    }

    public function collection()
    {
        return CbtStudentExam::with(['student.classGroup'])
            ->where('cbt_exam_id', $this->examId)
            ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA SISWA',
            'NISN',
            'KELAS',
            'STATUS',
            'PELANGGARAN',
            'NILAI AKHIR'
        ];
    }

    public function map($se): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $se->student->nama_lengkap ?? '-',
            $se->student->nisn ?? '-',
            $se->student->classGroup->group_name ?? '-',
            strtoupper($se->status),
            $se->violation_count,
            number_format($se->final_score, 2)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']]],
        ];
    }
}
