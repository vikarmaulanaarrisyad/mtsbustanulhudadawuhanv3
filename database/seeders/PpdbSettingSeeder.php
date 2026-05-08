<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\StudentAdmission;
use App\Models\AdmissionPhase;
use App\Models\AdmissionType;
use App\Models\AdmissionQuotas;
use App\Models\PpdbPaymentItem;
use Illuminate\Database\Seeder;

class PpdbSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dapatkan Tahun Akademik Aktif
        $ay = AcademicYear::where('current_semester', 1)->first() ?? AcademicYear::first();
        if (!$ay) return;

        // 2. Pengaturan Umum PPDB (StudentAdmission)
        $admission = StudentAdmission::updateOrCreate(
            ['academic_year_id' => $ay->id],
            [
                'admission_year' => date('Y'),
                'admission_status' => 'open',
                'admission_start_date' => date('Y') . '-01-01',
                'admission_end_date' => date('Y') . '-12-31',
                'announcement_start_date' => date('Y') . '-07-01',
                'announcement_end_date' => date('Y') . '-07-07',
            ]
        );

        // 3. Jalur Pendaftaran (AdmissionType)
        $types = [
            'Reguler',
            'Prestasi',
            'Afirmasi',
            'Mutasi Orang Tua'
        ];

        $typeModels = [];
        foreach ($types as $typeName) {
            $typeModels[] = AdmissionType::updateOrCreate(
                [
                    'academic_year_id' => $ay->id, 
                    'admission_type_name' => $typeName
                ],
                ['admission_type_name' => $typeName]
            );
        }

        // 4. Gelombang Pendaftaran (AdmissionPhase) 1-3
        $phases = [
            [
                'name' => 'Gelombang 1',
                'start' => date('Y') . '-01-01',
                'end' => date('Y') . '-03-31',
                'announcement' => date('Y') . '-04-05',
            ],
            [
                'name' => 'Gelombang 2',
                'start' => date('Y') . '-04-01',
                'end' => date('Y') . '-06-30',
                'announcement' => date('Y') . '-07-05',
            ],
            [
                'name' => 'Gelombang 3',
                'start' => date('Y') . '-07-01',
                'end' => date('Y') . '-08-31',
                'announcement' => date('Y') . '-09-05',
            ],
        ];

        foreach ($phases as $phaseData) {
            $phase = AdmissionPhase::updateOrCreate(
                [
                    'academic_year_id' => $ay->id, 
                    'phase_name' => $phaseData['name']
                ],
                [
                    'phase_start_date' => $phaseData['start'],
                    'phase_end_date' => $phaseData['end'],
                    'announcement_date' => $phaseData['announcement'],
                ]
            );

            // 5. Kuota Pendaftaran (AdmissionQuotas)
            foreach ($typeModels as $typeModel) {
                AdmissionQuotas::updateOrCreate(
                    [
                        'academic_year_id' => $ay->id,
                        'admission_phase_id' => $phase->id,
                        'admission_types_id' => $typeModel->id,
                    ],
                    [
                        'quota' => ($typeModel->admission_type_name === 'Reguler') ? 100 : 25,
                    ]
                );
            }
        }

        // 6. Master Biaya PPDB (PpdbPaymentItem)
        $paymentItems = [
            ['name' => 'Pendaftaran', 'amount' => 150000, 'desc' => 'Biaya pendaftaran awal'],
            ['name' => 'Seragam', 'amount' => 850000, 'desc' => 'Biaya seragam sekolah lengkap'],
            ['name' => 'Masa Ta\'aruf Siswa (MATSAMA)', 'amount' => 100000, 'desc' => 'Kegiatan pengenalan madrasah'],
            ['name' => 'Infaq Bangunan', 'amount' => 500000, 'desc' => 'Sumbangan pengembangan sarana'],
        ];

        foreach ($paymentItems as $item) {
            PpdbPaymentItem::updateOrCreate(
                [
                    'academic_year_id' => $ay->id, 
                    'item_name' => $item['name']
                ],
                [
                    'amount' => $item['amount'],
                    'description' => $item['desc'],
                    'is_active' => true,
                ]
            );
        }
    }
}
