<?php

namespace App\Exports\Emis;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Student::with(['profile', 'parents', 'classGroup'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NISN',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Nama Ibu Kandung',
            'Nama Ayah Kandung',
            'No HP/WA',
            'Alamat',
            'Rombel Saat Ini',
        ];
    }

    public function map($student): array
    {
        static $no = 1;
        return [
            $no++,
            $student->name ?? '',
            $student->nisn ?? '',
            $student->profile?->nik ?? '',
            $student->profile?->birth_place ?? '',
            $student->profile?->birth_date ? \Carbon\Carbon::parse($student->profile?->birth_date)->format('Y-m-d') : '',
            $student->gender ?? '',
            $student->profile?->religion ?? '',
            $student->parents?->mother_name ?? '',
            $student->parents?->father_name ?? '',
            $student->profile?->phone ?? '',
            $student->profile?->address ?? '',
            $student->classGroup?->name ?? 'Belum Ada Rombel',
        ];
    }
}
