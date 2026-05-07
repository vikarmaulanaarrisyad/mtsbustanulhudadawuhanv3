<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionGroups = [
            ['name' => 'Dashboard', 'prefix' => 'dashboard', 'icon' => 'fa-tachometer-alt'],
            ['name' => 'PPDB Online', 'prefix' => 'ppdb', 'icon' => 'fa-user-plus'],
            ['name' => 'Data Guru & Staf', 'prefix' => 'teacher', 'icon' => 'fa-users'],
            ['name' => 'Data Siswa', 'prefix' => 'student', 'icon' => 'fa-user-graduate'],
            ['name' => 'Data Alumni', 'prefix' => 'alumni', 'icon' => 'fa-user-tag'],
            ['name' => 'Manajemen Kelas', 'prefix' => 'class-group', 'icon' => 'fa-school'],
            ['name' => 'Mata Pelajaran', 'prefix' => 'subjects', 'icon' => 'fa-book'],
            ['name' => 'Jadwal Pelajaran', 'prefix' => 'class-schedules', 'icon' => 'fa-calendar-alt'],
            ['name' => 'Nilai Siswa', 'prefix' => 'grades', 'icon' => 'fa-file-invoice'],
            ['name' => 'Presensi Guru', 'prefix' => 'teacher-attendance', 'icon' => 'fa-user-clock'],
            ['name' => 'Presensi Siswa', 'prefix' => 'student-attendance', 'icon' => 'fa-clock'],
            ['name' => 'Layanan Surat', 'prefix' => 'mail', 'icon' => 'fa-envelope-open-text'],
            ['name' => 'Berita & Konten', 'prefix' => 'posts', 'icon' => 'fa-newspaper'],
            ['name' => 'Pengaturan App', 'prefix' => 'setting', 'icon' => 'fa-cogs'],
            ['name' => 'Manajemen User', 'prefix' => 'user', 'icon' => 'fa-user-cog'],
            ['name' => 'Role & Izin', 'prefix' => 'role', 'icon' => 'fa-user-lock'],
            ['name' => 'Izin Langsung', 'prefix' => 'permission', 'icon' => 'fa-key'],
            ['name' => 'Grup Izin', 'prefix' => 'permission-group', 'icon' => 'fa-layer-group'],
            ['name' => 'Tahun Akademik', 'prefix' => 'academic-year', 'icon' => 'fa-calendar-check'],
            ['name' => 'Konfigurasi', 'prefix' => 'config', 'icon' => 'fa-wrench'],
            ['name' => 'Izin Guru', 'prefix' => 'teacher-permit', 'icon' => 'fa-envelope-open-text'],
            ['name' => 'Izin Siswa', 'prefix' => 'student-permit', 'icon' => 'fa-envelope-open-text'],
            ['name' => 'Regis Wajah', 'prefix' => 'face-registration', 'icon' => 'fa-camera'],
            ['name' => 'Presensi Harian', 'prefix' => 'daily-attendance', 'icon' => 'fa-clock'],
            ['name' => 'Pengumuman', 'prefix' => 'announcement', 'icon' => 'fa-bullhorn'],
            ['name' => 'Agenda Sekolah', 'prefix' => 'school-agenda', 'icon' => 'fa-calendar-day'],
            ['name' => 'Log Perilaku', 'prefix' => 'behavior-log', 'icon' => 'fa-vial'],
            ['name' => 'Gaji & Payroll', 'prefix' => 'payroll', 'icon' => 'fa-money-check-alt'],
            ['name' => 'Jabatan', 'prefix' => 'position', 'icon' => 'fa-briefcase'],
            ['name' => 'Backup System', 'prefix' => 'backup', 'icon' => 'fa-database'],
            ['name' => 'PWA Settings', 'prefix' => 'pwa', 'icon' => 'fa-mobile-alt'],
            ['name' => 'Album Galeri', 'prefix' => 'albums', 'icon' => 'fa-images'],
            ['name' => 'Slider Gambar', 'prefix' => 'image-sliders', 'icon' => 'fa-sliders-h'],
            ['name' => 'Quotes', 'prefix' => 'quotes', 'icon' => 'fa-quote-left'],
            ['name' => 'Halaman Statis', 'prefix' => 'pages', 'icon' => 'fa-file-alt'],
            ['name' => 'Mutabaah & Tahfidz', 'prefix' => 'tahfidz', 'icon' => 'fa-quran'],
        ];

        foreach ($permissionGroups as $data) {
            PermissionGroup::updateOrCreate(
                ['name' => $data['name']],
                [
                    'prefix' => $data['prefix'],
                    'icon' => $data['icon'],
                ]
            );
        }
    }
}
