<?php

namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Subject([
            'name' => $row['nama_mapel'],
            'code' => $row['kode_mapel'] ?? null,
        ]);
    }
}
