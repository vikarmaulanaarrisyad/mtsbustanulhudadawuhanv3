<?php

namespace App\Imports;

use App\Models\MonthlyIncome;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MonthlyIncomeImport implements ToModel, WithHeadingRow
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

            $cek = MonthlyIncome::where('monthly_incomes_name', $row['penghasilan'])->first();

            if (!$cek) {
                MonthlyIncome::create([
                    'monthly_incomes_name' => $row['penghasilan'],
                ]);
            }
            DB::commit(); // Commit jika berhasil
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
