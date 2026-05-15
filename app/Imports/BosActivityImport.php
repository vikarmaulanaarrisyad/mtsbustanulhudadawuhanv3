<?php

namespace App\Imports;

use App\Models\BosActivity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BosActivityImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $codes = $rows->pluck('kode')->filter()->toArray();
        $existing = BosActivity::whereIn('code', $codes)->get()->keyBy('code');

        $toInsert = [];
        foreach ($rows as $row) {
            $code = $row['kode'] ?? null;
            if (!$code) continue;

            $data = [
                'name'     => $row['nama'] ?? $row['name'] ?? null,
                'category' => $row['kategori'] ?? $row['category'] ?? null,
            ];

            if ($existing->has($code)) {
                $existing->get($code)->update($data);
            } else {
                $data['code'] = $code;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $toInsert[] = $data;
            }
        }

        if (!empty($toInsert)) {
            BosActivity::insert($toInsert);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
