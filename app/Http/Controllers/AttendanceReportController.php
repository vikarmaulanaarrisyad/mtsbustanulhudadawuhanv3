<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Teacher;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceReportController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.attendance.reports.index', compact('teachers'));
    }

    public function data(Request $request)
    {
        $query = Attendance::with('teacher')->latest('date');

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('teacher_name', fn($r) => $r->teacher->name ?? '-')
            ->addColumn('status_badge', function ($r) {
                return '<span class="badge badge-' . $r->status_color . '">' . $r->status_label . '</span>';
            })
            ->editColumn('date', fn($r) => $r->date->format('d/m/Y'))
            ->escapeColumns([])
            ->make(true);
    }

    public function print(Request $request)
    {
        $query = Attendance::with('teacher')->orderBy('date', 'desc');

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $attendances = $query->get();
        $setting = Setting::first();
        $teacher = $request->teacher_id ? Teacher::find($request->teacher_id) : null;

        $pdf = Pdf::loadView('admin.attendance.reports.pdf', compact('attendances', 'setting', 'teacher', 'request'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Absensi.pdf');
    }
}
