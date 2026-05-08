<?php

namespace Database\Seeders;

use App\Models\PpdbRegistrant;
use App\Models\StudentAdmission;
use App\Models\AdmissionPhase;
use App\Models\AdmissionType;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PpdbBulkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Dapatkan Tahun Akademik Aktif
        $ay = AcademicYear::where('current_semester', 1)->first() ?? AcademicYear::first();
        if (!$ay) {
            $this->command->error('Tahun Akademik tidak ditemukan. Jalankan AcademicYearSeeder terlebih dahulu.');
            return;
        }

        // 2. Pastikan ada Data Master Admission, Phase, dan Type
        $admission = StudentAdmission::where('academic_year_id', $ay->id)->first() ?? StudentAdmission::create([
            'academic_year_id' => $ay->id,
            'admission_year' => date('Y'),
            'admission_status' => 'open',
            'admission_start_date' => now()->subMonth(),
            'admission_end_date' => now()->addMonth(),
        ]);

        $phase = AdmissionPhase::where('academic_year_id', $ay->id)->first() ?? AdmissionPhase::create([
            'academic_year_id' => $ay->id,
            'phase_name' => 'Gelombang 1',
            'phase_start_date' => now()->subMonth(),
            'phase_end_date' => now()->addMonth(),
        ]);

        $type = AdmissionType::where('academic_year_id', $ay->id)->first() ?? AdmissionType::create([
            'academic_year_id' => $ay->id,
            'admission_type_name' => 'Reguler',
        ]);

        $this->command->info('Memulai generate 175 data pendaftar PPDB...');

        // 100 Berkas Lengkap
        $this->generateRegistrants($faker, 100, PpdbRegistrant::STATUS_BERKAS_LENGKAP, $admission, $phase, $type);
        $this->command->info('100 Data Berkas Lengkap berhasil dibuat.');

        // 50 Menunggu / Pending
        $this->generateRegistrants($faker, 50, PpdbRegistrant::STATUS_PENDING, $admission, $phase, $type);
        $this->command->info('50 Data Menunggu (Pending) berhasil dibuat.');

        // 25 Berkas Belum Lengkap
        $this->generateRegistrants($faker, 25, PpdbRegistrant::STATUS_BERKAS_TIDAK_LENGKAP, $admission, $phase, $type);
        $this->command->info('25 Data Berkas Belum Lengkap berhasil dibuat.');

        $this->command->info('Selesai! Total 175 data pendaftar baru kelas 1 berhasil dibuat.');
    }

    /**
     * Helper to generate registrants
     */
    private function generateRegistrants($faker, $count, $status, $admission, $phase, $type)
    {
        for ($i = 0; $i < $count; $i++) {
            $name = $faker->name;
            $username = 'REG-' . date('Y') . '-' . Str::upper(Str::random(4)) . rand(100, 999);
            
            $user = User::create([
                'name' => $name,
                'email' => Str::slug($name) . rand(1000, 9999) . '@ppdb-example.com',
                'username' => $username,
                'password' => Hash::make('password'),
            ]);

            try {
                $user->assignRole('ppdb');
            } catch (\Exception $e) {
                // Skip jika role belum ada
            }

            PpdbRegistrant::create([
                'user_id' => $user->id,
                'registration_number' => PpdbRegistrant::generateRegistrationNumber(date('Y')),
                'student_admission_id' => $admission->id,
                'admission_phase_id' => $phase->id,
                'admission_type_id' => $type->id,
                'nama_lengkap' => $name,
                'nisn' => $faker->unique()->numerify('##########'),
                'nik' => $faker->unique()->numerify('################'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2019-12-31'), // Usia ~6-7 tahun untuk MI Kelas 1
                'asal_sekolah' => 'TK ' . $faker->city,
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'no_hp_ortu' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'status' => $status,
                'average_score' => $faker->randomFloat(2, 70, 95),
                'distance_km' => $faker->randomFloat(2, 0.1, 15),
                'selection_score' => $faker->randomFloat(2, 70, 100),
                'created_at' => now()->subMinutes(rand(1, 10000)),
            ]);
        }
    }
}
