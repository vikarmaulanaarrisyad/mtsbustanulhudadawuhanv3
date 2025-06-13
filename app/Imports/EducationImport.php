<?php

namespace App\Imports;

use App\Models\Education;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;

class EducationImport implements ToModel, WithHeadingRow, WithChunkReading
{
    use RemembersChunkOffset;
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

            $cek = Education::where('education_name', $row['status'])->first();

            if (!$cek) {
                Education::create([
                    'education_name' => $row['status'],
                ]);
            }
            DB::commit(); // Commit jika berhasil
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
