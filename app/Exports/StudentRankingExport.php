<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentRankingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $rankings;

    public function __construct($rankings)
    {
        $this->rankings = $rankings;
    }

    public function collection()
    {
        return collect($this->rankings);
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Kehadiran (%)',
            'Rata-rata Nilai',
            'Total Skor'
        ];
    }

    public function map($row): array
    {
        static $rank = 0;
        $rank++;
        
        return [
            $rank,
            $row['student']->nama_lengkap,
            $row['student']->nis,
            $row['student']->classGroup->kelas_lengkap ?? '-',
            $row['attendance_rate'] . '%',
            $row['avg_grade'],
            $row['total_score']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4E73DF']]],
        ];
    }
}
