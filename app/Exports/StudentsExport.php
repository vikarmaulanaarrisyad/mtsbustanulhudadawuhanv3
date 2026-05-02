<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return Student::with(['classGroup', 'academicYear', 'studentStatus', 'profile', 'parents'])
            ->when($this->request->academic_year_id, fn($q) => $q->where('academic_year_id', $this->request->academic_year_id))
            ->when($this->request->class_group_id, fn($q) => $q->where('student_class_group_id', $this->request->class_group_id))
            ->when($this->request->status_id, fn($q) => $q->where('student_status_id', $this->request->status_id))
            ->when($this->request->jenis_kelamin, fn($q) => $q->where('jenis_kelamin', $this->request->jenis_kelamin))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIS',
            'NISN',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Kelas',
            'Tahun Akademik',
            'Status',
            'Alamat',
            'No HP',
            'Nama Ayah',
            'Nama Ibu',
        ];
    }

    public function map($student): array
    {
        return [
            $student->nis,
            $student->nisn,
            $student->nama_lengkap,
            $student->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $student->tempat_lahir,
            $student->tanggal_lahir ? $student->tanggal_lahir->format('d-m-Y') : '-',
            $student->kelas_lengkap,
            $student->academicYear->academic_year ?? '-',
            $student->studentStatus->student_status_name ?? '-',
            $student->profile->alamat ?? '-',
            $student->profile->no_hp ?? '-',
            $student->parents->father_name ?? '-',
            $student->parents->mother_name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ],
        ];
    }
}
