<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentPermit;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class StudentPermitController extends Controller
{
    /**
     * Tampilkan halaman verifikasi izin siswa untuk Admin.
     */
    public function index()
    {
        return view('admin.student-permits.index');
    }

    /**
     * Datatable list of student permits.
     */
    public function data(Request $request)
    {
        $query = StudentPermit::with(['student', 'student.classGroup']);

        // Filter by status if needed
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('student_name', function ($row) {
                return $row->student ? $row->student->nama_lengkap : '-';
            })
            ->addColumn('class_name', function ($row) {
                return $row->student && $row->student->classGroup ? $row->student->classGroup->name : '-';
            })
            ->addColumn('date_range', function ($row) {
                $start = Carbon::parse($row->start_date)->format('d/m/Y');
                $end = $row->end_date ? Carbon::parse($row->end_date)->format('d/m/Y') : '';
                return $end ? "$start - $end" : $start;
            })
            ->addColumn('status_badge', function ($row) {
                if ($row->status == 'pending') {
                    return '<span class="badge badge-warning text-white"><i class="fas fa-clock mr-1"></i> Menunggu</span>';
                } elseif ($row->status == 'approved') {
                    return '<span class="badge badge-success text-white"><i class="fas fa-check mr-1"></i> Disetujui</span>';
                } else {
                    return '<span class="badge badge-danger text-white"><i class="fas fa-times mr-1"></i> Ditolak</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return view('admin.student-permits.action', compact('row'))->render();
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    /**
     * Approve or reject student permit.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string'
        ]);

        $permit = StudentPermit::findOrFail($id);
        
        if ($permit->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Status izin ini sudah tidak pending dan tidak dapat diubah lagi.'
            ], 400);
        }

        $permit->status = $request->status;
        $permit->note = $request->note;
        $permit->approved_by = Auth::id();
        $permit->approved_at = now();
        $permit->save();

        // Jika disetujui, buatkan record di tabel student_attendances
        if ($request->status == 'approved') {
            $student = $permit->student;
            
            // Validasi: Siswa harus sudah memiliki kelas (Rombel) untuk bisa dicatat absensinya
            if (!$student || !$student->student_class_group_id) {
                return response()->json([
                    'success' => true,
                    'message' => 'Izin disetujui, namun gagal mencatat absensi otomatis karena siswa belum ditempatkan di Rombel/Kelas.'
                ]);
            }

            $startDate = Carbon::parse($permit->start_date);
            $endDate = $permit->end_date ? Carbon::parse($permit->end_date) : $startDate->copy();
            
            $period = CarbonPeriod::create($startDate, $endDate);
            
            // Map jenis izin ke status absensi (Izin = permit, Sakit = sick)
            $attendanceStatus = $permit->type == 'Sakit' ? 'sick' : 'permit';

            foreach ($period as $date) {
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $permit->student_id,
                        'date' => $date->format('Y-m-d')
                    ],
                    [
                        'academic_year_id' => $student->academic_year_id ?? 1,
                        'class_group_id' => $student->student_class_group_id,
                        'time' => '07:00:00',
                        'status' => $attendanceStatus,
                        'notes' => 'Izin disetujui: ' . $permit->reason
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil ' . ($request->status == 'approved' ? 'disetujui' : 'ditolak')
        ]);
    }
}
