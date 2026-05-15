<?php
 
namespace App\Http\Controllers\Guru;
 
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\Announcement;
use App\Models\TeacherPermit;
use App\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AttendanceSetting;
use App\Models\PpdbRegistrant;
 
class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $dayOfWeek = Carbon::today()->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
        $hour = date('H');
        $greeting = 'Selamat Malam';
        if ($hour >= 5 && $hour < 11) $greeting = 'Selamat Pagi';
        elseif ($hour >= 11 && $hour < 15) $greeting = 'Selamat Siang';
        elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
 
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan. Mohon hubungi Administrator.');
        }
 
        // Jadwal Mengajar Hari Ini
        $schedules = ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $dayOfWeek)
            ->orderBy('study_period_id')
            ->get();
 
        // Ringkasan Kehadiran Guru (7 hari terakhir)
        $myAttendances = Attendance::where('teacher_id', $teacher->id)
            ->where('date', '>=', Carbon::today()->subDays(7))
            ->orderBy('date', 'desc')
            ->get();
 
        // Kehadiran Hari Ini (untuk tombol Check-in/out)
        $todayAttendance = Attendance::where('teacher_id', $teacher->id)
            ->where('date', $today)
            ->first();
 
        // Total Jam Mengajar Minggu Ini
        $totalSchedules = ClassSchedule::where('teacher_id', $teacher->id)->count();
 
        // Cek apakah Guru adalah Wali Kelas
        $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
        $myStudents = collect([]);
        $totalClassSavings = 0;
        if ($homeroomClass) {
            $myStudents = Student::with(['behaviorLogs', 'profile'])
                ->where('student_class_group_id', $homeroomClass->id)
                ->where('is_active', true)
                ->orderBy('nama_lengkap')
                ->get();
            
            $totalClassSavings = \App\Models\StudentSaving::whereIn('student_id', $myStudents->pluck('id'))->sum('balance');
        }

        // Pending Journals Today
        $filledJournalIds = \App\Models\TeachingJournal::where('teacher_id', $teacher->id)
            ->where('date', $today)
            ->pluck('class_schedule_id')
            ->toArray();
        
        $pendingJournalsCount = $schedules->whereNotIn('id', $filledJournalIds)->count();

        // Students Birthday Today
        $birthdayStudents = collect([]);
        if ($homeroomClass) {
            $birthdayStudents = Student::where('student_class_group_id', $homeroomClass->id)
                ->where('is_active', true)
                ->whereMonth('tanggal_lahir', date('m'))
                ->whereDay('tanggal_lahir', date('d'))
                ->get();
        }
 
        // Hitung Pengumuman Belum Dibaca
        $unreadAnnouncementsCount = Announcement::where('is_active', true)
            ->whereIn('type', ['Umum', 'Guru'])
            ->whereDoesntHave('reads', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
 
        // Ringkasan Izin Terakhir
        $myPermits = TeacherPermit::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
 
        // Cek apakah hari ini sedang izin/sakit
        $onLeave = false;
        if ($todayAttendance && in_array($todayAttendance->status, ['permit', 'sick'])) {
            $onLeave = true;
        }
 
        // Ambil Pengumuman Terbaru
        $announcements = Announcement::where('is_active', true)
            ->whereIn('type', ['Umum', 'Guru'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
 
        $setting = AttendanceSetting::first();
 
        // PPDB Pending Count for Verifiers
        $ppdbPendingCount = 0;
        if ($user->can('ppdb.verify.berkas') || $user->can('ppdb.verify')) {
            $ppdbPendingCount += PpdbRegistrant::whereIn('status', ['pending', 'berkas_tidak_lengkap'])->count();
        }
        if ($user->can('ppdb.verify.daftar_ulang')) {
            $ppdbPendingCount += PpdbRegistrant::where('status', 'daftar_ulang')->count();
        }

        return view('guru.dashboard.index', compact(
            'teacher', 'schedules', 'myAttendances', 'todayAttendance', 
            'totalSchedules', 'homeroomClass', 'myStudents', 'totalClassSavings', 'greeting',
            'unreadAnnouncementsCount', 'myPermits', 'onLeave', 'announcements', 'setting', 'pendingJournalsCount',
            'birthdayStudents', 'ppdbPendingCount'
        ));
    }
 
    public function teacherSchedule(Request $request)
    {
        $user = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $currentDay = $request->get('day', Carbon::today()->dayOfWeekIso);
        if($currentDay > 6) $currentDay = 1;

        $schedules = ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $currentDay)
            ->orderBy('study_period_id')
            ->get();

        return view('guru.dashboard.schedule', compact('teacher', 'schedules'));
    }

    public function printSchedule()
    {
        $user = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $schedules = ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->groupBy('day');

        $setting = \App\Models\Setting::first();
        $activeYear = \App\Models\AcademicYear::where('current_semester', true)->first();

        return view('guru.dashboard.schedule_print', compact('teacher', 'schedules', 'setting', 'activeYear'));
    }
 
    public function attendanceReport(Request $request)
    {
        $user = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $date = Carbon::createFromDate($year, $month, 1);

        $attendances = Attendance::where('teacher_id', $teacher->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->get();

        // Statistics
        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'permit' => $attendances->where('status', 'permit')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'total_hours' => 0,
            'attendance_rate' => 0
        ];

        $totalWorkingDays = $date->daysInMonth;
        // Simple working days calculation (excluding weekends could be added if needed)
        // But here we use actual data from the month
        
        $presentCount = $stats['present'] + $stats['late'];
        if ($totalWorkingDays > 0) {
            $stats['attendance_rate'] = round(($presentCount / $totalWorkingDays) * 100, 1);
        }

        // Calculate total hours
        foreach ($attendances as $attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                $in = Carbon::parse($attendance->check_in);
                $out = Carbon::parse($attendance->check_out);
                $stats['total_hours'] += $out->diffInMinutes($in) / 60;
            }
        }
        $stats['total_hours'] = round($stats['total_hours'], 1);

        // Data for charts
        $chartData = [
            'labels' => $attendances->pluck('date')->map(fn($d) => $d->format('d/m'))->toArray(),
            'hours' => [],
            'status_counts' => [
                $stats['present'], $stats['late'], $stats['absent'], $stats['permit'], $stats['sick']
            ]
        ];

        foreach ($attendances as $attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                $in = Carbon::parse($attendance->check_in);
                $out = Carbon::parse($attendance->check_out);
                $chartData['hours'][] = round($out->diffInMinutes($in) / 60, 1);
            } else {
                $chartData['hours'][] = 0;
            }
        }

        return view('guru.dashboard.report', compact('teacher', 'attendances', 'stats', 'chartData', 'month', 'year'));
    }

    public function getClassStudents($id)
    {
        $students = Student::where('student_class_group_id', $id)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();
 
        return response()->json([
            'class' => ClassGroup::find($id)->kelas_lengkap ?? '-',
            'students' => $students
        ]);
    }
}
