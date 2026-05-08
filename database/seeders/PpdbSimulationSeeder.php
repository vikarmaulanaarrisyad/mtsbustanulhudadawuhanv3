<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PpdbRegistrant;
use App\Models\StudentAdmission;
use App\Models\AdmissionPhase;
use App\Models\AdmissionType;
use App\Models\AcademicYear;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PpdbSimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Dapatkan atau Buat Tahun Akademik Aktif
        $ay = AcademicYear::where('is_active', true)->first() ?? AcademicYear::create([
            'year' => date('Y') . '/' . (date('Y') + 1),
            'is_active' => true
        ]);

        // 2. Pastikan ada Data Master Admission
        $admission = StudentAdmission::first() ?? StudentAdmission::create([
            'academic_year_id' => $ay->id,
            'admission_start_date' => now()->subMonth(),
            'admission_end_date' => now()->addMonth(),
            'announcement_start_date' => now()->addMonth(),
        ]);

        // 3. Pastikan ada Gelombang (Phase)
        $phase = AdmissionPhase::where('student_admission_id', $admission->id)->first() ?? AdmissionPhase::create([
            'student_admission_id' => $admission->id,
            'name' => 'Gelombang 1 (Simulasi)',
            'phase_start_date' => now()->subMonth(),
            'phase_end_date' => now()->addMonth(),
            'announcement_date' => now()->addMonth(),
        ]);

        // 4. Pastikan ada Jalur (Type)
        $type = AdmissionType::first() ?? AdmissionType::create([
            'admission_type_name' => 'Reguler',
            'is_active' => true
        ]);

        $this->command->info('Mulai generate 200 data simulasi PPDB...');

        for ($i = 1; $i <= 200; $i++) {
            // Create user untuk pendaftar
            $name = $faker->name;
            $email = Str::slug($name) . $i . '@simulasi-ppdb.com';
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'username' => 'REG-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
            ]);

            // Assign role jika ada role pendaftar
            try {
                $user->assignRole('Siswa');
            } catch (\Exception $e) {
                // Skip jika role tidak ditemukan
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
                'tanggal_lahir' => $faker->date('Y-m-d', '2012-12-31'),
                'asal_sekolah' => 'SMP ' . $faker->city,
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'no_hp_ortu' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'status' => 'pending', // Menunggu Verifikasi
                'average_score' => $faker->randomFloat(2, 75, 95),
                'distance_km' => $faker->randomFloat(2, 0.5, 15),
                'selection_score' => $faker->randomFloat(2, 70, 100),
                'created_at' => now()->subMinutes(rand(1, 40000)),
            ]);

            if ($i % 50 == 0) {
                $this->command->info("Telah memproses $i data pendaftar...");
            }
        }

        $this->command->info('Berhasil membuat 200 data pendaftar PPDB baru dengan status Menunggu Verifikasi.');
    }
}
