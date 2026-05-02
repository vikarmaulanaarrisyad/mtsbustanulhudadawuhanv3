<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionType;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Quotes;
use App\Models\SchoolAgenda;
use App\Models\StudentAdmission;
use App\Models\Tag;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Attendance;
use App\Models\StudentAttendance;
use App\Models\ClassGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $dayOfWeek = Carbon::today()->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
        
        // Cek apakah user adalah Guru
        if ($user->hasRole('Guru')) {
            $teacher = Teacher::where('user_id', $user->id)->first();
            
            if (!$teacher) {
                return view('admin.dashboard.index')->with('error', 'Profil Guru tidak ditemukan.');
            }

            // Jadwal Mengajar Hari Ini
            $schedules = \App\Models\ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
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
            $totalSchedules = \App\Models\ClassSchedule::where('teacher_id', $teacher->id)->count();

            // Cek apakah Guru adalah Wali Kelas
            $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
            $myStudents = [];
            if ($homeroomClass) {
                $myStudents = Student::where('student_class_group_id', $homeroomClass->id)
                    ->where('is_active', true)
                    ->orderBy('nama_lengkap')
                    ->get();
            }

            // Hitung Pengumuman Belum Dibaca
            $unreadAnnouncementsCount = \App\Models\Announcement::where('is_active', true)
                ->whereIn('type', ['Umum', 'Guru'])
                ->whereDoesntHave('reads', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->count();

            return view('admin.dashboard.teacher', compact('teacher', 'schedules', 'myAttendances', 'todayAttendance', 'totalSchedules', 'homeroomClass', 'myStudents', 'unreadAnnouncementsCount'));
        }

        // --- Logika Dashboard Admin (Default) ---
        $academicYear = AcademicYear::where('current_semester', 1)->first();

        // Basic Stats
        $studentsCount = Student::where('is_active', true)->count();
        $teachersCount = Teacher::count();
        $subjectsCount = Subject::count();
        $classesCount = ClassGroup::count();

        // Attendance Stats Today
        $teacherAttendanceCount = Attendance::where('date', $today)->count();
        $studentAttendanceCount = StudentAttendance::where('date', $today)->count();
        
        // Recent Student Attendance
        $recentAttendances = StudentAttendance::with(['student', 'classGroup'])
            ->where('date', $today)
            ->latest('time')
            ->take(5)
            ->get();

        // Original Stats
        $postsCount = Post::count();
        $categoriesCount = Category::count();
        $tagsCount = Tag::count();
        $pageCount = Page::count();
        $agendaCount = SchoolAgenda::count();
        $attendanceTrend = $this->getAttendanceTrend();

        return view('admin.dashboard.index', compact(
            'academicYear',
            'studentsCount',
            'teachersCount',
            'subjectsCount',
            'classesCount',
            'teacherAttendanceCount',
            'studentAttendanceCount',
            'recentAttendances',
            'postsCount',
            'categoriesCount',
            'tagsCount',
            'pageCount',
            'agendaCount',
            'attendanceTrend'
        ));
    }

    public function teacherSchedule(Request $request)
    {
        $user = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $currentDay = $request->get('day', Carbon::today()->dayOfWeekIso);
        if($currentDay > 6) $currentDay = 1;

        $schedules = \App\Models\ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $currentDay)
            ->orderBy('study_period_id')
            ->get();

        return view('admin.dashboard.teacher_schedule', compact('teacher', 'schedules'));
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

    private function getAttendanceTrend()
    {
        $days = [];
        $studentCounts = [];
        $teacherCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $days[] = Carbon::parse($date)->format('d M');
            
            $studentCounts[] = StudentAttendance::where('date', $date)->count();
            $teacherCounts[] = Attendance::where('date', $date)->count();
        }

        return [
            'labels' => $days,
            'students' => $studentCounts,
            'teachers' => $teacherCounts,
        ];
    }
}
