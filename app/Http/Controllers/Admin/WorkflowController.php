<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index()
    {
        $semester1 = [
            [
                'title' => 'Konfigurasi Tahun Ajaran',
                'description' => 'Pastikan Tahun Akademik dan Semester aktif sudah diatur dengan benar.',
                'route' => 'academic-years.index',
                'icon' => 'fas fa-calendar-alt',
                'color' => 'primary'
            ],
            [
                'title' => 'Manajemen SDM (Guru & Staf)',
                'description' => 'Update data guru, staf, dan penempatan jabatan struktural.',
                'route' => 'teachers.index',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'success'
            ],
            [
                'title' => 'Pengaturan Rombongan Belajar',
                'description' => 'Buat rombel baru dan tentukan wali kelas untuk setiap kelas.',
                'route' => 'class-groups.index',
                'icon' => 'fas fa-users-class',
                'color' => 'info'
            ],
            [
                'title' => 'Proses PPDB & Penempatan',
                'description' => 'Selesaikan verifikasi pendaftar baru dan tempatkan ke rombel kelas 1.',
                'route' => 'ppdb.index',
                'icon' => 'fas fa-user-plus',
                'color' => 'warning'
            ],
            [
                'title' => 'Kurikulum & Jadwal',
                'description' => 'Atur mata pelajaran, jam pelajaran, dan susun jadwal mingguan.',
                'route' => 'class-schedules.index',
                'icon' => 'fas fa-clock',
                'color' => 'danger'
            ],
            [
                'title' => 'Input Penilaian Semester 1',
                'description' => 'Input nilai harian, PTS, dan PAS Ganjil untuk pengisian rapor.',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-file-invoice',
                'color' => 'secondary'
            ],
            [
                'title' => 'Cetak Rapor Semester 1',
                'description' => 'Finalisasi nilai dan cetak buku rapor semester ganjil.',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-print',
                'color' => 'dark'
            ],
        ];

        $semester2 = [
            [
                'title' => 'Aktivasi Semester Genap',
                'description' => 'Ganti status semester aktif menjadi Semester Genap.',
                'route' => 'academic-years.index',
                'icon' => 'fas fa-toggle-on',
                'color' => 'primary'
            ],
            [
                'title' => 'Penyesuaian Jadwal (Opsional)',
                'description' => 'Lakukan update jadwal pelajaran jika ada perubahan di semester 2.',
                'route' => 'class-schedules.index',
                'icon' => 'fas fa-sync-alt',
                'color' => 'info'
            ],
            [
                'title' => 'Administrasi Harian Genap',
                'description' => 'Lanjutkan monitoring absensi dan jurnal mengajar guru.',
                'route' => 'admin.teaching-journals.index',
                'icon' => 'fas fa-book-open',
                'color' => 'success'
            ],
            [
                'title' => 'Persiapan Ujian Madrasah',
                'description' => 'Input nilai Ujian Madrasah untuk siswa kelas akhir (Kelas 6).',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-graduation-cap',
                'color' => 'danger'
            ],
            [
                'title' => 'Kenaikan Kelas & Kelulusan',
                'description' => 'Proses kenaikan kelas untuk kelas 1-5 dan kelulusan kelas 6.',
                'route' => 'promotions.index',
                'icon' => 'fas fa-user-graduate',
                'color' => 'warning'
            ],
            [
                'title' => 'Cetak Rapor Semester 2',
                'description' => 'Finalisasi nilai akhir tahun dan cetak rapor semester genap.',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-file-pdf',
                'color' => 'secondary'
            ],
        ];

        return view('admin.workflow.index', compact('semester1', 'semester2'));
    }
}
