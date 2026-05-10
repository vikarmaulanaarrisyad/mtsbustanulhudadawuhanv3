<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\ClassSchedule;
use App\Models\ClassGroup;
use App\Models\Student;
use App\Models\Subject;
use App\Models\GradeSetting;
use App\Models\StudentGrade;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruGradeController extends Controller
{
    /**
     * Pastikan guru sudah login dan punya profil teacher.
     */
    protected function getTeacher()
    {
        return Teacher::where('user_id', auth()->id())->first();
    }

    /**
     * Halaman utama input nilai guru.
     * Guru hanya melihat kelas & mapel yang dia ampu berdasarkan jadwal.
     */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return redirect()->route('guru.dashboard')
                ->with('error', 'Profil Guru tidak ditemukan. Hubungi Administrator.');
        }

        // Ambil semua kelas unik yang diajar guru ini (dari jadwal)
        $myClassIds = ClassSchedule::where('teacher_id', $teacher->id)
            ->distinct()->pluck('class_group_id');

        $myClasses = ClassGroup::whereIn('id', $myClassIds)
            ->orderBy('class_level')
            ->orderBy('class_group')
            ->get();

        // Jika guru adalah wali kelas, tambahkan kelas tersebut juga
        $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
        if ($homeroomClass && !$myClasses->contains('id', $homeroomClass->id)) {
            $myClasses->push($homeroomClass);
        }

        // Filter mapel berdasarkan kelas yang dipilih
        $selectedClassId = $request->class_id;
        $selectedSubjectId = $request->subject_id;
        $selectedSemester = $request->semester ?? 1;

        $mySubjects = collect();
        $selectedClass = null;
        $classLevel = null;
        $educationLevel = null;

        if ($selectedClassId) {
            $selectedClass = ClassGroup::find($selectedClassId);
            if ($selectedClass) {
                $classLevel = $selectedClass->class_level;
                $educationLevel = $this->getEducationLevel($classLevel);

                // Mapel yang diajar guru di kelas ini
                $mySubjectIds = ClassSchedule::where('teacher_id', $teacher->id)
                    ->where('class_group_id', $selectedClassId)
                    ->distinct()
                    ->pluck('subject_id');

                // Cari grade settings untuk mapel tersebut
                $mySubjects = GradeSetting::whereIn('subject_id', $mySubjectIds)
                    ->where('level', $educationLevel)
                    ->where('type', 'raport')
                    ->with('subject')
                    ->orderBy('order')
                    ->get();

                // Jika tidak ada di grade settings, ambil langsung dari subject
                if ($mySubjects->isEmpty()) {
                    $mySubjects = Subject::whereIn('id', $mySubjectIds)->get()
                        ->map(function ($s) use ($classLevel, $educationLevel) {
                            return (object)[
                                'subject_id' => $s->id,
                                'subject'    => $s,
                                'level'      => $educationLevel,
                            ];
                        });
                }
            }
        }

        // Ambil tahun akademik aktif
        $activeYear = AcademicYear::where('current_semester', 1)->first();

        return view('guru.grades.index', compact(
            'teacher', 'myClasses', 'mySubjects',
            'selectedClassId', 'selectedSubjectId', 'selectedSemester',
            'selectedClass', 'classLevel', 'educationLevel', 'activeYear'
        ));
    }

    /**
     * Data siswa + nilai untuk tabel AJAX.
     */
    public function data(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return response()->json(['error' => 'Profil guru tidak ditemukan'], 403);
        }

        $classId    = $request->class_id;
        $subjectId  = $request->subject_id;
        $semester   = $request->semester ?? 1;

        if (!$classId || !$subjectId) {
            return datatables(collect([]))->make(true);
        }

        // Validasi: guru benar-benar mengajar mapel ini di kelas ini
        $hasSchedule = ClassSchedule::where('teacher_id', $teacher->id)
            ->where('class_group_id', $classId)
            ->where('subject_id', $subjectId)
            ->exists();

        // Wali kelas boleh input nilai kelas sendiri meskipun tidak ada jadwal
        $isHomeroom = ClassGroup::where('id', $classId)
            ->where('teacher_id', $teacher->id)->exists();

        if (!$hasSchedule && !$isHomeroom) {
            return datatables(collect([]))->make(true);
        }

        $classGroup = ClassGroup::find($classId);
        $classLevel = $classGroup->class_level;

        $students = Student::where('student_class_group_id', $classId)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        return datatables($students)
            ->addIndexColumn()
            ->addColumn('grade', function ($student) use ($subjectId, $classLevel, $semester) {
                $grade = StudentGrade::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('type', 'raport')
                    ->where('class_level', $classLevel)
                    ->where('semester', $semester)
                    ->first();

                return (int) ($grade->score ?? 0);
            })
            ->addColumn('photo', function ($student) {
                if ($student->profile && $student->profile->foto) {
                    return asset('storage/' . $student->profile->foto);
                }
                return 'https://ui-avatars.com/api/?name=' . urlencode($student->nama_lengkap)
                    . '&background=6366f1&color=fff&bold=true';
            })
            ->make(true);
    }

    /**
     * Simpan satu nilai siswa (AJAX, satu baris).
     */
    public function save(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return response()->json(['message' => 'Akses ditolak: profil guru tidak ditemukan'], 403);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'class_id'   => 'required|exists:class_groups,id',
            'semester'   => 'required|in:1,2',
            'score'      => 'required|numeric|min:0|max:100',
        ]);

        // Validasi guru berhak di kelas ini
        $hasSchedule = ClassSchedule::where('teacher_id', $teacher->id)
            ->where('class_group_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        $isHomeroom = ClassGroup::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)->exists();

        if (!$hasSchedule && !$isHomeroom) {
            return response()->json(['message' => 'Anda tidak berwenang mengisi nilai di kelas ini'], 403);
        }

        $classGroup = ClassGroup::find($request->class_id);
        $classLevel = $classGroup->class_level;

        StudentGrade::updateOrCreate(
            [
                'student_id'  => $request->student_id,
                'subject_id'  => $request->subject_id,
                'type'        => 'raport',
                'class_level' => $classLevel,
                'semester'    => $request->semester,
            ],
            ['score' => round($request->score)]
        );

        return response()->json(['message' => 'Nilai berhasil disimpan']);
    }

    /**
     * Simpan massal (semua siswa sekaligus) via AJAX.
     */
    public function saveBulk(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'class_id'  => 'required|exists:class_groups,id',
            'subject_id'=> 'required|exists:subjects,id',
            'semester'  => 'required|in:1,2',
            'grades'    => 'required|array',
        ]);

        $hasSchedule = ClassSchedule::where('teacher_id', $teacher->id)
            ->where('class_group_id', $request->class_id)
            ->where('subject_id', $request->subject_id)->exists();

        $isHomeroom = ClassGroup::where('id', $request->class_id)
            ->where('teacher_id', $teacher->id)->exists();

        if (!$hasSchedule && !$isHomeroom) {
            return response()->json(['message' => 'Anda tidak berwenang mengisi nilai di kelas ini'], 403);
        }

        $classGroup = ClassGroup::find($request->class_id);
        $classLevel = $classGroup->class_level;
        $saved = 0;

        DB::transaction(function () use ($request, $classLevel, &$saved) {
            foreach ($request->grades as $studentId => $score) {
                // Skip if student not in this class
                $student = Student::where('id', $studentId)
                    ->where('student_class_group_id', $request->class_id)
                    ->first();
                if (!$student) continue;

                StudentGrade::updateOrCreate(
                    [
                        'student_id'  => $studentId,
                        'subject_id'  => $request->subject_id,
                        'type'        => 'raport',
                        'class_level' => $classLevel,
                        'semester'    => $request->semester,
                    ],
                    ['score' => round(max(0, min(100, $score ?? 0)))]
                );
                $saved++;
            }
        });

        return response()->json(['message' => "Berhasil menyimpan nilai {$saved} siswa"]);
    }

    /**
     * Ambil daftar mapel yang diajar guru di kelas tertentu (AJAX).
     */
    public function getSubjects(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) return response()->json([]);

        $classId = $request->class_id;
        $classGroup = ClassGroup::find($classId);
        if (!$classGroup) return response()->json([]);

        $educationLevel = $this->getEducationLevel($classGroup->class_level);

        $subjectIds = ClassSchedule::where('teacher_id', $teacher->id)
            ->where('class_group_id', $classId)
            ->distinct()->pluck('subject_id');

        // Try grade settings first
        $subjects = GradeSetting::whereIn('subject_id', $subjectIds)
            ->where('level', $educationLevel)
            ->where('type', 'raport')
            ->with('subject')
            ->orderBy('order')
            ->get()
            ->map(fn($gs) => ['id' => $gs->subject_id, 'name' => $gs->subject->name]);

        // Fallback to raw subjects
        if ($subjects->isEmpty()) {
            $subjects = Subject::whereIn('id', $subjectIds)
                ->orderBy('name')
                ->get()
                ->map(fn($s) => ['id' => $s->id, 'name' => $s->name]);
        }

        return response()->json($subjects);
    }

    /**
     * Helper: map class_level to education level string.
     */
    protected function getEducationLevel(int $classLevel): string
    {
        if ($classLevel <= 6)  return 'MI';
        if ($classLevel <= 9)  return 'MTs';
        return 'MA';
    }
}
