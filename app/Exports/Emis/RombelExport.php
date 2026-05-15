<?php

namespace App\Exports\Emis;

use App\Models\ClassGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RombelExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ClassGroup::with(['teacher', 'academicYear'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tahun Ajaran',
            'Tingkat Kelas',
            'Nama Rombel',
            'Wali Kelas',
            'NUPTK/NPK Wali Kelas',
            'Kurikulum',
            'Kapasitas Maksimal',
            'Jumlah Siswa Aktif',
        ];
    }

    public function map($rombel): array
    {
        static $no = 1;
        return [
            $no++,
            $rombel->academicYear?->name ?? '',
            $rombel->level ?? '',
            $rombel->name ?? '',
            $rombel->teacher?->name ?? 'Belum Diatur',
            $rombel->teacher?->nuptk ?? $rombel->teacher?->npk ?? '',
            $rombel->curriculum ?? 'Merdeka',
            $rombel->capacity ?? '',
            $rombel->students()->count() ?? 0,
        ];
    }
}
