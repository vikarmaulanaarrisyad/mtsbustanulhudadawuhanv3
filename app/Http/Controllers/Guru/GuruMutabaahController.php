<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\MutabaahLog;
use App\Models\Student;
use App\Models\TahfidzLog;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GuruMutabaahController extends Controller
{
    /**
     * Halaman utama — tab Mutabaah + Tahfidz untuk wali kelas.
     */
    public function index()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        // Cari kelas yang di-walikan
        $homeroomClass = ClassGroup::where('homeroom_teacher_id', $teacher?->id)->first();
        $students = collect();

        if ($homeroomClass) {
            $students = Student::active()
                ->where('student_class_group_id', $homeroomClass->id)
                ->orderBy('nama_lengkap')
                ->get();
        }

        $today = Carbon::today();
        $statToday = 0;
        $statTahfidz = 0;

        if ($homeroomClass) {
            $studentIds = $students->pluck('id');
            $statToday = MutabaahLog::whereIn('student_id', $studentIds)
                ->whereDate('date', $today)->count();
            $statTahfidz = TahfidzLog::whereIn('student_id', $studentIds)
                ->whereMonth('date', $today->month)
                ->whereYear('date', $today->year)->count();
        }

        return view('guru.mutabaah.index', compact(
            'homeroomClass', 'students', 'today',
            'statToday', 'statTahfidz'
        ));
    }

    /**
     * Bulk save mutabaah harian.
     */
    public function storeMutabaah(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'students' => 'required|array',
        ]);

        $count = 0;
        foreach ($request->students as $studentId => $data) {
            MutabaahLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $request->date],
                [
                    'shubuh'  => !empty($data['shubuh']),
                    'zhuhur'  => !empty($data['zhuhur']),
                    'ashar'   => !empty($data['ashar']),
                    'maghrib' => !empty($data['maghrib']),
                    'isya'    => !empty($data['isya']),
                    'dhuha'   => !empty($data['dhuha']),
                    'tahajud' => !empty($data['tahajud']),
                    'puasa'   => $data['puasa'] ?? null,
                    'tadarus' => $data['tadarus'] ?? null,
                ]
            );
            $count++;
        }

        return response()->json(['success' => true, 'message' => "Mutabaah {$count} siswa berhasil disimpan."]);
    }

    /**
     * Input setoran tahfidz individual.
     */
    public function storeTahfidz(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'date'        => 'required|date',
            'surah_name'  => 'required|string',
            'verse_range' => 'nullable|string',
            'juz'         => 'nullable|integer|min:1|max:30',
            'type'        => 'required|in:ziyadah,murojaah',
            'grade'       => 'required|string|max:2',
            'tajwid_score' => 'required|integer|min:0|max:100',
            'notes'       => 'nullable|string',
        ]);

        TahfidzLog::create([
            'student_id'   => $request->student_id,
            'teacher_id'   => Auth::id(),
            'date'         => $request->date,
            'surah_name'   => $request->surah_name,
            'verse_range'  => $request->verse_range,
            'juz'          => $request->juz,
            'type'         => $request->type,
            'grade'        => $request->grade,
            'tajwid_score' => $request->tajwid_score,
            'notes'        => $request->notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Setoran tahfidz berhasil disimpan!']);
    }

    /**
     * DataTable riwayat tahfidz kelas.
     */
    public function tahfidzData(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        $homeroomClass = ClassGroup::where('homeroom_teacher_id', $teacher?->id)->first();

        if (!$homeroomClass) {
            return DataTables::of(collect())->make(true);
        }

        $studentIds = Student::active()
            ->where('student_class_group_id', $homeroomClass->id)
            ->pluck('id');

        $query = TahfidzLog::with(['student', 'teacher'])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('date');

        return DataTables::of($query)
            ->addColumn('nama_siswa', fn($t) => $t->student->nama_lengkap ?? '-')
            ->addColumn('tanggal', fn($t) => Carbon::parse($t->date)->format('d M Y'))
            ->addColumn('type_badge', function ($t) {
                $color = $t->type === 'ziyadah' ? 'success' : 'info';
                return '<span class="badge badge-' . $color . '">' . ucfirst($t->type) . '</span>';
            })
            ->addColumn('grade_badge', function ($t) {
                $colors = ['A' => 'success', 'B+' => 'primary', 'B' => 'info', 'C' => 'warning', 'D' => 'danger'];
                $color = $colors[$t->grade] ?? 'secondary';
                return '<span class="badge badge-' . $color . '">' . $t->grade . '</span>';
            })
            ->rawColumns(['type_badge', 'grade_badge'])
            ->make(true);
    }

    /**
     * Load existing mutabaah data for a date.
     */
    public function getMutabaahData(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();
        $homeroomClass = ClassGroup::where('homeroom_teacher_id', $teacher?->id)->first();

        if (!$homeroomClass) return response()->json([]);

        $studentIds = Student::active()
            ->where('student_class_group_id', $homeroomClass->id)
            ->pluck('id');

        $logs = MutabaahLog::whereIn('student_id', $studentIds)
            ->whereDate('date', $request->date)
            ->get()
            ->keyBy('student_id');

        return response()->json($logs);
    }
}
