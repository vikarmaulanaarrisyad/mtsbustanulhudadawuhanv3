<?php

namespace App\Imports;

use App\Models\StudentStatus;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentStatusImport implements ToModel, WithHeadingRow
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

            $cek = StudentStatus::where('student_status_name', $row['status'])->first();

            if (!$cek) {
                StudentStatus::create([
                    'student_status_name' => $row['status'],
                ]);
            }
            DB::commit(); // Commit jika berhasil
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
