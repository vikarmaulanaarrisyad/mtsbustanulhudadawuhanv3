<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PostController;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ImageSlider;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Post;
use App\Models\Quotes;
use App\Models\SchoolAgenda;
use App\Models\Tag;
use App\Models\AcademicYear;
use App\Models\StudentAdmission;
use App\Models\Album;
use App\Models\Setting;
use App\Models\Extracurricular;
use App\Models\Achievement;
use App\Models\PpdbRegistrant;
use App\Models\AdmissionQuotas;
use App\Models\AdmissionPhase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(6); // Semua post untuk halaman utama
        $quetes = Quotes::all(); // Semua quotes
        $sliders = ImageSlider::all();

        $breakingNews = Post::orderBy('created_at', 'desc')->limit(5)->get(); // 5 post terbaru

        // 👉 Ambil agenda aktif
        $agendas = SchoolAgenda::where('status', 'active')
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();

        // 👉 Cek Status PPDB
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $ppdbOpen = false;
        $ppdbRegistrants = collect();
        
        if ($academicYear) {
            $admission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();
            if ($admission && $admission->admission_status === 'open') {
                $ppdbOpen = true;
            }
            
            // Ambil data pendaftar terbaru untuk ditampilkan dengan nama disamarkan
            if ($admission) {
                $ppdbRegistrants = PpdbRegistrant::where('student_admission_id', $admission->id)
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function ($registrant) {
                        $nameParts = explode(' ', $registrant->nama_lengkap);
                        $maskedName = '';
                        foreach ($nameParts as $index => $part) {
                            if ($index == 0) {
                                $maskedName .= $part . ' '; // Nama depan utuh
                            } else {
                                $maskedName .= substr($part, 0, 1) . str_repeat('*', max(1, mb_strlen($part) - 1)) . ' ';
                            }
                        }
                        $registrant->masked_name = trim($maskedName);
                        return $registrant;
                    });
            }
        }

        // 👉 Ambil Data Galeri & Setting
        $albums = Album::latest()->take(6)->get();
        $site_setting = Setting::first(); // get site setting for youtube_link
        $extracurriculars = Extracurricular::all();
        $achievements = Achievement::latest()->take(6)->get();

        $stats = [
            'teacher_count' => \App\Models\Teacher::count(),
            'student_count' => \App\Models\Student::where('is_active', true)->count(),
            'extracurricular_count' => $extracurriculars->count(),
            'achievement_count' => $achievements->count(),
        ];

        return view('welcome', compact('posts', 'quetes', 'breakingNews', 'sliders', 'agendas', 'ppdbOpen', 'academicYear', 'albums', 'site_setting', 'extracurriculars', 'achievements', 'stats', 'ppdbRegistrants'));
    }

    public function ppdbMonitoring()
    {
        // 1. Dapatkan Tahun Akademik Aktif untuk PPDB
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        if (!$academicYear) {
            return redirect()->route('front.index')->with('error', 'Tahun akademik pendaftaran belum diatur.');
        }

        // 2. Dapatkan Gelombang/Phase Aktif
        $activePhase = AdmissionPhase::where('academic_year_id', $academicYear->id)
            ->where('phase_start_date', '<=', now())
            ->where('phase_end_date', '>=', now())
            ->first();

        if (!$activePhase) {
            // Jika tidak ada gelombang aktif saat ini, ambil gelombang terakhir yang baru saja selesai
            $activePhase = AdmissionPhase::where('academic_year_id', $academicYear->id)
                ->orderBy('phase_end_date', 'desc')
                ->first();
        }

        if (!$activePhase) {
            return redirect()->route('front.index')->with('error', 'Gelombang pendaftaran belum tersedia.');
        }

        // 3. Ambil Kuota per Jenis Pendaftaran untuk Gelombang ini
        $quotas = AdmissionQuotas::where('admission_phase_id', $activePhase->id)
            ->with('admissionTypes')
            ->get()
            ->pluck('quota', 'admission_types_id');

        // 4. Ambil Semua Pendaftar Terverifikasi, diurutkan berdasarkan skor seleksi
        // Kita kelompokkan berdasarkan jenis pendaftaran
        $registrants = PpdbRegistrant::where('admission_phase_id', $activePhase->id)
            ->whereIn('status', [
                PpdbRegistrant::STATUS_BERKAS_LENGKAP,
                PpdbRegistrant::STATUS_DITERIMA,
                PpdbRegistrant::STATUS_DAFTAR_ULANG,
                PpdbRegistrant::STATUS_DAFTAR_ULANG_VERIFIED,
                PpdbRegistrant::STATUS_MOVED,
                PpdbRegistrant::STATUS_CADANGAN
            ])
            ->orderBy('selection_score', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // OTOMATIS: Sinkronkan status ke database jika pengumuman sudah aktif
        foreach ($registrants as $reg) {
            $reg->syncStatus();
        }

        $registrantsGrouped = $registrants->groupBy('admission_type_id');

        $admissionTypes = \App\Models\AdmissionType::whereIn('id', $registrantsGrouped->keys())->get();

        return view('front.ppdb.monitoring', [
            'activePhase' => $activePhase,
            'registrants' => $registrantsGrouped,
            'quotas' => $quotas,
            'admissionTypes' => $admissionTypes
        ]);
    }

    public function berita()
    {
        $posts = Post::latest()->paginate(12); // Tampilkan 12 berita per halaman
        return view('front.berita.index', compact('posts'));
    }

    // Method untuk detail berita
    public function show($slug)
    {
        $post = Post::where('post_slug', $slug)->firstOrFail();

        // Ambil semua ID kategori dari post saat ini
        $categoryIds = $post->categories->pluck('id');

        // Ambil post lain yang memiliki kategori sama
        $related = Post::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('category_id', $categoryIds);
        })
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('front.berita.detail', compact('post', 'related'));
    }

    // Method untuk menyimpan komentar
    public function postComment(Request $request, $postId)
    {
        // Validasi input komentar
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $post = Post::findOrFail($postId);

        // Buat komentar baru
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id(); // jika user login, atau bisa null untuk guest
        $comment->comment = $request->comment;
        $comment->save();

        return redirect()->back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function showComments($id)
    {
        $post = Post::with(['comments'])->findOrFail($id);

        // Ambil semua komentar, terbaru di atas
        $comments = $post->comments()->orderBy('created_at', 'desc')->paginate(2);

        return view('front.berita.detail', compact('post', 'comments'));
    }

    public function handle($slug)
    {
        $menu = Menu::where('menu_slug', $slug)->firstOrFail();

        switch ($menu->menu_type) {

            // =====================
            // HALAMAN (Page)
            // =====================
            case 'pages':
                $page = Page::where('slug', $menu->menu_url)->firstOrFail();
                return view('front.page.show', compact('page'));

                // =====================
                // KATEGORI
                // =====================
            case 'links':
                $category = Category::where('category_slug', $menu->menu_url)
                    ->firstOrFail();

                $posts = $category->posts()
                    ->latest()
                    ->paginate(10);

                // Recent Post (tidak termasuk post yang ada di halaman ini jika mau)
                $recentPosts = Post::latest()
                    ->take(4)
                    ->get();

                // Sidebar
                $recentPosts = Post::latest()->take(5)->get();
                $categories = Category::withCount('posts')->get();
                $tags = Tag::withCount('posts')->get();

                return view('front.berita.index', compact('category', 'posts', 'recentPosts',));

                // =====================
                // MODULE / SYSTEM
                // =====================
            case 'modules':
                if ($menu->menu_url === 'berita') {
                    return app(PostController::class)->index();
                }

                if ($menu->menu_url === 'ppdb') {
                    // return app(PpdbController::class)->index();
                }

                abort(404);

            default:
                abort(404);
        }
    }

    public function handle1($slug)
    {
        if ($slug === 'dashboard') {
            return redirect()->route('dashboard');
        }

        $menu = Menu::where('menu_slug', $slug)->firstOrFail();

        switch ($menu->menu_type) {

            case 'pages':
                $page = Page::where('slug', $slug)->firstOrFail();
                return view('front.page.show', compact('page'));

            case 'modul':
                if ($menu->menu_url === 'berita') {
                    return app(PostController::class)->index();
                }
                abort(404);

            case 'link':
                return redirect()->away($menu->menu_url);

            default:
                abort(404);
        }
    }

    public function showPpdbCheck()
    {
        return view('front.ppdb.check');
    }

    public function checkPpdbStatus(Request $request)
    {
        $request->validate([
            'registration_number' => 'required|string',
        ]);

        $registrant = PpdbRegistrant::with(['admissionPhase', 'admissionType'])
            ->where('registration_number', $request->registration_number)
            ->first();

        if (!$registrant) {
            return redirect()->back()->with('error', 'Nomor pendaftaran tidak ditemukan.');
        }

        // OTOMATIS: Sinkronkan status ke database jika pengumuman sudah aktif
        $registrant->syncStatus();

        return view('front.ppdb.result', compact('registrant'));
    }

    public function manifest()
    {
        $setting = Setting::first();
        
        $v = $setting->pwa_version ?? time();
        $manifest = [
            "name" => $setting->pwa_name ?? "Madrasah Digital MTs Bustanul Huda",
            "short_name" => $setting->pwa_short_name ?? "Madrasah",
            "description" => $setting->short_description ?? "Sistem Informasi Akademik dan PPDB",
            "start_url" => "/",
            "display" => "standalone",
            "background_color" => $setting->pwa_background_color ?? "#ffffff",
            "theme_color" => $setting->pwa_theme_color ?? "#10b981",
            "icons" => [
                [
                    "src" => "/storage/pwa/icons/icon-192x192.png?v=" . $v,
                    "sizes" => "192x192",
                    "type" => "image/png"
                ],
                [
                    "src" => "/storage/pwa/icons/icon-512x512.png?v=" . $v,
                    "sizes" => "512x512",
                    "type" => "image/png"
                ],
                [
                    "src" => "/storage/pwa/icons/icon-192x192-maskable.png?v=" . $v,
                    "sizes" => "192x192",
                    "type" => "image/png",
                    "purpose" => "maskable"
                ]
            ]
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
