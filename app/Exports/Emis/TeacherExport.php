<?php

namespace App\Exports\Emis;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeacherExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Teacher::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIP',
            'NUPTK',
            'NPK',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Status Pegawai',
            'Agama',
            'Alamat',
            'No HP/WA',
        ];
    }

    public function map($teacher): array
    {
        static $no = 1;
        return [
            $no++,
            $teacher->name ?? '',
            $teacher->nip ?? '',
            $teacher->nuptk ?? '',
            $teacher->npk ?? '',
            $teacher->nik ?? '',
            $teacher->birth_place ?? '',
            $teacher->birth_date ? \Carbon\Carbon::parse($teacher->birth_date)->format('Y-m-d') : '',
            $teacher->gender ?? '',
            $teacher->employment_status ?? '',
            $teacher->religion ?? '',
            $teacher->address ?? '',
            $teacher->phone ?? '',
        ];
    }
}
