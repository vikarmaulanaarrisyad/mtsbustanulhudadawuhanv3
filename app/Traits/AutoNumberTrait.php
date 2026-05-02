<?php

namespace App\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

trait AutoNumberTrait
{
    /**
     * Generate automatic letter number
     * Format: {Number}/{Type}/{SchoolCode}/{MonthRoman}/{Year}
     *
     * @param string $type Code of the letter (e.g., SKL, MUT, S-AKT)
     * @param string $column Column name to check (e.g., letter_number, mail_number)
     * @return string
     */
    public static function generateLetterNumber($type, $column = 'letter_number')
    {
        $year = date('Y');
        $month = date('n');
        $romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $monthRoman = $romans[$month];
        
        $setting = Setting::first();
        $schoolCode = $setting->school_code ?? 'MADRASAH';

        // Find last number for this type in this year
        // We look for patterns like "%/{type}/{school_code}/%/{year}"
        $lastRecord = self::where($column, 'like', "%/{$type}/{$schoolCode}/%/{$year}")
            ->orderBy($column, 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastRecord) {
            $lastNumber = explode('/', $lastRecord->$column)[0];
            if (is_numeric($lastNumber)) {
                $nextNumber = (int)$lastNumber + 1;
            }
        }

        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        return "{$formattedNumber}/{$type}/{$schoolCode}/{$monthRoman}/{$year}";
    }
}
