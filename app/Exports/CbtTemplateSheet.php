<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class CbtTemplateSheet implements FromArray, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $bankName;

    public function __construct(string $bankName)
    {
        $this->bankName = $bankName;
    }

    public function title(): string
    {
        return 'Template Soal';
    }

    public function headings(): array
    {
        return [
            'No',
            'Tipe Soal',
            'Soal',
            'Gambar Soal',
            'Opsi A',
            'Gambar Opsi A',
            'Opsi B',
            'Gambar Opsi B',
            'Opsi C',
            'Gambar Opsi C',
            'Opsi D',
            'Gambar Opsi D',
            'Opsi E',
            'Gambar Opsi E',
            'Jawaban Benar',
            'Bobot Nilai',
            'Pasangan (Penjodohan)',
        ];
    }

    public function array(): array
    {
        // Return sample rows
        return [
            [1, 'PG', 'Siapakah presiden pertama Indonesia?', '', 'Soekarno', '', 'Soeharto', '', 'Habibie', '', 'Megawati', '', '', '', 'A', 1, ''],
            [2, 'PGK', 'Manakah yang termasuk bilangan prima?', '', '2', '', '4', '', '5', '', '6', '', '7', '', 'A,C,E', 1, ''],
            [3, 'Penjodohan', 'Jodohkan negara dengan ibukotanya', '', '', '', '', '', '', '', '', '', '', '', '', 1, '{"Indonesia":"Jakarta","Malaysia":"Kuala Lumpur","Thailand":"Bangkok"}'],
            [4, 'Essay', 'Jelaskan pengertian fotosintesis!', '', '', '', '', '', '', '', '', '', '', '', 'Fotosintesis adalah proses pembuatan makanan oleh tumbuhan hijau', 2, ''],
            [5, 'Uraian', 'Uraikan proses terjadinya hujan secara lengkap!', '', '', '', '', '', '', '', '', '', '', '', '', 3, ''],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 12,  // Tipe Soal
            'C' => 40,  // Soal
            'D' => 15,  // Gambar Soal
            'E' => 20,  // Opsi A
            'F' => 15,  // Gambar Opsi A
            'G' => 20,  // Opsi B
            'H' => 15,  // Gambar Opsi B
            'I' => 20,  // Opsi C
            'J' => 15,  // Gambar Opsi C
            'K' => 20,  // Opsi D
            'L' => 15,  // Gambar Opsi D
            'M' => 20,  // Opsi E
            'N' => 15,  // Gambar Opsi E
            'O' => 15,  // Jawaban Benar
            'P' => 10,  // Bobot Nilai
            'Q' => 45,  // Pasangan Penjodohan
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1E40AF']],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Title row height
                $sheet->getRowDimension(1)->setRowHeight(35);

                // Data rows styling
                for ($row = 2; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(25);
                    $bgColor = ($row % 2 === 0) ? 'F0F7FF' : 'FFFFFF';
                    $sheet->getStyle("A{$row}:Q{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $bgColor],
                        ],
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                    ]);
                }

                // Center No and Bobot columns
                $sheet->getStyle("A2:A100")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("P2:P100")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("O2:O100")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Data validation for Tipe Soal (dropdown)
                for ($row = 2; $row <= 200; $row++) {
                    $validation = $sheet->getCell("B{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Tipe tidak valid');
                    $validation->setError('Pilih salah satu: PG, PGK, Penjodohan, Essay, Uraian');
                    $validation->setPromptTitle('Tipe Soal');
                    $validation->setPrompt('Pilih tipe soal');
                    $validation->setFormula1('"PG,PGK,Penjodohan,Essay,Uraian"');
                }

                // Freeze top row
                $sheet->freezePane('A2');

                // Add sample image to D2 (Gambar Soal)
                if (file_exists(public_path('img/logo.png'))) {
                    $drawing = new Drawing();
                    $drawing->setName('Sample Image');
                    $drawing->setDescription('Sample Question Image');
                    $drawing->setPath(public_path('img/logo.png'));
                    $drawing->setHeight(80);
                    $drawing->setCoordinates('D2');
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    $drawing->setWorksheet($sheet);
                    
                    // Adjust row height to fit image
                    $sheet->getRowDimension(2)->setRowHeight(80);

                    // Add sample image to F2 (Gambar Opsi A)
                    $drawing2 = new Drawing();
                    $drawing2->setName('Sample Option');
                    $drawing2->setPath(public_path('img/logo.png'));
                    $drawing2->setHeight(50);
                    $drawing2->setCoordinates('F2');
                    $drawing2->setOffsetX(10);
                    $drawing2->setOffsetY(10);
                    $drawing2->setWorksheet($sheet);
                }

                // Add bank name info at top
                $sheet->getHeaderFooter()->setOddHeader('&L&B' . $this->bankName . '&R&D');
            },
        ];
    }
}
