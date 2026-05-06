<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeachersTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Template' => new TeacherDataSheet(),
            'Panduan'  => new TeacherInstructionSheet(),
        ];
    }
}

class TeacherDataSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return collect([
            [
                'Ahmad Fauzi, S.Pd',                 // nama
                '3201012501850001',                   // nik
                '198501012010011001',                 // nip
                'NUP123456',                          // nuptk
                'L',                                  // jenis_kelamin (L/P)
                'Bandung',                            // tempat_lahir
                '1985-01-25',                         // tanggal_lahir
                'Jl. Merdeka No.1',                   // alamat
                '08123456789',                        // no_hp
                'PNS',                                // status_kepegawaian
                'Guru Matematika',                    // jabatan
                'Wali Kelas',                         // tugas_tambahan
                'Penata Muda / III.a',                // pangkat_golongan
                '2010-01-01',                         // tmt
                '1',                                  // sudah_sertifikasi
                'S1',                                 // pendidikan_terakhir
                'Pendidikan Matematika',              // jurusan
                'Universitas Indonesia',              // universitas
                'BRI',                                // nama_bank
                '1234567890',                         // no_rekening
                'Ahmad Fauzi',                        // nama_pemilik_rekening
                '3500000',                            // gaji_pokok
                'ahmad@example.com',                  // email
                'ahmadfauzi',                         // username
                'password123',                        // password
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'nama', 'nik', 'nip', 'nuptk', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'no_hp',
            'status_kepegawaian', 'jabatan', 'tugas_tambahan', 'pangkat_golongan', 'tmt', 'sudah_sertifikasi', 'pendidikan_terakhir',
            'jurusan', 'universitas', 'nama_bank', 'no_rekening', 'nama_pemilik_rekening', 'gaji_pokok',
            'email', 'username', 'password'
        ];
    }

    public function title(): string
    {
        return 'Template Data Guru';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Y1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '117a8b']],
        ]);
        $sheet->freezePane('A2');
    }
}

class TeacherInstructionSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return collect([
            ['nama', 'Wajib diisi. Sertakan gelar jika perlu.'],
            ['nik', '16 digit angka sesuai KTP.'],
            ['nip', 'NIP atau NIPPK (jika ada). Angka saja.'],
            ['jenis_kelamin', 'Isi dengan L (Laki-laki) atau P (Perempuan).'],
            ['tanggal_lahir', 'Format: YYYY-MM-DD (Contoh: 1990-05-15).'],
            ['status_kepegawaian', 'Isi: PNS / PPPK / GTY / GTT / Honorer.'],
            ['jabatan', 'Jabatan Utama (Pendidik).'],
            ['tugas_tambahan', 'Tugas Tambahan (Struktural) seperti Wali Kelas, Bendahara, dll.'],
            ['tmt', 'Tanggal Mulai Tugas. Format: YYYY-MM-DD.'],
            ['sudah_sertifikasi', 'Isi 1 untuk Ya, atau 0 untuk Belum.'],
            ['gaji_pokok', 'Angka saja tanpa titik/koma (Contoh: 3000000).'],
            ['email', 'Unik. Digunakan untuk login ke sistem.'],
            ['username', 'Unik. Tanpa spasi.'],
            ['password', 'Minimal 8 karakter.'],
            ['', ''],
            ['PENTING:', 'Jangan mengubah urutan kolom pada Sheet "Template Data Guru".'],
            ['', 'Gunakan format tanggal YYYY-MM-DD agar sistem dapat membaca dengan benar.'],
        ]);
    }

    public function headings(): array
    {
        return ['Nama Kolom', 'Petunjuk Pengisian / Format'];
    }

    public function title(): string
    {
        return 'Petunjuk Pengisian';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '28a745']],
        ]);
        
        $sheet->getStyle('A15:B16')->getFont()->setBold(true)->getColor()->setRGB('FF0000');
    }
}
