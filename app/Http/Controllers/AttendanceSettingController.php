<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use Illuminate\Http\Request;

class AttendanceSettingController extends Controller
{
    public function index()
    {
        $settingAttendace = AttendanceSetting::first();
        if (!$settingAttendace) {
            $settingAttendace = AttendanceSetting::create([
                'check_in_start' => '06:00:00',
                'check_in_end' => '08:00:00',
                'check_out_start' => '14:00:00',
                'check_out_end' => '17:00:00',
                'work_days' => [1, 2, 3, 4, 5, 6],
            ]);
        }
        return view('admin.attendance.settings.index', compact('settingAttendace'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'check_in_start' => 'required',
            'check_in_end' => 'required|after:check_in_start',
            'check_out_start' => 'required',
            'check_out_end' => 'required|after:check_out_start',
            'work_days' => 'required|array',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'radius' => 'nullable|numeric',
        ]);

        AttendanceSetting::updateOrCreate(
            ['id' => $request->id ?? 1],
            $data
        );

        return response()->json(['message' => 'Pengaturan absensi berhasil diperbaharui']);
    }
}
