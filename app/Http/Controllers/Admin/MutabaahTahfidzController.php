<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use App\Models\MutabaahLog;
use App\Models\Student;
use App\Models\TahfidzLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MutabaahTahfidzController extends Controller
{
    /**
     * Halaman utama Mutabaah & Tahfidz (Gaya Penempatan Rombel).
     */
    public function index()
    {
        $academicYears = AcademicYear::with('semester')->orderByDesc('id')->get();
        $classGroups = ClassGroup::orderBy('class_level')->orderBy('class_group')->get();

        $today = Carbon::today();

        $statMutabaahToday = MutabaahLog::whereDate('date', $today)->count();
        $statTahfidzMonth = TahfidzLog::whereMonth('date', $today->month)
            ->whereYear('date', $today->year)->count();
        $statAvgTajwid = TahfidzLog::whereMonth('date', $today->month)
            ->whereYear('date', $today->year)->avg('tajwid_score') ?? 0;

        $layout = auth()->user()->hasAnyRole(['Super Admin', 'Admin'])
            ? 'layouts.app' : 'layouts.teacher';

        return view('admin.mutabaah-tahfidz.index', compact(
            'academicYears', 'classGroups', 'layout',
            'statMutabaahToday', 'statTahfidzMonth', 'statAvgTajwid'
        ));
    }

    /**
     * DataTable: Daftar siswa + mutabaah hari tertentu.
     */
    public function mutabaahData(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $classGroupId = $request->get('class_group_id');

        $query = Student::with(['classGroup'])
            ->active()
            ->when($classGroupId, fn($q) => $q->where('student_class_group_id', $classGroupId))
            ->orderBy('nama_lengkap');

        return DataTables::of($query)
            ->addColumn('kelas', fn($s) => $s->kelas_lengkap)
            ->addColumn('mutabaah', function ($s) use ($date) {
                $log = MutabaahLog::where('student_id', $s->id)
                    ->whereDate('date', $date)->first();

                $fields = ['shubuh', 'zhuhur', 'ashar', 'maghrib', 'isya', 'dhuha', 'tahajud'];
                $data = [];
                foreach ($fields as $f) {
                    $data[$f] = $log ? (bool) $log->$f : false;
                }
                $data['puasa'] = $log->puasa ?? '';
                $data['tadarus'] = $log->tadarus ?? '';
                $data['id'] = $log->id ?? null;
                return $data;
            })
            ->addColumn('skor', function ($s) use ($date) {
                $log = MutabaahLog::where('student_id', $s->id)
                    ->whereDate('date', $date)->first();
                if (!$log) return 0;
                $total = ($log->shubuh + $log->zhuhur + $log->ashar + $log->maghrib + $log->isya + $log->dhuha + $log->tahajud);
                return $total;
            })
            ->rawColumns(['mutabaah'])
            ->make(true);
    }

    /**
     * DataTable: Riwayat tahfidz.
     */
    public function tahfidzData(Request $request)
    {
        $classGroupId = $request->get('class_group_id');
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $query = TahfidzLog::with(['student.classGroup', 'teacher'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->when($classGroupId, function ($q) use ($classGroupId) {
                $q->whereHas('student', fn($sq) => $sq->where('student_class_group_id', $classGroupId));
            })
            ->orderByDesc('date');

        return DataTables::of($query)
            ->addColumn('nama_siswa', fn($t) => $t->student->nama_lengkap ?? '-')
            ->addColumn('kelas', fn($t) => $t->student->kelas_lengkap ?? '-')
            ->addColumn('guru', fn($t) => $t->teacher->name ?? '-')
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
            ->addColumn('aksi', function ($t) {
                return '<button class="btn btn-xs btn-danger" onclick="deleteTahfidz(' . $t->id . ')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns(['type_badge', 'grade_badge', 'aksi'])
            ->make(true);
    }

    /**
     * Simpan/Update mutabaah batch per kelas per tanggal.
     */
    public function storeMutabaah(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'students' => 'required|array',
        ]);

        $date = $request->date;
        $count = 0;

        foreach ($request->students as $studentId => $data) {
            MutabaahLog::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
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
     * Simpan tahfidz individual.
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

        return response()->json(['success' => true, 'message' => 'Data tahfidz berhasil disimpan!']);
    }

    /**
     * Hapus log.
     */
    public function destroy($id, $type)
    {
        if ($type === 'mutabaah') {
            MutabaahLog::findOrFail($id)->delete();
        } else {
            TahfidzLog::findOrFail($id)->delete();
        }
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    /**
     * API: Get students by class group.
     */
    public function getStudents(Request $request)
    {
        $students = Student::active()
            ->where('student_class_group_id', $request->class_group_id)
            ->orderBy('nama_lengkap')
            ->get(['id', 'nama_lengkap', 'nis']);

        return response()->json($students);
    }
}
