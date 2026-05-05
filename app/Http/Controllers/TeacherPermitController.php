<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherPermit;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherPermitController extends Controller
{
    /**
     * Dashboard Izin untuk Guru
     */
    public function index()
    {
        $user = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return redirect()->back()->with('error', 'Profil Guru tidak ditemukan.');
        }

        $permits = TeacherPermit::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.teacher.permits.index', compact('teacher', 'permits'));
    }

    /**
     * Simpan Pengajuan Izin
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $teacher = Teacher::where('user_id', auth()->id())->first();

        $data = $request->all();
        $data['teacher_id'] = $teacher->id;
        $data['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('permits', 'public');
        }

        TeacherPermit::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil dikirim ke Kepala Madrasah.'
        ]);
    }

    /**
     * List Izin untuk Kepala Madrasah (Admin Area)
     */
    public function adminIndex()
    {
        return view('admin.teacher.permits.admin');
    }

    /**
     * Data JSON untuk DataTables Admin
     */
    public function adminData(Request $request)
    {
        $query = TeacherPermit::with('teacher')
            ->select('teacher_permits.*') // Explicitly select only permit columns to avoid ID conflicts
            ->when($request->status, function ($q) use ($request) {
                return $q->where('teacher_permits.status', $request->status);
            })
            ->when($request->type, function ($q) use ($request) {
                return $q->where('teacher_permits.type', $request->type);
            })
            ->when($request->date_start, function ($q) use ($request) {
                return $q->whereDate('teacher_permits.start_date', '>=', $request->date_start);
            })
            ->when($request->date_end, function ($q) use ($request) {
                return $q->whereDate('teacher_permits.start_date', '<=', $request->date_end);
            })
            ->orderBy('teacher_permits.created_at', 'desc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('teacher_info', function ($p) {
                return '<div class="d-flex align-items-center py-2">
                            <div class="avatar-sm mr-3 bg-soft-emerald rounded-circle d-flex align-items-center justify-content-center text-emerald font-weight-bold shadow-sm" style="width: 45px; height: 45px; border: 2px solid #fff;">
                                ' . substr($p->teacher->name, 0, 1) . '
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark" style="font-size: 1.05rem;">' . $p->teacher->name . '</div>
                                <div class="text-xs text-muted">NIP. ' . ($p->teacher->nip ?? '-') . '</div>
                            </div>
                        </div>';
            })
            ->addColumn('permit_info', function ($p) {
                $dates = \Carbon\Carbon::parse($p->start_date)->translatedFormat('d M Y');
                if ($p->end_date) {
                    $dates .= ' - ' . \Carbon\Carbon::parse($p->end_date)->translatedFormat('d M Y');
                }
                return '<div class="mb-1">
                            <span class="badge badge-soft-dark text-dark px-2 py-1">' . $p->type . '</span>
                        </div>
                        <div class="text-xs text-muted font-italic">
                            <i class="far fa-calendar-alt mr-1"></i> ' . $dates . '
                        </div>';
            })
            ->addColumn('reason_info', function ($p) {
                $attachment = '';
                if ($p->attachment) {
                    $attachment = '<br><a href="' . asset('storage/' . $p->attachment) . '" target="_blank" class="badge badge-soft-info text-info px-2 py-1 text-[10px] uppercase mt-1">
                                    <i class="fas fa-paperclip mr-1"></i> Lampiran
                                   </a>';
                }
                return '<p class="mb-0 text-sm text-dark font-weight-500" style="max-width: 250px;">' . $p->reason . '</p>' . $attachment;
            })
            ->addColumn('status_badge', function ($p) {
                $class = $p->status == 'approved' ? 'badge-soft-success' : ($p->status == 'rejected' ? 'badge-soft-danger' : 'badge-soft-warning');
                $text = $p->status == 'approved' ? 'Disetujui' : ($p->status == 'rejected' ? 'Ditolak' : 'Menunggu');
                return '<span class="badge badge-pill px-3 py-2 text-[10px] uppercase font-weight-black ' . $class . '">' . $text . '</span>';
            })
            ->addColumn('action', function ($p) {
                if ($p->status == 'pending') {
                    return '<div class="text-right">
                                <button onclick="reviewPermit(' . $p->id . ', \'' . addslashes($p->teacher->name) . '\')" class="btn btn-emerald btn-sm rounded-pill px-4 font-weight-bold shadow-sm btn-premium">
                                    TINJAU
                                </button>
                            </div>';
                }
                return '<div class="text-right text-[10px] text-muted font-bold uppercase opacity-60">Processed</div>';
            })
            ->rawColumns(['teacher_info', 'permit_info', 'reason_info', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Update Status Izin (Approval)
     */
    public function approve(Request $request, $id)
    {
        $permit = TeacherPermit::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string',
        ]);

        $permit->update([
            'status' => $request->status,
            'note' => $request->note,
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        // Jika disetujui, buat/update record di tabel Attendance secara otomatis
        if ($request->status == 'approved') {
            $startDate = Carbon::parse($permit->start_date);
            $endDate = $permit->end_date ? Carbon::parse($permit->end_date) : $startDate;
            
            // Loop setiap tanggal dalam rentang izin
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                Attendance::updateOrCreate(
                    [
                        'teacher_id' => $permit->teacher_id,
                        'date' => $currentDate->toDateString(),
                    ],
                    [
                        'status' => (strtolower($permit->type) == 'sakit') ? 'sick' : 'permit',
                        'notes' => 'Izin: ' . $permit->reason,
                    ]
                );
                $currentDate->addDay();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status izin berhasil diperbarui.' . ($request->status == 'approved' ? ' Data absensi telah disinkronkan.' : '')
        ]);
    }
}
