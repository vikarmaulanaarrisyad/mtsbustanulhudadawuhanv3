<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CbtTemplateGuideSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function title(): string
    {
        return 'Petunjuk & Contoh';
    }

    public function array(): array
    {
        return [
            ['PETUNJUK PENGISIAN TEMPLATE SOAL CBT'],
            [''],
            ['Kolom', 'Keterangan'],
            ['No', 'Nomor urut soal (1, 2, 3, ...)'],
            ['Tipe Soal', 'Pilih salah satu: PG, PGK, Penjodohan, Essay, Uraian'],
            ['Soal', 'Tuliskan teks soal. Bisa menggunakan format HTML sederhana (<b>, <i>, <u>)'],
            ['Opsi A - E', 'Isi pilihan jawaban (khusus PG dan PGK). Kosongkan untuk Essay/Uraian/Penjodohan.'],
            ['Jawaban Benar', 'PG: huruf jawaban (A/B/C/D/E). PGK: beberapa huruf dipisah koma (A,C,E). Essay: teks jawaban kunci. Uraian: boleh kosong.'],
            ['Bobot Nilai', 'Skor untuk soal ini (default: 1). Bisa diisi angka lebih besar untuk soal sulit.'],
            ['Pasangan Penjodohan', 'Khusus tipe Penjodohan. Format: Premis1=Respon1|Premis2=Respon2|...'],
            [''],
            ['PENJELASAN TIPE SOAL'],
            [''],
            ['Tipe', 'Penjelasan', 'Contoh Jawaban Benar'],
            ['PG', 'Pilihan Ganda - 1 jawaban benar dari beberapa opsi', 'A'],
            ['PGK', 'Pilihan Ganda Komplek - Lebih dari 1 jawaban benar', 'A,C,E'],
            ['Penjodohan', 'Menjodohkan premis dengan respon yang tepat', '(Isi kolom Pasangan Penjodohan)'],
            ['Essay', 'Jawaban singkat - ada kunci jawaban', 'Fotosintesis adalah proses...'],
            ['Uraian', 'Jawaban panjang/deskriptif - dinilai manual', '(Kosongkan, dinilai guru)'],
            [''],
            ['CARA MENAMBAHKAN GAMBAR SOAL'],
            [''],
            ['Cara 1: Naming Convention (Sangat Disarankan)'],
            ['- Namai file gambar Anda sesuai Nomor Soal (kolom No) atau Nomor Baris.'],
            ['- Contoh: Untuk soal No 1, namai gambar: 1.jpg'],
            ['- Untuk Opsi A pada soal No 1, namai gambar: 1_A.jpg (begitu juga B, C, D, E)'],
            ['- Saat import, pilih semua file gambar tersebut bersamaan dengan file Excel.'],
            [''],
            ['Cara 2: Tulis Nama File di Kolom Gambar'],
            ['- Ketik nama file (misal: biology.png) di kolom "Gambar Soal" atau "Gambar Opsi".'],
            ['- Upload file gambar tersebut bersamaan dengan file Excel saat proses import.'],
            [''],
            ['Cara 3: Sisipkan Gambar Langsung (Embedded)'],
            ['- Masukkan/Copy-Paste gambar langsung ke dalam cell di kolom "Gambar Soal/Opsi".'],
            ['- Sesuaikan ukuran gambar agar pas di dalam cell.'],
            [''],
            ['Cara 4: Tag [IMG] (Legacy)'],
            ['- Di kolom "Soal", tambahkan teks: [IMG:nama_file.jpg]'],
            [''],
            ['TIPS:'],
            ['- Pastikan tipe soal diisi dengan benar (PG/PGK/Penjodohan/Essay/Uraian)'],
            ['- Untuk PG & PGK, minimal isi 2 opsi (Opsi A dan Opsi B)'],
            ['- Jawaban PGK dipisah dengan koma tanpa spasi: A,C,E'],
            ['- Penjodohan dipisah dengan tanda | dan = : Negara=Ibu Kota'],
            ['- Bobot nilai default adalah 1 jika dikosongkan'],
            ['- Baris dengan kolom No atau Soal kosong akan dilewati (skip)'],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 60,
            'C' => 35,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1E40AF']],
            ],
            3 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'],
                ],
            ],
            12 => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '7C3AED']],
            ],
            14 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7C3AED'],
                ],
            ],
            21 => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'DC2626']],
            ],
            28 => [
                'font' => ['bold' => true, 'italic' => true, 'color' => ['rgb' => '2563EB']],
            ],
            30 => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '059669']],
            ],
        ];
    }
}
