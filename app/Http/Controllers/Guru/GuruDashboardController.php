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
 
class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $dayOfWeek = Carbon::today()->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
 
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
        $myStudents = [];
        if ($homeroomClass) {
            $myStudents = Student::with(['behaviorLogs', 'profile'])
                ->where('student_class_group_id', $homeroomClass->id)
                ->where('is_active', true)
                ->orderBy('nama_lengkap')
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
 
        return view('guru.dashboard.index', compact(
            'teacher', 'schedules', 'myAttendances', 'todayAttendance', 
            'totalSchedules', 'homeroomClass', 'myStudents', 
            'unreadAnnouncementsCount', 'myPermits', 'onLeave', 'announcements'
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
