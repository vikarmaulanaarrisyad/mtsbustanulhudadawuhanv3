<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['id' => 1, 'student_status_name' => 'Aktif'],
            ['id' => 2, 'student_status_name' => 'Lulus'],
            ['id' => 3, 'student_status_name' => 'Pindahan Masuk'],
            ['id' => 4, 'student_status_name' => 'Mutasi Keluar'],
            ['id' => 5, 'student_status_name' => 'Dikeluarkan'],
            ['id' => 6, 'student_status_name' => 'Putus Sekolah'],
        ];

        foreach ($statuses as $status) {
            DB::table('student_statuses')->updateOrInsert(['id' => $status['id']], $status);
        }
    }
}
