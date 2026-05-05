<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return view('setting.index', compact('setting'));
    }

    public function update1(Request $request, Setting $setting)
    {
        $rules = [
            'owner_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|string|min:11|max:17',
            'phone_hours' => 'required',
            'about' => 'required',
            'address' => 'nullable',
            'city' => 'nullable',
            'province' => 'nullable',
            'company_name' => 'required',
            'short_description' => 'required',
            'keyword' => 'nullable'
        ];

        if ($request->has('pills') && $request->pills == 'logo') {
            $rules = [
                'path_image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'path_image_header' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'path_image_footer' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ];
        }

        if ($request->has('pills') && $request->pills == 'sosial-media') {
            $rules = [
                'instagram_link' => 'required|url',
                'twitter_link' => 'required|url',
                'fanpage_link' => 'required|url',
                'google_plus_link' => 'required|url'
            ];
        }

        $data = $request->except('path_image', 'path_image_header', 'path_image_footer');

        if ($request->hasFile('path_image')) {
            if (Storage::disk('public')->exists($setting->path_image)) {
                Storage::disk('public')->delete($setting->path_image);
            }

            $data['path_image'] = upload('setting', $request->file('path_image'), 'setting');
        }

        if ($request->hasFile('path_image_header')) {
            if (Storage::disk('public')->exists($setting->path_image_header)) {
                Storage::disk('public')->delete($setting->path_image_header);
            }

            $data['path_image_header'] = upload('setting', $request->file('path_image_header'), 'setting');
        }

        if ($request->hasFile('path_image_footer')) {
            if (Storage::disk('public')->exists($setting->path_image_footer)) {
                Storage::disk('public')->delete($setting->path_image_footer);
            }

            $data['path_image_footer'] = upload('setting', $request->file('path_image_footer'), 'setting');
        }

        $setting->update($data);

        if ($request->has('pills') && $request->pills == 'bank') {
            $setting->bank_setting()->attach($request->bank_id, $request->only('account', 'name', 'is_main'));
        }

        return back()->with([
            'message' => 'Pengaturan berhasil diperbarui',
            'success' => true
        ]);
    }

    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'owner_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|string|min:11|max:17',
            'phone_hours' => 'required',
            'about' => 'required',
            'address' => 'nullable',
            'city' => 'nullable',
            'province' => 'nullable',
            'company_name' => 'required',
            'short_description' => 'required',
            'keyword' => 'nullable'
        ];

        if ($request->has('pills') && $request->pills == 'logo') {
            $rules = [
                'path_image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'path_breadcrumb' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'path_image_header' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'path_image_footer' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ];
        }

        if ($request->has('pills') && $request->pills == 'sosial-media') {
            $rules = [
                'instagram_link' => 'required|url',
                'twitter_link' => 'required|url',
                'fanpage_link' => 'required|url',
                'google_plus_link' => 'required|url',
                'youtube_link' => 'nullable|url'
            ];
        }

        if ($request->has('pills') && $request->pills == 'payment') {
            $rules = [
                'midtrans_server_key' => 'nullable|string',
                'midtrans_client_key' => 'nullable|string',
                'midtrans_is_production' => 'nullable|boolean',
                'bank_name' => 'nullable|string',
                'bank_account_name' => 'nullable|string',
                'bank_account_number' => 'nullable|string',
            ];
            // Ensure boolean value is set correctly from checkbox
            $request->merge([
                'midtrans_is_production' => $request->has('midtrans_is_production')
            ]);
        }

        if ($request->has('pills') && $request->pills == 'pwa') {
            $rules = [
                'pwa_name' => 'required|string|max:255',
                'pwa_short_name' => 'required|string|max:50',
                'pwa_theme_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
                'pwa_background_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            ];
        }

        $data = $request->except('path_image', 'path_image_header', 'path_breadcrumb', 'path_image_footer');

        if ($request->hasFile('path_image') && $setting->path_image) {
            if (Storage::disk('public')->exists($setting->path_image)) {
                Storage::disk('public')->delete($setting->path_image);
            }

            $data['path_image'] = upload('setting', $request->file('path_image'), 'setting');
        }

        if ($request->hasFile('path_breadcrumb') && $setting->path_breadcrumb) {
            if (Storage::disk('public')->exists($setting->path_breadcrumb)) {
                Storage::disk('public')->delete($setting->path_breadcrumb);
            }

            $data['path_breadcrumb'] = upload('setting', $request->file('path_breadcrumb'), 'setting');
        }

        if ($request->hasFile('path_image_header') && $setting->path_image_header) {
            if (Storage::disk('public')->exists($setting->path_image_header)) {
                Storage::disk('public')->delete($setting->path_image_header);
            }

            $data['path_image_header'] = upload('setting', $request->file('path_image_header'), 'setting');
        }

        if ($request->hasFile('path_image_footer') && $setting->path_image_footer) {
            if (Storage::disk('public')->exists($setting->path_image_footer)) {
                Storage::disk('public')->delete($setting->path_image_footer);
            }

            $data['path_image_footer'] = upload('setting', $request->file('path_image_footer'), 'setting');
        }

        $setting->update($data);

        if ($request->has('pills') && $request->pills == 'bank') {
            $setting->bank_setting()->attach($request->bank_id, $request->only('account', 'name', 'is_main'));
        }

        return back()->with([
            'message' => 'Pengaturan berhasil diperbarui',
            'success' => true
        ]);
    }

    public function testMidtrans(Request $request)
    {
        $setting = Setting::first();
        if (!$setting || !$setting->midtrans_server_key) {
            return response()->json(['success' => false, 'message' => 'Server Key belum diisi/disimpan. Silakan simpan pengaturan terlebih dahulu.']);
        }

        \Midtrans\Config::$serverKey = trim($setting->midtrans_server_key);
        \Midtrans\Config::$isProduction = (bool) $setting->midtrans_is_production;

        try {
            // Test connection by fetching a dummy transaction status
            $status = \Midtrans\Transaction::status('test-connection-ping-12345');
            return response()->json(['success' => true, 'message' => 'Koneksi ke Midtrans berhasil! Kunci API valid.']);
        } catch (\Exception $e) {
            // Midtrans returns 404 for non-existent transactions, which means authentication SUCCESS!
            // If it returns 401, authentication FAILED.
            $errorMsg = $e->getMessage();
            if (strpos($errorMsg, '404') !== false || strpos(strtolower($errorMsg), "doesn't exist") !== false || strpos(strtolower($errorMsg), "tidak ditemukan") !== false) {
                return response()->json(['success' => true, 'message' => 'Koneksi ke Midtrans berhasil! Kunci API valid. (' . ($setting->midtrans_is_production ? 'Production' : 'Sandbox') . ')']);
            }
            
            return response()->json(['success' => false, 'message' => 'Gagal terkoneksi: Kunci API salah atau environment tidak sesuai. (' . $errorMsg . ')']);
        }
    }
}
