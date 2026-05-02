<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjectsTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            ['Matematika', 'MTK01'],
            ['Bahasa Indonesia', 'BIN01'],
        ]);
    }

    public function headings(): array
    {
        return ['nama_mapel', 'kode_mapel'];
    }
}
