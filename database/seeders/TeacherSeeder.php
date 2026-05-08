<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Create Kepala Madrasah (1)
        $this->createTeacher($faker, 'Kepala Madrasah', 'kamad', 'Admin');

        // 2. Create Bendahara (1)
        $this->createTeacher($faker, 'Bendahara Madrasah', 'bendahara', 'Admin');

        // 3. Create Teachers (15)
        for ($i = 1; $i <= 15; $i++) {
            $this->createTeacher($faker, 'Guru Mapel', 'guru' . $i, 'Guru');
        }
    }

    /**
     * Helper to create teacher and user
     */
    private function createTeacher($faker, $position, $username, $role)
    {
        $name = $faker->name;
        $email = $username . '@example.com';

        // Use updateOrCreate to avoid duplicates if seeder is run multiple times
        $user = User::updateOrCreate(
            ['username' => $username],
            [
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
            ]
        );

        // Sync role
        $user->syncRoles([$role]);

        // Create or Update Teacher
        Teacher::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $faker->unique()->numerify('##################'),
                'nik' => $faker->unique()->numerify('################'),
                'name' => $name,
                'position' => $position,
                'gender' => $faker->randomElement(['L', 'P']),
                'place_of_birth' => $faker->city,
                'date_of_birth' => $faker->date('Y-m-d', '1995-01-01'),
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'employment_status' => $faker->randomElement(['GTY', 'PTY', 'Honorer']),
                'education' => 'S1',
                'major' => $faker->randomElement(['Pendidikan Agama Islam', 'Pendidikan Matematika', 'Pendidikan Bahasa Indonesia', 'Pendidikan Bahasa Inggris']),
                'university' => $faker->randomElement(['IAIN Kediri', 'UNISKA', 'UNP Kediri', 'UIN Maliki Malang']),
                'start_date' => $faker->date('Y-m-d', '2020-01-01'),
                'base_salary' => $faker->randomElement([1500000, 2000000, 2500000]),
            ]
        );
    }
}
