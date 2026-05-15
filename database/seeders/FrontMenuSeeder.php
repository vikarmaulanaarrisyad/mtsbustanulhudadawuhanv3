<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Str;

class FrontMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel menu terlebih dahulu
        Menu::truncate();

        $menus = [
            [
                'menu_title' => 'Beranda',
                'menu_url' => '/',
                'menu_parent_id' => 0,
                'menu_position' => 1,
                'menu_type' => 'link',
                'sub' => []
            ],
            [
                'menu_title' => 'Profil',
                'menu_url' => '#',
                'menu_parent_id' => 0,
                'menu_position' => 2,
                'menu_type' => 'link',
                'sub' => [
                    ['title' => 'Sejarah', 'url' => '/page/sejarah'],
                    ['title' => 'Visi & Misi', 'url' => '/page/visi-misi'],
                    ['title' => 'Struktur Organisasi', 'url' => '/page/struktur-organisasi'],
                    ['title' => 'Sambutan Kepala', 'url' => '/page/sambutan-kepala-madrasah'],
                ]
            ],
            [
                'menu_title' => 'Akademik',
                'menu_url' => '#',
                'menu_parent_id' => 0,
                'menu_position' => 3,
                'menu_type' => 'link',
                'sub' => [
                    ['title' => 'Kurikulum', 'url' => '/page/kurikulum'],
                    ['title' => 'Kalender Pendidikan', 'url' => '/page/kalender-pendidikan'],
                    ['title' => 'Fasilitas Madrasah', 'url' => '/page/fasilitas'],
                ]
            ],
            [
                'menu_title' => 'Kesiswaan',
                'menu_url' => '#',
                'menu_parent_id' => 0,
                'menu_position' => 4,
                'menu_type' => 'link',
                'sub' => [
                    ['title' => 'Ekstrakurikuler', 'url' => '/page/ekstrakurikuler'],
                    ['title' => 'Organisasi Siswa (OSIS)', 'url' => '/page/osis'],
                    ['title' => 'Prestasi Siswa', 'url' => '/prestasi'],
                ]
            ],
            [
                'menu_title' => 'Berita',
                'menu_url' => '/berita',
                'menu_parent_id' => 0,
                'menu_position' => 5,
                'menu_type' => 'link',
                'sub' => []
            ],
            [
                'menu_title' => 'Prestasi',
                'menu_url' => '/prestasi',
                'menu_parent_id' => 0,
                'menu_position' => 6,
                'menu_type' => 'link',
                'sub' => []
            ],
            [
                'menu_title' => 'Galeri',
                'menu_url' => '/galeri',
                'menu_parent_id' => 0,
                'menu_position' => 7,
                'menu_type' => 'link',
                'sub' => []
            ],
            [
                'menu_title' => 'Kontak',
                'menu_url' => '/kontak',
                'menu_parent_id' => 0,
                'menu_position' => 8,
                'menu_type' => 'link',
                'sub' => []
            ],
            [
                'menu_title' => 'PPDB',
                'menu_url' => '#',
                'menu_parent_id' => 0,
                'menu_position' => 9,
                'menu_type' => 'link',
                'sub' => [
                    ['title' => 'Informasi & Daftar', 'url' => '/register'],
                    ['title' => 'Hasil Seleksi (Real-Time)', 'url' => '/ppdb/monitoring'],
                    ['title' => 'Cek Status Pendaftaran', 'url' => '/ppdb/check'],
                ]
            ],
            [
                'menu_title' => 'Login',
                'menu_url' => '/login',
                'menu_parent_id' => 0,
                'menu_position' => 10,
                'menu_type' => 'link',
                'sub' => []
            ],
        ];

        foreach ($menus as $m) {
            $parent = Menu::create([
                'menu_title' => $m['menu_title'],
                'menu_url' => $m['menu_url'],
                'menu_slug' => Str::slug($m['menu_title']),
                'menu_parent_id' => $m['menu_parent_id'],
                'menu_position' => $m['menu_position'],
                'menu_type' => $m['menu_type'],
                'menu_target' => '_self'
            ]);

            if (!empty($m['sub'])) {
                foreach ($m['sub'] as $index => $sub) {
                    Menu::create([
                        'menu_title' => $sub['title'],
                        'menu_url' => $sub['url'],
                        'menu_slug' => Str::slug($sub['title']),
                        'menu_parent_id' => $parent->id,
                        'menu_position' => $index + 1,
                        'menu_type' => 'link',
                        'menu_target' => '_self'
                    ]);
                }
            }
        }
    }
}
