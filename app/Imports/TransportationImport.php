<?php

namespace App\Imports;

use App\Models\Transportation;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransportationImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            // Bersihkan nama kolom dari spasi yang tidak perlu
            $row = array_map('trim', $row);  // Remove any extra spaces from column names

            $cek = Transportation::where('transportation_name', $row['transportasi'])->first();

            if (!$cek) {
                Transportation::create([
                    'transportation_name' => $row['transportasi'],
                ]);
            }
            DB::commit(); // Commit jika berhasil
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
