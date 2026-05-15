<?php

namespace App\Imports;

use App\Models\BosItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BosItemImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $codes = $rows->map(fn($r) => $r['kode'] ?? $r['code'] ?? null)->filter()->toArray();
        $existing = BosItem::whereIn('code', $codes)->get()->keyBy('code');

        $toInsert = [];
        foreach ($rows as $row) {
            $code = $row['kode'] ?? $row['code'] ?? null;
            if (!$code) continue;

            $data = [
                'tahun'       => $row['tahun'] ?? null,
                'kategori'    => $row['kategori'] ?? null,
                'kode_kateg'  => $row['kode_kateg'] ?? null,
                'nama_kateg'  => $row['nama_kateg'] ?? null,
                'kode_provi'  => $row['kode_provi'] ?? null,
                'kode_kabk'   => $row['kode_kabk'] ?? null,
                'name'        => $row['nama'] ?? $row['name'] ?? null,
                'spesifikasi' => $row['spesifikasi'] ?? null,
                'satuan'      => $row['satuan'] ?? null,
                'jenis_pemb'  => $row['jenis_pemb'] ?? null,
                'harga_1'     => $row['harga_1'] ?? 0,
                'harga_2'     => $row['harga_2'] ?? 0,
                'harga_3'     => $row['harga_3'] ?? 0,
                'price'       => $row['harga_1'] ?? 0,
                'unit'        => $row['satuan'] ?? null,
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
            BosItem::insert($toInsert);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
