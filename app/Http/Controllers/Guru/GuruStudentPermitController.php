<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\ClassSchedule;
use App\Models\ClassGroup;
use App\Models\StudentPermit;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class GuruStudentPermitController extends Controller
{
    /**
     * Dapatkan instance guru saat ini.
     */
    protected function getTeacher()
    {
        return Teacher::where('user_id', auth()->id())->first();
    }

    /**
     * Halaman manajemen izin siswa untuk Guru.
     */
    public function index()
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return redirect()->route('guru.dashboard')->with('error', 'Profil Guru tidak ditemukan.');
        }

        return view('guru.student_permits.index', compact('teacher'));
    }

    /**
     * Mengambil data izin siswa via AJAX DataTables.
     */
    public function data(Request $request)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return response()->json(['error' => 'Profil guru tidak ditemukan'], 403);
        }

        // Ambil ID kelas wali (Homeroom)
        $homeroomClass = ClassGroup::where('teacher_id', $teacher->id)->first();
        
        if (!$homeroomClass) {
            // Jika bukan wali kelas, kembalikan tabel kosong
            return DataTables::of(collect([]))->make(true);
        }

        // Filter izin hanya untuk siswa di kelas wali tersebut
        $query = StudentPermit::with(['student', 'student.classGroup'])
            ->whereHas('student', function ($q) use ($homeroomClass) {
                $q->where('student_class_group_id', $homeroomClass->id);
            })
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('student_info', function ($row) {
                $photo = $row->student && $row->student->profile && $row->student->profile->foto 
                    ? asset('storage/' . $row->student->profile->foto) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($row->student->nama_lengkap ?? '-') . '&background=f59e0b&color=fff&bold=true';
                
                $name = $row->student ? $row->student->nama_lengkap : '-';
                $class = $row->student && $row->student->classGroup ? $row->student->classGroup->kelas_lengkap : '-';

                return '<div class="flex items-center space-x-3">
                            <img src="'.$photo.'" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                            <div>
                                <div class="text-sm font-black text-slate-700">'.$name.'</div>
                                <div class="text-[10px] font-bold text-slate-400"><i class="fas fa-door-open mr-1"></i> '.$class.'</div>
                            </div>
                        </div>';
            })
            ->addColumn('permit_type', function ($row) {
                $color = $row->type == 'Sakit' ? 'blue' : 'indigo';
                return '<span class="px-3 py-1 bg-'.$color.'-50 text-'.$color.'-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-'.$color.'-200">'.$row->type.'</span>';
            })
            ->addColumn('date_range', function ($row) {
                $start = Carbon::parse($row->start_date)->translatedFormat('d M Y');
                $end = $row->end_date ? Carbon::parse($row->end_date)->translatedFormat('d M Y') : '';
                return $end && $start !== $end ? "$start s/d $end" : $start;
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 'pending') {
                    return '<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Menunggu</span>';
                } elseif ($row->status == 'approved') {
                    return '<span class="badge badge-success"><i class="fas fa-check mr-1"></i> Disetujui</span>';
                } else {
                    return '<span class="badge badge-danger"><i class="fas fa-times mr-1"></i> Ditolak</span>';
                }
            })
            ->addColumn('action', function ($row) {
                if ($row->status == 'pending') {
                    return '<button onclick="openActionModal('.$row->id.', \''.addslashes($row->student->nama_lengkap ?? '').'\', \''.$row->type.'\', \''.addslashes($row->reason).'\')" class="btn btn-sm btn-orange text-white font-bold rounded-lg px-3 py-2 shadow-sm text-xs"><i class="fas fa-gavel mr-1"></i> Tindakan</button>';
                }
                return '<button class="btn btn-sm btn-light text-slate-400 font-bold rounded-lg px-3 py-2 text-xs" disabled><i class="fas fa-lock mr-1"></i> Selesai</button>';
            })
            ->rawColumns(['student_info', 'permit_type', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Memproses (Approve/Reject) izin siswa.
     */
    public function approve(Request $request, $id)
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string'
        ]);

        $permit = StudentPermit::findOrFail($id);
        
        if ($permit->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Izin ini sudah diproses dan tidak dapat diubah lagi.'
            ], 400);
        }

        // Verifikasi kepemilikan kelas (HARUS Wali Kelas)
        $student = $permit->student;
        $classId = $student->student_class_group_id ?? 0;
        
        $isHomeroom = ClassGroup::where('id', $classId)->where('teacher_id', $teacher->id)->exists();

        if (!$isHomeroom) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Wali Kelas yang berwenang memproses izin siswa di kelas ini.'
            ], 403);
        }

        // Simpan pembaruan status
        $permit->status = $request->status;
        $permit->note = $request->note;
        $permit->approved_by = Auth::id();
        $permit->approved_at = now();
        $permit->save();

        // Otomatisasi Input Presensi jika Approved
        if ($request->status == 'approved' && $classId) {
            $startDate = Carbon::parse($permit->start_date);
            $endDate = $permit->end_date ? Carbon::parse($permit->end_date) : $startDate->copy();
            
            $period = CarbonPeriod::create($startDate, $endDate);
            $attendanceStatus = $permit->type == 'Sakit' ? 'sick' : 'permit';

            foreach ($period as $date) {
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $permit->student_id,
                        'date' => $date->format('Y-m-d')
                    ],
                    [
                        'academic_year_id' => $student->academic_year_id ?? 1,
                        'class_group_id' => $classId,
                        'time' => '07:00:00',
                        'status' => $attendanceStatus,
                        'notes' => 'Izin disetujui Guru: ' . $permit->reason
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin siswa berhasil ' . ($request->status == 'approved' ? 'disetujui' : 'ditolak')
        ]);
    }
}
