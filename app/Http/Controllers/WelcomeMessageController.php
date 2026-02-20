<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WelcomeMessage;
use Illuminate\Support\Facades\Storage;

class WelcomeMessageController extends Controller
{
    // Menampilkan form sambutan
    public function index()
    {
        $data = WelcomeMessage::first(); // ambil sambutan pertama (atau null)
        return view('admin.blog.opening-speech.index', compact('data'));
    }

    // Simpan sambutan baru
    public function store(Request $request)
    {
        $request->validate([
            'sambutan' => 'required',
            'path_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $welcomeMessage = WelcomeMessage::create([
                'name' => $request->name,
                'slug'    => Str::slug('Sambutan Kepala Madrasah'),
                'excerpt' => Str::limit(strip_tags($request->sambutan), 300),
                'content' => $request->sambutan,
                'path_image' => $request->hasFile('path_image') ? upload('sambutan', $request->path_image, 'sambutan') : null
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sambutan berhasil dibuat',
                'data' => $welcomeMessage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update sambutan yang sudah ada
    public function update(Request $request, $id)
    {
        $welcomeMessage = WelcomeMessage::findOrFail($id);

        $request->validate([
            'sambutan' => 'required',
            'path_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {

            $pathImage = $welcomeMessage->path_image; // default pakai gambar lama

            if ($request->hasFile('path_image')) {

                // Hapus lama jika ada
                if (
                    $welcomeMessage->path_image &&
                    Storage::disk('public')->exists($welcomeMessage->path_image)
                ) {
                    Storage::disk('public')->delete($welcomeMessage->path_image);
                }

                // Upload baru
                $pathImage = upload('sambutan', $request->path_image, 'sambutan');
            }

            $welcomeMessage->update([
                'name'       => $request->name,
                'slug'       => Str::slug('Sambutan Kepala Madrasah'),
                'excerpt'    => Str::limit(strip_tags($request->sambutan), 300),
                'content'    => $request->sambutan,
                'path_image' => $pathImage, // tidak pernah null
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sambutan berhasil diperbarui',
                'data' => $welcomeMessage
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
