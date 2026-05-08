<?php

namespace App\Imports;

use App\Models\PerformanceIndicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PerformanceIndicatorImport implements ToCollection, WithHeadingRow
{
    protected $imported = 0;
    protected $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $category = trim($row['kategori'] ?? $row['category'] ?? '');
            $text = trim($row['indikator'] ?? $row['indicator_text'] ?? $row['pertanyaan'] ?? '');
            $weight = $row['bobot'] ?? $row['weight'] ?? 1;
            $role = trim($row['target_role'] ?? $row['role'] ?? 'guru');

            if (empty($category) || empty($text)) {
                $this->skipped++;
                continue;
            }

            // Avoid duplicates
            $exists = PerformanceIndicator::where('category', $category)
                ->where('indicator_text', $text)
                ->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            PerformanceIndicator::create([
                'category'       => $category,
                'indicator_text' => $text,
                'weight'         => $weight,
                'target_role'    => $role,
            ]);

            $this->imported++;
        }
    }

    public function getImported(): int
    {
        return $this->imported;
    }

    public function getSkipped(): int
    {
        return $this->skipped;
    }
}
