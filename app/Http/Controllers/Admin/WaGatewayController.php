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
            $recipients = Teacher::whereNotNull('phone')
                ->where('phone', '!=', '')
                ->pluck('phone')
                ->toArray();
        } elseif ($request->target_type === 'siswa') {
            $recipients = \App\Models\StudentProfile::whereNotNull('no_hp')
                ->where('no_hp', '!=', '')
                ->pluck('no_hp')
                ->toArray();
        } elseif ($request->target_type === 'all') {
            $guru = Teacher::whereNotNull('phone')
                ->where('phone', '!=', '')
                ->pluck('phone')
                ->toArray();
            $siswa = \App\Models\StudentProfile::whereNotNull('no_hp')
                ->where('no_hp', '!=', '')
                ->pluck('no_hp')
                ->toArray();
            $recipients = array_unique(array_merge($guru, $siswa));
        }

        if (empty($recipients)) {
            return response()->json(['message' => 'Tidak ada nomor tujuan yang ditemukan.'], 422);
        }

        $setting = Setting::first();
        
        if ($setting->wa_api_url && $setting->wa_api_token) {
            try {
                // Fonnte accepts multiple targets separated by comma
                $targetString = implode(',', $recipients);
                
                $response = Http::withHeaders([
                    'Authorization' => $setting->wa_api_token
                ])->post($setting->wa_api_url, [
                    'target' => $targetString,
                    'message' => $request->message,
                    'delay' => '2', // 2 seconds delay between messages to avoid ban
                ]);

                if ($response->successful()) {
                    return response()->json([
                        'message' => 'Berhasil mengirim pesan ke ' . count($recipients) . ' nomor via API Gateway.',
                        'count' => count($recipients)
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Gagal terhubung ke API Gateway: ' . $response->body()
                    ], 500);
                }

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ], 500);
            }
        }

        // Fallback for simulation if API is not configured
        return response()->json([
            'message' => '[SIMULASI] Pesan berhasil diproses untuk ' . count($recipients) . ' penerima. (API Belum Dikonfigurasi)',
            'count' => count($recipients)
        ]);
    }
}
