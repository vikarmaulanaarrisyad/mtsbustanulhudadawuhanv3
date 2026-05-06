<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            // CATEGORY: PENDIDIK (Untuk Jabatan Utama)
            ['name' => 'Guru Mapel', 'code' => 'GURU', 'category' => 'pendidik', 'description' => 'Tenaga Pendidik Mata Pelajaran', 'is_signer' => false, 'sort_order' => 10],
            ['name' => 'Guru Kelas', 'code' => 'GURU-KELAS', 'category' => 'pendidik', 'description' => 'Tenaga Pendidik Guru Kelas', 'is_signer' => false, 'sort_order' => 11],
            ['name' => 'Guru BK', 'code' => 'GURU-BK', 'category' => 'pendidik', 'description' => 'Guru Bimbingan Konseling', 'is_signer' => false, 'sort_order' => 12],
            ['name' => 'Guru Tahfidz', 'code' => 'GURU-TAHFIDZ', 'category' => 'pendidik', 'description' => 'Pengajar Tahfidz Al-Quran', 'is_signer' => false, 'sort_order' => 13],
            ['name' => 'Staf Tata Usaha', 'code' => 'STAF-TU', 'category' => 'pendidik', 'description' => 'Tenaga Administrasi Sekolah', 'is_signer' => false, 'sort_order' => 14],
            ['name' => 'Operator EMIS/Simpatika', 'code' => 'OPERATOR', 'category' => 'pendidik', 'description' => 'Pengelola Data Madrasah', 'is_signer' => false, 'sort_order' => 15],
            
            // CATEGORY: STRUKTURAL (Untuk Tugas Tambahan)
            ['name' => 'Kepala Madrasah', 'code' => 'KAMAD', 'category' => 'struktural', 'description' => 'Pimpinan tertinggi di Madrasah', 'is_signer' => true, 'sort_order' => 1],
            ['name' => 'Waka Kurikulum', 'code' => 'WAKA-KUR', 'category' => 'struktural', 'description' => 'Wakil Kepala Bidang Kurikulum', 'is_signer' => false, 'sort_order' => 2],
            ['name' => 'Waka Kesiswaan', 'code' => 'WAKA-KES', 'category' => 'struktural', 'description' => 'Wakil Kepala Bidang Kesiswaan', 'is_signer' => false, 'sort_order' => 3],
            ['name' => 'Waka Sarana Prasarana', 'code' => 'WAKA-SARPRAS', 'category' => 'struktural', 'description' => 'Wakil Kepala Bidang Sarana Prasarana', 'is_signer' => false, 'sort_order' => 4],
            ['name' => 'Waka Humas', 'code' => 'WAKA-HUMAS', 'category' => 'struktural', 'description' => 'Wakil Kepala Bidang Hubungan Masyarakat', 'is_signer' => false, 'sort_order' => 5],
            ['name' => 'Kepala Tata Usaha', 'code' => 'KA-TU', 'category' => 'struktural', 'description' => 'Kepala Urusan Tata Usaha', 'is_signer' => true, 'sort_order' => 6],
            ['name' => 'Bendahara Madrasah', 'code' => 'BENDAHARA', 'category' => 'struktural', 'description' => 'Pengelola Keuangan Madrasah', 'is_signer' => false, 'sort_order' => 7],
            ['name' => 'Bendahara BOS', 'code' => 'BENDAHARA-BOS', 'category' => 'struktural', 'description' => 'Pengelola Dana BOS', 'is_signer' => false, 'sort_order' => 8],
            ['name' => 'Wali Kelas', 'code' => 'WALIKELAS', 'category' => 'struktural', 'description' => 'Pembimbing Kelas', 'is_signer' => false, 'sort_order' => 20],
            ['name' => 'Kepala Perpustakaan', 'code' => 'KA-PERPUS', 'category' => 'struktural', 'description' => 'Pengelola Perpustakaan', 'is_signer' => false, 'sort_order' => 21],
            ['name' => 'Kepala Laboratorium', 'code' => 'KA-LAB', 'category' => 'struktural', 'description' => 'Pengelola Laboratorium', 'is_signer' => false, 'sort_order' => 22],
            ['name' => 'Pembina OSIS', 'code' => 'PEMBINA-OSIS', 'category' => 'struktural', 'description' => 'Pembimbing Organisasi Siswa', 'is_signer' => false, 'sort_order' => 23],
            ['name' => 'Pembina Pramuka', 'code' => 'PEMBINA-PRAMUKA', 'category' => 'struktural', 'description' => 'Pembimbing Ekstrakurikuler Pramuka', 'is_signer' => false, 'sort_order' => 24],
            ['name' => 'Kepala Asrama', 'code' => 'KA-ASRAMA', 'category' => 'struktural', 'description' => 'Pengelola Pondok/Asrama (Jika ada)', 'is_signer' => false, 'sort_order' => 25],
            
            // Lain-lain (Struktural)
            ['name' => 'Petugas Kebersihan', 'code' => 'KEBERSIHAN', 'category' => 'struktural', 'description' => 'Staf Kebersihan Madrasah', 'is_signer' => false, 'sort_order' => 30],
            ['name' => 'Penjaga Keamanan / Satpam', 'code' => 'SECURITY', 'category' => 'struktural', 'description' => 'Petugas Keamanan', 'is_signer' => false, 'sort_order' => 31],
        ];

        foreach ($positions as $pos) {
            Position::updateOrCreate(['name' => $pos['name']], $pos);
        }
    }
}
