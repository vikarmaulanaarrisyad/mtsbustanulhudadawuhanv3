<?php

namespace App\Imports;

use App\Models\BosExpenseType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BosExpenseTypeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $codes = $rows->pluck('kode_jenis')->filter()->toArray();
        $existing = BosExpenseType::whereIn('kode_jenis', $codes)->get()->keyBy('kode_jenis');

        $toInsert = [];
        foreach ($rows as $row) {
            $code = $row['kode_jenis'] ?? null;
            if (!$code) continue;

            $data = [
                'kode_kate' => $row['kode_kate'] ?? null,
                'kategori'  => $row['kategori'] ?? null,
                'jenis'     => $row['jenis'] ?? null,
                'deskripsi' => $row['deskripsi'] ?? null,
            ];

            if ($existing->has($code)) {
                $existing->get($code)->update($data);
            } else {
                $data['kode_jenis'] = $code;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $toInsert[] = $data;
            }
        }

        if (!empty($toInsert)) {
            BosExpenseType::insert($toInsert);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
