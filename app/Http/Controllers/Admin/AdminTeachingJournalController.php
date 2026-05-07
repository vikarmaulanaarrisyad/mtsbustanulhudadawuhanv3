<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeachingJournal;
use App\Models\Teacher;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class AdminTeachingJournalController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.journal.index', compact('teachers'));
    }

    public function data(Request $request)
    {
        $query = TeachingJournal::with(['teacher', 'subject', 'classGroup', 'studyPeriod'])->latest('date');

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('date', fn($r) => $r->date->format('d/m/Y'))
            ->addColumn('teacher_name', fn($r) => $r->teacher->name ?? '-')
            ->addColumn('subject_name', fn($r) => $r->subject->name ?? '-')
            ->addColumn('class_name', fn($r) => $r->classGroup->kelas_lengkap ?? '-')
            ->addColumn('period', fn($r) => $r->studyPeriod->period_name ?? '-')
            ->make(true);
    }

    public function exportPdf(Request $request)
    {
        $query = TeachingJournal::with(['teacher', 'subject', 'classGroup', 'studyPeriod'])->orderBy('date', 'asc');

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $journals = $query->get();
        $setting = Setting::first();
        $teacher = $request->teacher_id ? Teacher::find($request->teacher_id) : null;

        $pdf = Pdf::loadView('admin.journal.pdf', compact('journals', 'setting', 'teacher', 'request'));
        $pdf->setPaper('a4', 'landscape'); // Better for many columns

        return $pdf->stream('Jurnal_Mengajar_' . ($teacher ? str_replace(' ', '_', $teacher->name) : 'All') . '.pdf');
    }
}
