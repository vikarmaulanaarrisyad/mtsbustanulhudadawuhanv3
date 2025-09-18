<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WelcomeMessage;

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
        ]);

        try {
            $welcomeMessage = WelcomeMessage::create([
                'slug'    => Str::slug('Sambutan Kepala Madrasah'),
                'excerpt' => Str::limit(strip_tags($request->sambutan), 300),
                'content' => $request->sambutan,
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
        ]);

        try {
            $welcomeMessage->update([
                'slug'    => Str::slug('Sambutan Kepala Madrasah'),
                'excerpt' => Str::limit(strip_tags($request->sambutan), 300),
                'content' => $request->sambutan,
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
