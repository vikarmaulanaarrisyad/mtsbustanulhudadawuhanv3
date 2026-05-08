<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerformanceIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            // Pedagogik
            ['category' => 'Pedagogik', 'indicator_text' => 'Menguasai karakteristik peserta didik'],
            ['category' => 'Pedagogik', 'indicator_text' => 'Menguasai teori belajar dan prinsip pembelajaran'],
            ['category' => 'Pedagogik', 'indicator_text' => 'Pengembangan kurikulum'],
            ['category' => 'Pedagogik', 'indicator_text' => 'Kegiatan pembelajaran yang mendidik'],
            ['category' => 'Pedagogik', 'indicator_text' => 'Pengembangan potensi peserta didik'],
            ['category' => 'Pedagogik', 'indicator_text' => 'Komunikasi dengan peserta didik'],
            ['category' => 'Pedagogik', 'indicator_text' => 'Penilaian dan evaluasi'],

            // Kepribadian
            ['category' => 'Kepribadian', 'indicator_text' => 'Bertindak sesuai dengan norma agama, hukum, sosial, dan kebudayaan nasional'],
            ['category' => 'Kepribadian', 'indicator_text' => 'Menunjukkan pribadi yang dewasa dan teladan'],
            ['category' => 'Kepribadian', 'indicator_text' => 'Etos kerja, tanggung jawab yang tinggi, rasa bangga menjadi guru'],

            // Sosial
            ['category' => 'Sosial', 'indicator_text' => 'Bersikap inklusif, bertindak obyektif, serta tidak diskriminatif'],
            ['category' => 'Sosial', 'indicator_text' => 'Komunikasi dengan sesama guru, tenaga kependidikan, orang tua, dan masyarakat'],

            // Profesional
            ['category' => 'Profesional', 'indicator_text' => 'Penguasaan materi, struktur, konsep, dan pola pikir keilmuan yang mendukung mata pelajaran yang diampu'],
            ['category' => 'Profesional', 'indicator_text' => 'Mengembangkan keprofesionalan melalui tindakan yang reflektif'],
        ];

        foreach ($indicators as $indicator) {
            \App\Models\PerformanceIndicator::updateOrCreate(
                ['indicator_text' => $indicator['indicator_text']],
                $indicator
            );
        }
    }
}
