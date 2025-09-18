<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\ImageSlider;
use App\Models\Post;
use App\Models\Quotes;
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

        return view('welcome', compact('posts', 'quetes', 'breakingNews', 'sliders'));
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
}
