<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blog.posts.index');
    }

    // public function data1()
    // {
    //     $query = Post::with(['categories', 'tags'])->orderBy('created_at', 'desc')->get();

    //     return datatables($query)
    //         ->addIndexColumn()
    //         ->addColumn('post_image', function ($q) {
    //             $imageUrl = $q->post_image ? Storage::url($q->post_image) : asset('images/no-image.png');

    //             return '
    //                 <img src="' . $imageUrl . '" alt="' . e($q->caption) . '" style="max-height: 60px;" class="img-thumbnail">
    //             ';
    //         })

    //         ->addColumn('selectAll', function ($q) {
    //             return '
    //                 <div class="form-check form-check-inline">
    //                     <input type="checkbox" class="form-check-input row-checkbox" name="selected[]" value="' . $q->id . '" data-id="' . $q->id . '">
    //                 </div>
    //             ';
    //         })
    //         ->editColumn('action', function ($q) {
    //             return '
    //                 <a href="' . route('posts.edit', $q->id) . '" class="btn btn-sm btn-info" title="Edit">
    //                     <i class="fa fa-edit"></i>
    //                 </a>
    //                 <button onclick="deleteData(`' . route('posts.destroy', $q->id) . '`, `' . e($q->post_title) . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
    //                     <i class="fa fa-trash"></i>
    //                 </button>
    //             ';
    //         })

    //         ->editColumn('user', function ($q) {
    //             return $q->user->name ?? '-';
    //         })
    //         ->editColumn('created_at', function ($q) {
    //             return tanggal_indonesia($q->created_at, false, true);
    //         })
    //         ->escapeColumns([])
    //         ->make(true);
    // }

    public function data()
    {
        $query = Post::with(['categories:id,category_name', 'tags:id,tag_name', 'user:id,name'])
            ->select('id', 'post_title', 'post_image', 'user_id', 'created_at')
            ->orderBy('created_at', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('post_image', function ($q) {
                $imageUrl = $q->post_image ? Storage::url($q->post_image) : asset('images/no-image.png');

                return '<img src="' . $imageUrl . '" alt="' . e($q->post_title) . '" style="max-height: 60px;" class="img-thumbnail">';
            })
            ->addColumn('selectAll', function ($q) {
                return '
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input row-checkbox" name="selected[]" value="' . $q->id . '" data-id="' . $q->id . '">
                </div>
            ';
            })
            ->addColumn('action', function ($q) {
                return '
                <a href="' . route('posts.edit', $q->id) . '" class="btn btn-sm btn-info" title="Edit">
                    <i class="fa fa-edit"></i>
                </a>
                <button onclick="deleteData(`' . route('posts.destroy', $q->id) . '`, `' . e($q->post_title) . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            ';
            })
            ->editColumn('user', function ($q) {
                return $q->user->name ?? '-';
            })
            ->editColumn('created_at', function ($q) {
                return tanggal_indonesia($q->created_at, false, true);
            })
            ->rawColumns(['post_image', 'selectAll', 'action']) // <- ganti escapeColumns([])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.blog.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek validasi awal
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_image' => 'required|image|max:2048',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Buat slug dari judul
        $slug = Str::slug($request->post_title);

        $data = [
            'post_title' => $request->post_title,
            'post_slug' => $slug,
            'post_content' => $request->post_content,
            'post_type' => $request->post_type ?? 'post',
            'post_status' => $request->post_status,
            'post_visibility' => $request->post_visibility,
            'post_comment_status' => $request->post_comment_status,
            'user_id' => Auth::user()->id,
            'post_image' => $request->hasFile('post_image') ? upload('post_image', $request->post_image, 'post_image') : null,
        ];

        $post = Post::create($data);

        // Sync kategori & tag
        $post->categories()->sync($request->input('categories', []));
        $post->tags()->sync($request->input('tags', []));

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::with(['categories', 'tags'])->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'post_content' => 'required|string',
            'post_image' => 'nullable|image|max:2048',
            'post_status' => 'required|in:draft,publish',
            'post_visibility' => 'required|in:public,private',
            'post_comment_status' => 'required|in:open,close',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Cari data post
        $post = Post::findOrFail($id);

        // Slug dari judul
        $slug = Str::slug($request->post_title);

        // Siapkan data update
        $data = [
            'post_title' => $request->post_title,
            'post_slug' => $slug,
            'post_content' => $request->post_content,
            'post_type' => $request->post_type ?? 'post',
            'post_status' => $request->post_status,
            'post_visibility' => $request->post_visibility,
            'post_comment_status' => $request->post_comment_status,
            'user_id' => Auth::id(),
        ];

        // Jika ada file baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('post_image')) {
            if ($post->post_image && Storage::disk('public')->exists($post->post_image)) {
                Storage::disk('public')->delete($post->post_image);
            }

            $data['post_image'] = upload('post_image', $request->post_image, 'post_image');
        }

        // Update data utama
        $post->update($data);

        // Sinkronisasi kategori dan tag (jika ada)
        $post->categories()->sync($request->categories ?? []);
        $post->tags()->sync($request->tags ?? []);

        return response()->json([
            'message' => 'Data berhasil diperbarui',
            'redirect' => route('posts.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Hapus gambar jika ada
        if (!empty($post->post_image) && Storage::disk('public')->exists($post->post_image)) {
            Storage::disk('public')->delete($post->post_image);
        }

        // Hapus relasi kategori dan tag
        $post->categories()->detach();
        $post->tags()->detach();

        // Hapus postingan
        $post->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        try {
            $posts = Post::whereIn('id', $ids)->get();

            foreach ($posts as $post) {
                // Hapus file gambar jika ada
                if (!empty($post->post_image) && Storage::disk('public')->exists($post->post_image)) {
                    Storage::disk('public')->delete($post->post_image);
                }

                // Hapus relasi kategori dan tag
                $post->categories()->detach();
                $post->tags()->detach();

                // Hapus data post
                $post->delete();
            }

            return response()->json(['message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
