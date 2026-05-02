<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeachersTemplateExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            [
                'Ahmad Fauzi, S.Pd',
                '198501012010011001',
                'Guru Matematika',
                'Penata Muda / III.a',
                'ahmad@example.com',
                'ahmadfauzi',
                'password123'
            ],
            [
                'Siti Aminah, M.Pd',
                '199002022015022002',
                'Guru Bahasa Indonesia',
                'Penata / III.c',
                'siti@example.com',
                'sitiaminah',
                'password123'
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'nama',
            'nip',
            'jabatan',
            'pangkat',
            'email',
            'username',
            'password'
        ];
    }
}
