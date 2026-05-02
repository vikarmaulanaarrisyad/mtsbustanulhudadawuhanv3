<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Holiday;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function dashboard()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda tidak terhubung dengan data Guru/Staf.');
        }

        $today = Carbon::today();
        $setting = AttendanceSetting::first();
        
        if (!$setting) {
            return redirect()->route('dashboard')->with('error', 'Pengaturan presensi belum dikonfigurasi oleh Admin.');
        }

        $holiday = Holiday::where('holiday_date', $today)->first();
        $isWeekend = !in_array($today->dayOfWeekIso, $setting->work_days ?? [1, 2, 3, 4, 5, 6]);
        
        $attendance = Attendance::where('teacher_id', $teacher->id)->where('date', $today)->first();
        
        $canCheckIn = false;
        $canCheckOut = false;
        
        $now = Carbon::now()->toTimeString();
        
        if (!$holiday && !$isWeekend) {
            if (!$attendance || !$attendance->check_in) {
                if ($now >= $setting->check_in_start && $now <= $setting->check_in_end) {
                    $canCheckIn = true;
                }
            }
            
            if ($attendance && $attendance->check_in && !$attendance->check_out) {
                if ($now >= $setting->check_out_start && $now <= $setting->check_out_end) {
                    $canCheckOut = true;
                }
            }
        }

        return view('teacher.attendance.dashboard', compact('teacher', 'attendance', 'setting', 'holiday', 'isWeekend', 'canCheckIn', 'canCheckOut'));
    }

    public function checkIn(Request $request)
    {
        try {
            $teacher = Teacher::where('user_id', Auth::id())->first();
            if (!$teacher) return response()->json(['message' => 'Profil Guru tidak ditemukan.'], 422);

            $setting = AttendanceSetting::first();
            if (!$setting) return response()->json(['message' => 'Pengaturan presensi belum dikonfigurasi.'], 422);

            $now = Carbon::now();
            $time = $now->toTimeString();

            // Validasi Waktu Masuk
            if ($time < $setting->check_in_start) {
                return response()->json(['message' => 'Belum waktunya absen masuk. Dimulai jam ' . $setting->check_in_start], 422);
            }
            if ($time > $setting->check_in_end) {
                return response()->json(['message' => 'Waktu absen masuk sudah berakhir (Batas: ' . $setting->check_in_end . ')'], 422);
            }

            $attendance = Attendance::updateOrCreate(
                ['teacher_id' => $teacher->id, 'date' => $now->toDateString()],
                [
                    'check_in' => $now->toTimeString(),
                    'status' => 'present',
                    'check_in_ip' => $request->ip(),
                ]
            );

            return response()->json(['message' => 'Berhasil! Absen Masuk tercatat pada ' . $now->format('H:i')]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function checkOut(Request $request)
    {
        try {
            $teacher = Teacher::where('user_id', Auth::id())->first();
            if (!$teacher) return response()->json(['message' => 'Profil Guru tidak ditemukan.'], 422);

            $setting = AttendanceSetting::first();
            if (!$setting) return response()->json(['message' => 'Pengaturan presensi belum dikonfigurasi.'], 422);

            $now = Carbon::now();
            $time = $now->toTimeString();

            // Validasi Waktu Pulang
            if ($time < $setting->check_out_start) {
                return response()->json(['message' => 'Belum waktunya absen pulang. Baru bisa jam ' . $setting->check_out_start], 422);
            }
            if ($time > $setting->check_out_end) {
                return response()->json(['message' => 'Waktu absen pulang sudah berakhir (Batas: ' . $setting->check_out_end . ')'], 422);
            }

            $attendance = Attendance::where('teacher_id', $teacher->id)->where('date', $now->toDateString())->first();
            if (!$attendance) {
                return response()->json(['message' => 'Anda belum melakukan absen masuk hari ini.'], 422);
            }

            $attendance->update([
                'check_out' => $now->toTimeString(),
                'check_out_ip' => $request->ip(),
            ]);

            return response()->json(['message' => 'Berhasil! Absen Pulang tercatat pada ' . $now->format('H:i')]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
