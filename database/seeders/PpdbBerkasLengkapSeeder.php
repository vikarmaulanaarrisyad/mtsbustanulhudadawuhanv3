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

class PpdbBerkasLengkapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Dapatkan Tahun Akademik
        $ay = AcademicYear::where('current_semester', 1)->first() ?? AcademicYear::first() ?? AcademicYear::create([
            'academic_year' => date('Y') . '/' . (date('Y') + 1),
            'semester_id' => 1,
            'current_semester' => 1
        ]);

        // 2. Admission Master
        $admission = StudentAdmission::where('academic_year_id', $ay->id)->first() ?? StudentAdmission::create([
            'academic_year_id' => $ay->id,
            'admission_year' => date('Y'),
            'admission_status' => 'open',
            'admission_start_date' => now()->subMonth(),
            'admission_end_date' => now()->addMonth(),
            'announcement_start_date' => now()->addMonth(),
            'announcement_end_date' => now()->addMonth()->addDays(7),
        ]);

        // 3. Gelombang
        $phase = AdmissionPhase::where('academic_year_id', $ay->id)->first() ?? AdmissionPhase::create([
            'academic_year_id' => $ay->id,
            'phase_name' => 'Gelombang 1 (Simulasi)',
            'phase_start_date' => now()->subMonth(),
            'phase_end_date' => now()->addMonth(),
            'announcement_date' => now()->addMonth(),
        ]);

        // 4. Jalur
        $type = AdmissionType::where('academic_year_id', $ay->id)->first() ?? AdmissionType::create([
            'academic_year_id' => $ay->id,
            'admission_type_name' => 'Reguler',
        ]);

        $this->command->info('Mulai generate 100 data simulasi PPDB (Berkas Lengkap)...');

        for ($i = 1; $i <= 100; $i++) {
            $name = $faker->name;
            $email = Str::slug($name) . (rand(100, 999)) . $i . '@berkas-lengkap.com';
            
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'username' => 'COMP-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'password' => Hash::make('password'),
            ]);

            try { $user->assignRole('Siswa'); } catch (\Exception $e) {}

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
                'status' => 'berkas_lengkap', // Status Berkas Lengkap
                'average_score' => $faker->randomFloat(2, 80, 98), // Skor lebih tinggi
                'distance_km' => $faker->randomFloat(2, 0.1, 10),
                'selection_score' => $faker->randomFloat(2, 80, 100),
                'created_at' => now()->subMinutes(rand(1, 20000)),
            ]);

            if ($i % 25 == 0) {
                $this->command->info("Telah memproses $i data...");
            }
        }

        $this->command->info('Berhasil membuat 100 data pendaftar PPDB dengan status Berkas Lengkap.');
    }
}
