<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.media.album.index');
    }

    public function data()
    {
        $query = Album::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('album_cover', function ($q) {
                $imageUrl = $q->album_cover ? Storage::url($q->album_cover) : asset('images/no-image.png');

                return '
                    <img src="' . $imageUrl . '" alt="' . e($q->album_title) . '" style="max-height: 60px;" class="img-thumbnail">
                ';
            })

            ->addColumn('selectAll', function ($q) {
                return '
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input row-checkbox" name="selected[]" value="' . $q->id . '" data-id="' . $q->id . '">
                    </div>
                ';
            })
            ->editColumn('action', function ($q) {
                return '
                <button onclick="editForm(`' . route('albums.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                    <i class="fa fa-pencil-alt"></i>
                </button>
                  <button onclick="deleteData(`' . route('albums.destroy', $q->id) . '`,`' . $q->caption . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek validasi awal
        $validator = Validator::make($request->all(), [
            'album_title'              => 'required|unique:albums,album_title',
            'album_description'        => 'required',
            'album_cover'              => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Buat slug dari judul
        $slug = Str::slug($request->album_title);

        // Cek apakah slug sudah digunakan
        if (Album::where('album_slug', $slug)->exists()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['album_title' => ['Slug dari judul sudah digunakan, silakan gunakan judul lain.']],
                'message' => 'judul sudah digunakan.',
            ], 422);
        }

        $data = [
            'album_title' => $request->album_title,
            'album_description' => $request->album_description,
            'album_slug' => $slug,
            'album_cover' => $request->hasFile('album_cover') ? upload('album', $request->album_cover, 'cover') : null,
        ];

        Album::create($data);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $album = Album::findOrfail($id);

        return response()->json(['data' => $album]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Ambil data berdasarkan ID
        $album = Album::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'album_title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('albums', 'album_title')->ignore($id),
            ],
            'album_description'        => 'required',
            'album_cover'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Buat slug dari album_title
        $slug = Str::slug($request->album_title);

        // Cek apakah slug sudah digunakan oleh data lain
        if (Album::where('album_slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['album_title' => ['Slug dari judul sudah digunakan, silakan gunakan judul lain.']],
                'message' => 'Judul sudah digunakan.',
            ], 422);
        }
        // Hapus thumbnail lama jika ada thumbnail baru
        if ($request->hasFile('album_cover') && $album->album_cover && Storage::disk('public')->exists($album->album_cover)) {
            Storage::disk('public')->delete($album->album_cover);
        }

        // Update data
        $data = [
            'album_title' => $request->album_title,
            'album_slug' => $slug,
            'album_description' => $request->album_description,
            'album_cover'     => $request->hasFile('album_cover') ? upload('album', $request->album_cover, 'cover', $album->album_cover) : $album->album_cover,
        ];

        $album->update($data);

        return response()->json([
            'message' => 'Data berhasil diperbarui',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $query = Album::findOrfail($id);

        if (!empty($query->album_cover)) {
            if (Storage::disk('public')->exists($query->album_cover)) {
                Storage::disk('public')->delete($query->album_cover);
            }
        }

        $query->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        try {
            $albums = Album::whereIn('id', $ids)->get();

            foreach ($albums as $album) {
                if (!empty($album->album_cover)) {
                    if (Storage::disk('public')->exists($album->album_cover)) {
                        Storage::disk('public')->delete($album->album_cover);
                    }
                }
            }

            // Setelah semua file dihapus, hapus data dari database
            Album::whereIn('id', $ids)->delete();

            return response()->json(['message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
