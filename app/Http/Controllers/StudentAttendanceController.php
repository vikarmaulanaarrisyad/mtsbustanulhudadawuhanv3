<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        return view('admin.academic.student_attendances.index', compact('classGroups', 'academicYears'));
    }

    public function data(Request $request)
    {
        $query = StudentAttendance::with(['student', 'classGroup'])
            ->when($request->class_group_id, fn($q) => $q->where('class_group_id', $request->class_group_id))
            ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->when($request->date, fn($q) => $q->where('date', $request->date))
            ->latest('time');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_name', fn($r) => $r->student->nama_lengkap)
            ->addColumn('nis', fn($r) => $r->student->nis)
            ->addColumn('class_name', fn($r) => $r->classGroup->kelas_lengkap)
            ->addColumn('status_badge', function ($r) {
                return '<span class="badge badge-' . $r->status_color . '">' . $r->status_label . '</span>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function scanner()
    {
        return view('admin.academic.student_attendances.scanner');
    }

    public function scan(Request $request)
    {
        $request->validate(['qr_code' => 'required']);
        
        // QR Code contains NISN or NIS. Let's assume NISN.
        $student = Student::where('nisn', $request->qr_code)->orWhere('nis', $request->qr_code)->first();
        
        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan.'], 404);
        }

        $today = Carbon::today();
        $now = Carbon::now();
        
        // Check if already scanned today
        $exists = StudentAttendance::where('student_id', $student->id)->where('date', $today->toDateString())->first();
        if ($exists) {
            return response()->json(['status' => 'warning', 'message' => $student->nama_lengkap . ' sudah melakukan presensi hari ini.']);
        }

        // Attendance Setting for Late Detection
        $setting = AttendanceSetting::first();
        $status = 'present';
        if ($setting && $now->toTimeString() > $setting->check_in_end) {
            $status = 'late';
        }

        StudentAttendance::create([
            'student_id' => $student->id,
            'academic_year_id' => $student->academic_year_id,
            'class_group_id' => $student->student_class_group_id,
            'date' => $today->toDateString(),
            'time' => $now->toTimeString(),
            'status' => $status,
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Presensi Berhasil: ' . $student->nama_lengkap,
            'data' => [
                'nama' => $student->nama_lengkap,
                'kelas' => $student->kelas_lengkap,
                'waktu' => $now->format('H:i:s'),
                'status' => $status == 'late' ? 'Terlambat' : 'Hadir'
            ]
        ]);
    }

    public function printCards(Request $request)
    {
        $query = Student::with('classGroup');
        if ($request->class_group_id) {
            $query->where('student_class_group_id', $request->class_group_id);
        }
        $students = $query->get();
        
        return view('admin.academic.student_attendances.cards', compact('students'));
    }

    public function pdf(Request $request)
    {
        $query = StudentAttendance::with(['student', 'classGroup']);

        if ($request->class_group_id) {
            $query->where('class_group_id', $request->class_group_id);
        }
        if ($request->date) {
            $query->where('date', $request->date);
        } else {
            $query->where('date', Carbon::today()->toDateString());
        }

        $attendances = $query->latest('time')->get();
        $date = $request->date ?? Carbon::today()->toDateString();
        $classGroup = $request->class_group_id ? ClassGroup::find($request->class_group_id) : null;
        $setting = \App\Models\Setting::first();

        $pdf = Pdf::loadView('admin.academic.student_attendances.pdf', compact('attendances', 'date', 'classGroup', 'setting'));
        return $pdf->stream('Laporan_Presensi_Siswa_' . $date . '.pdf');
    }
}
