<?php

namespace App\Http\Controllers;

use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MailSettingController extends Controller
{
    public function index()
    {
        $mailSetting = MailSetting::first() ?? new MailSetting();
        return view('admin.mail.settings.index', compact('mailSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'sub_header' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'header_line_style' => 'nullable|in:none,solid,double',
            'default_signer_name' => 'nullable|string|max:255',
            'default_signer_position' => 'nullable|string|max:255',
            'default_signer_nip' => 'nullable|string|max:30',
        ]);

        $setting = MailSetting::first() ?? new MailSetting();

        $data = $request->only([
            'school_name',
            'sub_header',
            'address',
            'phone',
            'email',
            'website',
            'header_line_style',
            'default_signer_name',
            'default_signer_position',
            'default_signer_nip'
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('mail', 'public');
        }

        MailSetting::updateOrCreate(['id' => 1], $data);

        return response()->json([
            'status' => true,
            'message' => 'Pengaturan Kop Surat berhasil diperbaharui'
        ]);
    }
}
