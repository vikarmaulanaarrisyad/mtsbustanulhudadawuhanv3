<?php

namespace App\Imports;

use App\Models\BosMasterRkam;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BosMasterRkamImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $codes = $rows->pluck('kode_sub_kegiatan')->filter()->toArray();
        $existing = BosMasterRkam::whereIn('kode_sub_kegiatan', $codes)->get()->keyBy('kode_sub_kegiatan');

        $toInsert = [];
        foreach ($rows as $row) {
            $code = $row['kode_sub_kegiatan'] ?? null;
            if (!$code) continue;

            $data = [
                'kode_snp'      => $row['kode_snp'] ?? null,
                'snp'           => $row['snp'] ?? null,
                'kode_kegiatan' => $row['kode_kegiatan'] ?? null,
                'nama_kegiatan' => $row['nama_kegiatan'] ?? null,
                'sub_kegiatan'  => $row['sub_kegiatan'] ?? null,
            ];

            if ($existing->has($code)) {
                $existing->get($code)->update($data);
            } else {
                $data['kode_sub_kegiatan'] = $code;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $toInsert[] = $data;
            }
        }

        if (!empty($toInsert)) {
            BosMasterRkam::insert($toInsert);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
