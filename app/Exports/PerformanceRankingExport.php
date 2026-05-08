<?php
namespace App\Exports;

use App\Models\PerformanceAssessment;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class PerformanceRankingExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        $currentAY = AcademicYear::where('current_semester', 1)->first();
        
        return PerformanceAssessment::with('teacher')
            ->where('academic_year_id', $currentAY->id ?? 0)
            ->where('status', 'submitted')
            ->select('teacher_id', DB::raw('AVG(total_score) as final_score'))
            ->groupBy('teacher_id')
            ->orderByDesc('final_score')
            ->get();
    }

    public function headings(): array
    {
        return [
            'PERINGKAT',
            'NAMA GURU',
            'NIP',
            'JABATAN',
            'SKOR AKHIR (%)',
            'PREDIKAT'
        ];
    }

    public function map($rank): array
    {
        static $index = 0;
        $index++;

        $score = $rank->final_score;
        $predikat = 'Kurang';
        if($score >= 90) $predikat = 'Amat Baik';
        elseif($score >= 75) $predikat = 'Baik';
        elseif($score >= 60) $predikat = 'Cukup';

        return [
            $index,
            $rank->teacher->name ?? '-',
            $rank->teacher->nip ?? '-',
            $rank->teacher->position ?? '-',
            number_format($score, 2) . '%',
            $predikat
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4B5563']]],
        ];
    }
}
