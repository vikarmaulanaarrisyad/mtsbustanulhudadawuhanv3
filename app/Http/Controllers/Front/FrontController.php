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

        return view('welcome', compact('posts', 'quetes', 'breakingNews', 'sliders', 'agendas'));
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
            $category = Category::where('category_slug', $menu->menu_url)->firstOrFail();
            return view('front.category.show', compact('category'));

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
}
