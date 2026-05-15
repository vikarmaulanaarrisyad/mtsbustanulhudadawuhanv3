<?php

namespace App\Imports;

use App\Models\BosProgram;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BosProgramImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new BosProgram([
            'code' => $row['kode'] ?? null,
            'name' => $row['nama'] ?? $row['program'] ?? null,
        ]);
    }
}
