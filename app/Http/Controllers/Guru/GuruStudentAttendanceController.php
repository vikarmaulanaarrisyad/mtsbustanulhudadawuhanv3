<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\ClassSchedule;
use App\Models\ClassGroup;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class GuruStudentAttendanceController extends Controller
{
    /**
     * Dapatkan instance guru saat ini.
     */
    protected function getTeacher()
    {
        return Teacher::where('user_id', auth()->id())->first();
    }

    /**
     * Halaman utama rekap absensi siswa per guru.
     */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return redirect()->route('guru.dashboard')
                ->with('error', 'Profil Guru tidak ditemukan.');
        }

        // Ambil semua ID kelas yang diajar oleh guru ini
        $myClassIds = ClassSchedule::where('teacher_id', $teacher->id)
            ->distinct()
            ->pluck('class_group_id');

        // Ambil data detail kelasnya
        $myClasses = ClassGroup::whereIn('id', $myClassIds)
            ->orderBy('class_level')
            ->orderBy('class_group')
            ->get();

        // Tambahkan kelas wali jika belum ada di daftar
        $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
        if ($homeroomClass && !$myClasses->contains('id', $homeroomClass->id)) {
            $myClasses->push($homeroomClass);
        }

        // Siapkan parameter filter
        $selectedClassId = $request->class_id;
        $selectedDate = $request->date ?? Carbon::today()->toDateString();
        
        $selectedClass = null;
        if ($selectedClassId) {
            $selectedClass = ClassGroup::find($selectedClassId);
        }

        $activeYear = AcademicYear::where('current_semester', 1)->first();

        return view('guru.student_attendances.index', compact(
            'teacher', 'myClasses', 'selectedClassId', 'selectedDate', 'selectedClass', 'activeYear'
        ));
    }

    /**
     * Ringkasan absensi per siswa dengan persentase.
     */
    public function summary(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        // Ambil kelas-kelas terkait
        $myClassIds = ClassSchedule::where('teacher_id', $teacher->id)->distinct()->pluck('class_group_id')->toArray();
        $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
        if ($homeroomClass && !in_array($homeroomClass->id, $myClassIds)) {
            $myClassIds[] = $homeroomClass->id;
        }

        $myClasses = ClassGroup::whereIn('id', $myClassIds)->get();
        $selectedClassId = $request->class_id ?? ($homeroomClass->id ?? (count($myClassIds) > 0 ? $myClassIds[0] : null));

        $students = collect();
        if ($selectedClassId) {
            $students = Student::where('student_class_group_id', $selectedClassId)
                ->where('is_active', true)
                ->withCount([
                    'attendances as present_count' => fn($q) => $q->whereIn('status', ['present', 'late']),
                    'attendances as sick_count' => fn($q) => $q->where('status', 'sick'),
                    'attendances as permit_count' => fn($q) => $q->where('status', 'permit'),
                    'attendances as absent_count' => fn($q) => $q->where('status', 'absent'),
                    'attendances as total_count'
                ])
                ->orderBy('nama_lengkap')
                ->get()
                ->map(function($s) {
                    $s->percentage = $s->total_count > 0 ? round(($s->present_count / $s->total_count) * 100, 1) : 0;
                    return $s;
                });
        }

        return view('guru.student_attendances.summary', compact('teacher', 'myClasses', 'selectedClassId', 'students'));
    }

    /**
     * Detail absensi satu siswa.
     */
    public function studentDetail($id)
    {
        $teacher = $this->getTeacher();
        $student = Student::with(['classGroup', 'profile'])->findOrFail($id);

        // Security check: apakah guru ini mengajar di kelas siswa tersebut?
        $myClassIds = ClassSchedule::where('teacher_id', $teacher->id)->distinct()->pluck('class_group_id')->toArray();
        $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
        if ($homeroomClass) $myClassIds[] = $homeroomClass->id;

        if (!in_array($student->student_class_group_id, $myClassIds)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data siswa ini.');
        }

        $stats = StudentAttendance::where('student_id', $id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $total = $stats->sum();
        $present = ($stats['present'] ?? 0) + ($stats['late'] ?? 0);
        $percentage = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        $attendances = StudentAttendance::where('student_id', $id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('guru.student_attendances.detail', compact('student', 'stats', 'total', 'present', 'percentage', 'attendances'));
    }

    /**
     * Mengambil data absensi via AJAX DataTables.
     */
    public function data(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return response()->json(['error' => 'Profil guru tidak ditemukan'], 403);
        }

        $classId = $request->class_group_id;
        $date = $request->date ?? Carbon::today()->toDateString();

        if (!$classId) {
            return datatables(collect([]))->make(true);
        }

        // Verifikasi bahwa guru ini punya akses ke kelas ini (sebagai pengajar atau wali kelas)
        $hasSchedule = ClassSchedule::where('teacher_id', $teacher->id)
            ->where('class_group_id', $classId)
            ->exists();
        
        $isHomeroom = ClassGroup::where('id', $classId)
            ->where('teacher_id', $teacher->id)->exists();

        if (!$hasSchedule && !$isHomeroom) {
            return datatables(collect([]))->make(true);
        }

        // Ambil absensi siswa pada kelas dan tanggal yang diminta
        $query = StudentAttendance::with(['student'])
            ->where('class_group_id', $classId)
            ->where('date', $date)
            ->orderBy('time', 'desc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_name', function($r) {
                $photo = $r->student->profile && $r->student->profile->foto 
                    ? asset('storage/' . $r->student->profile->foto) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($r->student->nama_lengkap) . '&background=6366f1&color=fff&bold=true';
                
                return '<div class="flex items-center space-x-3">
                            <img src="'.$photo.'" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                            <div>
                                <div class="text-sm font-black text-slate-700">'.$r->student->nama_lengkap.'</div>
                                <div class="text-[10px] font-bold text-slate-400">NIS: '.($r->student->nis ?? '-').'</div>
                            </div>
                        </div>';
            })
            ->addColumn('time', function($r) {
                return Carbon::parse($r->time)->format('H:i');
            })
            ->addColumn('status_badge', function ($r) {
                $badges = [
                    'present' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'late'    => 'bg-amber-100 text-amber-700 border-amber-200',
                    'absent'  => 'bg-rose-100 text-rose-700 border-rose-200',
                    'permit'  => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                    'sick'    => 'bg-blue-100 text-blue-700 border-blue-200',
                ];
                
                $cls = $badges[$r->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                return '<span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border '.$cls.'">' . $r->status_label . '</span>';
            })
            ->escapeColumns([]) // Agar HTML badge & profil bisa ter-render
            ->make(true);
    }
}
