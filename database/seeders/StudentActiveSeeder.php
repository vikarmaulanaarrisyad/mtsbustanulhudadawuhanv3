<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudentStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StudentActiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Dapatkan Tahun Akademik Aktif
        $ay = AcademicYear::where('current_semester', 1)->first() ?? AcademicYear::first();
        if (!$ay) return;

        // 2. Dapatkan Status Siswa Aktif
        $statusActive = StudentStatus::where('student_status_name', 'Aktif')->first() ?? StudentStatus::create(['student_status_name' => 'Aktif']);

        // 3. Dapatkan Semua Rombel/Kelas yang tersedia
        $classes = ClassGroup::where('academic_year_id', $ay->id)->get();

        if ($classes->isEmpty()) {
            $this->command->error('Data Rombel tidak ditemukan! Jalankan ClassGroupSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Memulai generate siswa aktif dan penempatan rombel (maks 28 per kelas)...');

        foreach ($classes as $class) {
            // Targetkan 28 siswa per rombel
            $targetCount = 28;
            
            for ($i = 0; $i < $targetCount; $i++) {
                $name = $faker->name;
                $nis = Student::generateNIS();
                
                // Buat user untuk login siswa
                $user = User::create([
                    'name' => $name,
                    'username' => $nis,
                    'email' => $nis . '@student.example.com',
                    'password' => Hash::make('password'),
                ]);

                try {
                    $user->assignRole('Siswa');
                } catch (\Exception $e) {
                    // Abaikan jika role tidak ditemukan
                }

                // Buat data siswa
                $student = Student::create([
                    'user_id' => $user->id,
                    'academic_year_id' => $ay->id,
                    'student_status_id' => $statusActive->id,
                    'student_class_group_id' => $class->id,
                    'nama_lengkap' => $name,
                    'nis' => $nis,
                    'nisn' => $faker->unique()->numerify('##########'),
                    'nik' => $faker->unique()->numerify('################'),
                    'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                    'tempat_lahir' => $faker->city,
                    'tanggal_lahir' => $faker->date('Y-m-d', '2015-12-31'),
                    'is_active' => true,
                    'tanggal_masuk' => date('Y-m-d', strtotime('-1 year')),
                ]);

                // Tambahkan ke riwayat kelas
                $student->histories()->create([
                    'academic_year_id' => $ay->id,
                    'class_group_id' => $class->id,
                    'status' => 'enrolled',
                ]);
            }
            $this->command->info("Rombel {$class->class_group} {$class->sub_class_group} berhasil diisi dengan 28 siswa.");
        }

        $this->command->info('Selesai! Seluruh siswa aktif telah ditempatkan ke rombel masing-masing.');
    }
}
