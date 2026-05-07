<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WaGatewayController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.wa-gateway.index', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $setting = Setting::first();
        $setting->update([
            'wa_api_url' => $request->wa_api_url,
            'wa_api_token' => $request->wa_api_token,
            'wa_api_sender' => $request->wa_api_sender,
        ]);

        return redirect()->back()->with('success', 'Konfigurasi WA Gateway berhasil diperbarui.');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'target_type' => 'required',
            'message' => 'required',
        ]);

        $recipients = [];
        if ($request->target_type === 'guru') {
            $recipients = Teacher::whereNotNull('phone')->pluck('phone')->toArray();
        } elseif ($request->target_type === 'siswa') {
            $recipients = Student::whereNotNull('phone')->pluck('phone')->toArray();
        } elseif ($request->target_type === 'all') {
            $guru = Teacher::whereNotNull('phone')->pluck('phone')->toArray();
            $siswa = Student::whereNotNull('phone')->pluck('phone')->toArray();
            $recipients = array_unique(array_merge($guru, $siswa));
        }

        if (empty($recipients)) {
            return response()->json(['message' => 'Tidak ada nomor tujuan yang ditemukan.'], 422);
        }

        // Logic to send WA via API
        // For demonstration, we'll simulate it.
        // In real use, use Http::post($url, [...])
        
        return response()->json([
            'message' => 'Pesan sedang dikirim ke ' . count($recipients) . ' penerima.',
            'count' => count($recipients)
        ]);
    }
}
