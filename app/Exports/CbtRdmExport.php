<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CbtRdmExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $studentExams;
    protected $count = 0;

    public function __construct($studentExams)
    {
        $this->studentExams = $studentExams;
    }

    public function collection()
    {
        return $this->studentExams;
    }

    public function headings(): array
    {
        return [
            ['FORMAT IMPOR NILAI RDM'],
            [''],
            [
                'NO',
                'NISN',
                'NAMA SISWA',
                'NILAI'
            ]
        ];
    }

    public function map($row): array
    {
        $this->count++;
        return [
            $this->count,
            $row->student->nisn ?? '-',
            $row->student->name ?? '-',
            $row->final_score ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            3    => ['font' => ['bold' => true]],
        ];
    }
}
