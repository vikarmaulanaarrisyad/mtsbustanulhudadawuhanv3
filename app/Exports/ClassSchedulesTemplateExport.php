<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassSchedulesTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            ['Matematika', 'Budi Santoso, S.Pd', '7-A', '2023/2024 - Ganjil', 'Senin', '07:00', '08:30'],
        ]);
    }

    public function headings(): array
    {
        return [
            'mata_pelajaran',
            'nama_guru',
            'kelas',
            'tahun_pelajaran',
            'hari',
            'jam_mulai',
            'jam_selesai'
        ];
    }
}
