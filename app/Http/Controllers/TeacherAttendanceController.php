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
        
        $todayAttendance = Attendance::where('teacher_id', $teacher->id)->where('date', $today)->first();
        
        $canCheckIn = false;
        $canCheckOut = false;
        $onLeave = false;
        
        if ($todayAttendance && in_array($todayAttendance->status, ['permit', 'sick'])) {
            $onLeave = true;
        }
        
        $now = Carbon::now()->toTimeString();
        
        if (!$holiday && !$isWeekend && !$onLeave) {
            if (!$todayAttendance || !$todayAttendance->check_in) {
                if ($now >= $setting->check_in_start && $now <= $setting->check_in_end) {
                    $canCheckIn = true;
                }
            }
            
            if ($todayAttendance && $todayAttendance->check_in && !$todayAttendance->check_out) {
                if ($now >= $setting->check_out_start && $now <= $setting->check_out_end) {
                    $canCheckOut = true;
                }
            }
        }

        $faceDescriptor = \App\Models\TeacherFaceDescriptor::where('teacher_id', $teacher->id)->first();

        return view('teacher.attendance.dashboard', compact(
            'teacher', 
            'todayAttendance', 
            'setting', 
            'holiday', 
            'isWeekend', 
            'canCheckIn', 
            'canCheckOut', 
            'onLeave',
            'faceDescriptor'
        ));
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

            // Cek apakah hari ini sedang izin/sakit
            $existingAttendance = Attendance::where('teacher_id', $teacher->id)->where('date', $now->toDateString())->first();
            if ($existingAttendance && in_array($existingAttendance->status, ['permit', 'sick'])) {
                return response()->json(['message' => 'Hari ini Anda tercatat sedang izin/sakit. Tidak perlu melakukan absen.'], 422);
            }

            // Validasi Waktu Masuk
            if ($time < $setting->check_in_start) {
                return response()->json(['message' => 'Belum waktunya absen masuk. Dimulai jam ' . $setting->check_in_start], 422);
            }
            if ($time > $setting->check_in_end) {
                return response()->json(['message' => 'Waktu absen masuk sudah berakhir (Batas: ' . $setting->check_in_end . ')'], 422);
            }

            // Validasi Radius Geofencing
            if ($setting->latitude && $setting->longitude) {
                if (!$request->latitude || !$request->longitude) {
                    return response()->json(['message' => 'Gagal mendapatkan lokasi. Pastikan GPS aktif.'], 422);
                }

                $distance = $this->calculateDistance($request->latitude, $request->longitude, $setting->latitude, $setting->longitude);
                if ($distance > $setting->radius) {
                    return response()->json(['message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Maksimal: ' . $setting->radius . 'm'], 422);
                }
            }

            $attendance = Attendance::updateOrCreate(
                ['teacher_id' => $teacher->id, 'date' => $now->toDateString()],
                [
                    'check_in' => $now->toTimeString(),
                    'status' => 'present',
                    'check_in_ip' => $request->ip(),
                    'check_in_lat' => $request->latitude,
                    'check_in_lng' => $request->longitude,
                    'image_in' => $request->image ? $this->saveFaceImage($request->image, $teacher->id, 'in') : null,
                ]
            );

            return response()->json(['message' => 'Berhasil! Absen Masuk tercatat pada ' . $now->format('H:i')]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344) * 1000; // Return in meters
    }

    private function saveFaceImage($base64Image, $teacherId, $type)
    {
        $imageData = str_replace('data:image/jpeg;base64,', '', $base64Image);
        $imageData = str_replace(' ', '+', $imageData);
        $imageName = 'attendance_faces/' . $teacherId . '_' . $type . '_' . time() . '.jpg';
        \Illuminate\Support\Facades\Storage::disk('public')->put($imageName, base64_decode($imageData));
        return $imageName;
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

            // Validasi Radius Geofencing
            if ($setting->latitude && $setting->longitude) {
                if (!$request->latitude || !$request->longitude) {
                    return response()->json(['message' => 'Gagal mendapatkan lokasi. Pastikan GPS aktif.'], 422);
                }

                $distance = $this->calculateDistance($request->latitude, $request->longitude, $setting->latitude, $setting->longitude);
                if ($distance > $setting->radius) {
                    return response()->json(['message' => 'Anda berada di luar radius kantor (' . round($distance) . 'm). Maksimal: ' . $setting->radius . 'm'], 422);
                }
            }

            $attendance = Attendance::where('teacher_id', $teacher->id)->where('date', $now->toDateString())->first();
            if (!$attendance) {
                return response()->json(['message' => 'Anda belum melakukan absen masuk hari ini.'], 422);
            }

            $attendance->update([
                'check_out' => $now->toTimeString(),
                'check_out_ip' => $request->ip(),
                'check_out_lat' => $request->latitude,
                'check_out_lng' => $request->longitude,
                'image_out' => $request->image ? $this->saveFaceImage($request->image, $teacher->id, 'out') : null,
            ]);

            return response()->json(['message' => 'Berhasil! Absen Pulang tercatat pada ' . $now->format('H:i')]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
    public function liveMonitoring()
    {
        $today = Carbon::today()->toDateString();
        $teachers = Teacher::orderBy('name')->get();
        $attendances = Attendance::where('date', $today)->get()->keyBy('teacher_id');
        
        $stats = [
            'total' => $teachers->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $teachers->count() - $attendances->count(),
        ];

        return view('admin.attendance.live', compact('teachers', 'attendances', 'stats'));
    }
}
