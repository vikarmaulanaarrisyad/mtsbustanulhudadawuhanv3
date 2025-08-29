<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WelcomeMessage;

class WelcomeMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = WelcomeMessage::first();
        return view('admin.blog.opening-speech.index', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $welcomeMessage = WelcomeMessage::findOrfail($id);

        $request->validate([
            'sambutan' => 'required',
        ]);

        try {
            $welcomeMessage->update([
                'slug'    => Str::slug($request->title),
                'excerpt' => Str::limit(strip_tags($request->sambutan), 300),
                'content' => $request->sambutan,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sambutan berhasil disimpan',
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
