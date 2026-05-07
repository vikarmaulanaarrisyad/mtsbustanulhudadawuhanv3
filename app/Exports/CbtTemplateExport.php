<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CbtTemplateExport implements WithMultipleSheets
{
    protected $bankName;

    public function __construct(string $bankName = 'Bank Soal')
    {
        $this->bankName = $bankName;
    }

    public function sheets(): array
    {
        return [
            'Template Soal' => new CbtTemplateSheet($this->bankName),
            'Petunjuk & Contoh' => new CbtTemplateGuideSheet(),
        ];
    }
}
