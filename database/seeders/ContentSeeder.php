<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Models\Album;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::whereHas('roles', function($q) {
            $q->where('name', 'Super Admin');
        })->first() ?? User::first();

        if (!$user) return;

        // 1. Kategori Berita & Artikel
        $categories = [
            ['category_name' => 'Berita', 'category_slug' => 'berita', 'category_description' => 'Kumpulan berita terbaru madrasah.'],
            ['category_name' => 'Artikel', 'category_slug' => 'artikel', 'category_description' => 'Artikel pendidikan dan keagamaan.'],
            ['category_name' => 'Pengumuman', 'category_slug' => 'pengumuman', 'category_description' => 'Pengumuman resmi madrasah.'],
            ['category_name' => 'Prestasi', 'category_slug' => 'prestasi', 'category_description' => 'Catatan prestasi siswa dan guru.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['category_slug' => $cat['category_slug']], $cat);
        }

        $beritaCat = Category::where('category_slug', 'berita')->first();

        // 2. Artikel / Berita (Posts)
        $posts = [
            [
                'post_title' => 'Pendaftaran Siswa Baru TP 2024/2025 Resmi Dibuka',
                'post_content' => 'Selamat datang calon siswa baru MI Bustanul Huda. Pendaftaran tahun ini dapat dilakukan secara online melalui portal PPDB kami...',
                'post_type' => 'post',
                'post_status' => 'publish',
            ],
            [
                'post_title' => 'MI Bustanul Huda Raih Juara Umum Lomba PORSENI',
                'post_content' => 'Alhamdulillah, siswa-siswi kami berhasil memborong piala dalam ajang PORSENI tingkat kecamatan tahun ini...',
                'post_type' => 'post',
                'post_status' => 'publish',
            ],
            [
                'post_title' => 'Tips Mendampingi Anak Belajar di Rumah',
                'post_content' => 'Belajar di rumah memerlukan suasana yang nyaman dan dukungan penuh dari orang tua. Berikut adalah beberapa tips yang dapat dilakukan...',
                'post_type' => 'post',
                'post_status' => 'publish',
            ],
        ];

        foreach ($posts as $p) {
            $post = Post::updateOrCreate(
                ['post_slug' => Str::slug($p['post_title'])],
                array_merge($p, [
                    'post_slug' => Str::slug($p['post_title']),
                    'user_id' => $user->id,
                ])
            );
            $post->categories()->sync([$beritaCat->id]);
        }

        // 3. Halaman Statis (Pages)
        $pagesData = [
            ['title' => 'Profil Madrasah', 'body' => 'MI Bustanul Huda adalah lembaga pendidikan tingkat dasar yang berkomitmen mencetak generasi Qurani dan berwawasan luas.'],
            ['title' => 'Visi dan Misi', 'body' => 'Visi: Mewujudkan madrasah yang unggul, islami, dan berbudaya lingkungan. Misi: Melaksanakan pembelajaran yang inovatif...'],
            ['title' => 'Sejarah Singkat', 'body' => 'Berdiri sejak tahun 1980-an, MI Bustanul Huda terus berkembang pesat dalam melayani kebutuhan pendidikan masyarakat sekitar.'],
            ['title' => 'Sarana Prasarana', 'body' => 'Kami memiliki fasilitas pendukung lengkap seperti Laboratorium Komputer, Perpustakaan Digital, dan Ruang Kelas ber-AC.'],
        ];

        foreach ($pagesData as $pg) {
            Page::updateOrCreate(['slug' => Str::slug($pg['title'])], [
                'title' => $pg['title'],
                'slug' => Str::slug($pg['title']),
                'body' => $pg['body'],
            ]);
        }

        // 4. Galeri Foto (Albums)
        $albums = [
            ['album_title' => 'Kegiatan Ekstrakurikuler', 'album_description' => 'Foto-foto kegiatan Pramuka, Drumband, dan Seni Tari.'],
            ['album_title' => 'Fasilitas Madrasah', 'album_description' => 'Tampilan gedung dan ruang kelas MI Bustanul Huda.'],
            ['album_title' => 'Wisuda Purna Siswa', 'album_description' => 'Momen kebahagiaan kelulusan siswa kelas 6.'],
        ];

        foreach ($albums as $alb) {
            Album::updateOrCreate(['album_slug' => Str::slug($alb['album_title'])], [
                'album_title' => $alb['album_title'],
                'album_slug' => Str::slug($alb['album_title']),
                'album_description' => $alb['album_description'],
            ]);
        }

        // 5. Manajemen Menu
        // Bersihkan menu lama jika perlu, atau gunakan updateOrCreate
        $menus = [
            ['title' => 'Beranda', 'url' => '/', 'pos' => 1],
            ['title' => 'Profil', 'url' => '#', 'pos' => 2],
            ['title' => 'Berita', 'url' => '/posts', 'pos' => 3],
            ['title' => 'Galeri', 'url' => '/albums', 'pos' => 4],
            ['title' => 'PPDB Online', 'url' => '/ppdb', 'pos' => 5],
            ['title' => 'Kontak', 'url' => '/contact', 'pos' => 6],
        ];

        foreach ($menus as $m) {
            $menu = Menu::updateOrCreate(['menu_slug' => Str::slug($m['title'])], [
                'menu_title' => $m['title'],
                'menu_slug' => Str::slug($m['title']),
                'menu_url' => $m['url'],
                'menu_position' => $m['pos'],
            ]);

            if ($m['title'] === 'Profil') {
                $subMenus = [
                    ['title' => 'Visi & Misi', 'url' => '/p/visi-dan-misi', 'pos' => 1],
                    ['title' => 'Sejarah', 'url' => '/p/sejarah-singkat', 'pos' => 2],
                    ['title' => 'Fasilitas', 'url' => '/p/sarana-prasarana', 'pos' => 3],
                ];
                foreach ($subMenus as $sm) {
                    Menu::updateOrCreate(['menu_slug' => Str::slug($sm['title'])], [
                        'menu_title' => $sm['title'],
                        'menu_slug' => Str::slug($sm['title']),
                        'menu_url' => $sm['url'],
                        'menu_parent_id' => $menu->id,
                        'menu_position' => $sm['pos'],
                    ]);
                }
            }

            if ($m['title'] === 'PPDB Online') {
                $subMenus = [
                    ['title' => 'Form Pendaftaran', 'url' => '/ppdb', 'pos' => 1],
                    ['title' => 'Monitoring PPDB', 'url' => '/ppdb/monitoring', 'pos' => 2],
                ];
                foreach ($subMenus as $sm) {
                    Menu::updateOrCreate(['menu_slug' => Str::slug($sm['title'])], [
                        'menu_title' => $sm['title'],
                        'menu_slug' => Str::slug($sm['title']),
                        'menu_url' => $sm['url'],
                        'menu_parent_id' => $menu->id,
                        'menu_position' => $sm['pos'],
                    ]);
                }
            }
        }
    }
}
