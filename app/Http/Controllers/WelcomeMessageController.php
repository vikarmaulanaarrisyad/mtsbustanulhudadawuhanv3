<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WelcomeMessage;
use App\Models\Setting;
use App\Services\GeminiAiService;
use App\Services\GroqAiService;
use Illuminate\Support\Facades\Storage;

class WelcomeMessageController extends Controller
{
    public function generateWithAi(Request $request)
    {
        try {
            $setting = Setting::first();
            $provider = $setting->ai_provider ?? 'gemini';

            if ($provider === 'groq') {
                $aiService = app(GroqAiService::class);
            } else {
                $aiService = app(GeminiAiService::class);
            }

            $schoolName = $setting->company_name ?? 'MTs Bustanul Huda';
            $principalName = $request->name ?? 'Kepala Madrasah';

            $prompt = "Buatkan draft sambutan kepala madrasah yang profesional, inspiratif, dan islami untuk website resmi {$schoolName}. Sambutan ini ditulis atas nama {$principalName} selaku Kepala Madrasah. Panjangnya sekitar 3-4 paragraf pendek. Format output harus berformat HTML berupa tag <p>. Jangan tambahkan markdown atau judul di awal, langsung isi teks sambutannya.";

            $result = $aiService->getCompletion($prompt);

            // Clean markdown wrapper if any
            $result = preg_replace('/```html\n?/', '', $result);
            $result = preg_replace('/```\n?/', '', $result);

            return response()->json([
                'status' => 'success',
                'data' => trim($result)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat teks AI: ' . $e->getMessage()
            ], 500);
        }
    }
    // Menampilkan form sambutan
    public function index()
    {
        $data = WelcomeMessage::first(); // ambil sambutan pertama (atau null)
        $teachers = \App\Models\Teacher::orderBy('name', 'asc')->get();
        return view('admin.blog.opening-speech.index', compact('data', 'teachers'));
    }

    // Simpan sambutan baru
    public function store(Request $request)
    {
        $request->validate([
            'sambutan' => 'required',
            'path_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'path_image.required' => 'Foto Kepala Madrasah wajib diunggah.',
            'path_image.image' => 'File yang diunggah harus berupa gambar.',
            'path_image.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'path_image.max' => 'Ukuran foto maksimal adalah 2 MB (2048 KB).',
            'sambutan.required' => 'Naskah sambutan wajib diisi.',
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
        ], [
            'path_image.image' => 'File yang diunggah harus berupa gambar.',
            'path_image.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'path_image.max' => 'Ukuran foto maksimal adalah 2 MB (2048 KB).',
            'sambutan.required' => 'Naskah sambutan wajib diisi.',
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
