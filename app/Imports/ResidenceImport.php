<?php

namespace App\Imports;

use App\Models\Residence;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ResidenceImport implements ToModel, WithHeadingRow
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

            $cek = Residence::where('residences_name', $row['tempat_tinggal'])->first();

            if (!$cek) {
                Residence::create([
                    'residences_name' => $row['tempat_tinggal'],
                ]);
            }
            DB::commit(); // Commit jika berhasil
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
