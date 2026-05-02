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
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $setting = AttendanceSetting::first();
        $now = Carbon::now();
        
        // Status logic
        $status = 'present';
        // Example: If check-in after start time, mark as late
        // Actually, check_in_start is usually the earliest possible. 
        // Let's say check_in_start is 07:00. If they check in at 07:15, is it late?
        // Usually there's a specific "on time" threshold. 
        // For simplicity, if they check in within the window, they are present.
        // We could add a "late_threshold" later.

        Attendance::updateOrCreate(
            ['teacher_id' => $teacher->id, 'date' => $now->toDateString()],
            [
                'check_in' => $now->toTimeString(),
                'status' => $status,
                'check_in_ip' => $request->ip(),
            ]
        );

        return response()->json(['message' => 'Presensi Masuk berhasil dilakukan pada ' . $now->format('H:i')]);
    }

    public function checkOut(Request $request)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $now = Carbon::now();

        $attendance = Attendance::where('teacher_id', $teacher->id)->where('date', $now->toDateString())->first();
        if (!$attendance) return response()->json(['message' => 'Anda belum melakukan presensi masuk hari ini'], 422);

        $attendance->update([
            'check_out' => $now->toTimeString(),
            'check_out_ip' => $request->ip(),
        ]);

        return response()->json(['message' => 'Presensi Pulang berhasil dilakukan pada ' . $now->format('H:i')]);
    }
}
