<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtBank;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\CbtExamResultExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CbtStudentExam;

class CbtExamController extends Controller
{
    public function index()
    {
        $activeYear = \App\Models\AcademicYear::where('current_semester', 1)->first();
        
        $banks = CbtBank::all(); // Banks don't have year, but we could filter if they did
        $classes = ClassGroup::where('academic_year_id', $activeYear->id ?? 0)->get();

        return view('admin.cbt.exam.index', compact('banks', 'classes'));
    }

    public function data(Request $request)
    {
        $query = CbtExam::with(['bank', 'classes'])->withCount('studentExams');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                            <a href="'.route('admin.cbt.exam.monitor', $row->id).'" class="btn btn-sm btn-info" title="Live Monitoring"><i class="fas fa-tv"></i></a>
                            <a href="'.route('admin.cbt.exam.item-analysis', $row->id).'" class="btn btn-sm btn-primary" title="Analisis Soal"><i class="fas fa-chart-bar"></i></a>
                            <a href="'.route('admin.cbt.exam.print-exam-cards', $row->id).'" target="_blank" class="btn btn-sm btn-dark" title="Cetak Kartu"><i class="fas fa-id-card"></i></a>
                            <button onclick="editExam('.$row->id.')" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteExam('.$row->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->editColumn('classes', function($row) {
                return $row->classes->pluck('class_group')->implode(', ');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cbt_bank_id' => 'required',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|numeric',
            'classes' => 'required|array',
        ]);

        $exam = CbtExam::create([
            'name' => $request->name,
            'cbt_bank_id' => $request->cbt_bank_id,
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'token' => strtoupper(Str::random(6)),
            'is_active' => $request->boolean('is_active'),
            'display_result' => $request->boolean('display_result'),
            'detect_tab_switch' => $request->boolean('detect_tab_switch'),
            'max_violations' => $request->max_violations ?? 5,
            'auto_finish_on_limit' => $request->boolean('auto_finish_on_limit')
        ]);

        $exam->classes()->sync($request->classes);

        return response()->json(['message' => 'Jadwal Ujian berhasil ditambahkan']);
    }

    public function edit(CbtExam $exam)
    {
        $exam->load('classes');
        return response()->json($exam);
    }

    public function update(Request $request, CbtExam $exam)
    {
        $request->validate([
            'name' => 'required',
            'cbt_bank_id' => 'required',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|numeric',
            'classes' => 'required|array',
        ]);

        $exam->update([
            'name' => $request->name,
            'cbt_bank_id' => $request->cbt_bank_id,
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->boolean('is_active'),
            'display_result' => $request->boolean('display_result'),
            'detect_tab_switch' => $request->boolean('detect_tab_switch'),
            'max_violations' => $request->max_violations ?? 5,
            'auto_finish_on_limit' => $request->boolean('auto_finish_on_limit')
        ]);

        $exam->classes()->sync($request->classes);

        return response()->json(['message' => 'Jadwal Ujian berhasil diperbarui']);
    }

    public function destroy(CbtExam $exam)
    {
        $exam->delete();
        return response()->json(['message' => 'Jadwal Ujian berhasil dihapus']);
    }

    public function refreshToken(CbtExam $exam)
    {
        $exam->update(['token' => strtoupper(Str::random(6))]);
        return response()->json(['message' => 'Token berhasil diperbarui', 'token' => $exam->token]);
    }

    public function monitor(CbtExam $exam)
    {
        $exam->load([
            'bank' => function($q) { $q->withCount('questions'); },
            'classes',
            'studentExams' => function($q) {
                $q->with(['student.classGroup'])->withCount('answers');
            }
        ]);
        
        $allClassIds = $exam->classes->pluck('id');
        $allStudents = \App\Models\Student::whereIn('student_class_group_id', $allClassIds)->get();

        return view('admin.cbt.exam.monitor', compact('exam', 'allStudents'));
    }

    public function resetStudentExam(CbtStudentExam $studentExam)
    {
        // Delete answers and reset status
        $studentExam->answers()->delete();
        $studentExam->update([
            'status' => 'not_started',
            'start_time' => null,
            'end_time' => null,
            'violation_count' => 0,
            'final_score' => 0
        ]);

        return response()->json(['message' => 'Sesi ujian siswa berhasil di-reset']);
    }

    public function forceFinishStudentExam(CbtStudentExam $studentExam)
    {
        if ($studentExam->status == 'finished') {
            return response()->json(['message' => 'Siswa sudah menyelesaikan ujian'], 400);
        }

        // Calculate score (simple logic)
        $totalQuestions = $studentExam->exam->bank->questions->count();
        $correctAnswers = $studentExam->answers()->where('is_correct', true)->count();
        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

        $studentExam->update([
            'status' => 'finished',
            'end_time' => now(),
            'final_score' => $score
        ]);

        return response()->json(['message' => 'Ujian siswa berhasil dihentikan paksa']);
    }

    public function exportExcel(CbtExam $exam)
    {
        return Excel::download(new CbtExamResultExport($exam->id), "Hasil_Ujian_{$exam->name}.xlsx");
    }

    public function exportPdf(CbtExam $exam)
    {
        $exam->load(['bank', 'studentExams.student.classGroup']);
        $pdf = Pdf::loadView('admin.cbt.exam.export.result_pdf', compact('exam'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download("Laporan_Ujian_{$exam->name}.pdf");
    }

    public function exportStudentPdf(CbtStudentExam $studentExam)
    {
        $studentExam->load(['student.classGroup', 'exam.bank', 'answers.question.options', 'answers.option']);
        $pdf = Pdf::loadView('admin.cbt.exam.export.student_result_pdf', compact('studentExam'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download("Hasil_Detail_{$studentExam->student->nama_lengkap}.pdf");
    }

    public function storeSpecialSession(Request $request, CbtExam $exam)
    {
        $request->validate([
            'type' => 'required|in:remedial,susulan',
            'name' => 'required',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|numeric',
            'student_ids' => 'required|array',
            'kkm' => 'nullable|numeric'
        ]);

        $newExam = CbtExam::create([
            'name' => $request->name,
            'cbt_bank_id' => $exam->cbt_bank_id,
            'type' => $request->type,
            'exam_mode' => 'selected_students',
            'parent_exam_id' => $exam->id,
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'token' => strtoupper(Str::random(6)),
            'is_active' => true,
            'display_result' => $exam->display_result
        ]);

        // Link to the same classes just in case, but participation is limited by pre-created studentExams
        $newExam->classes()->sync($exam->classes->pluck('id'));

        // Pre-create student exams for selected students
        foreach ($request->student_ids as $studentId) {
            CbtStudentExam::create([
                'cbt_exam_id' => $newExam->id,
                'student_id' => $studentId,
                'status' => 'not_started',
                'final_score' => 0
            ]);
        }

        return response()->json([
            'message' => 'Sesi ' . ucfirst($request->type) . ' berhasil dibuat untuk ' . count($request->student_ids) . ' siswa.',
            'id' => $newExam->id
        ]);
    }

    public function printExamCards(CbtExam $exam)
    {
        $exam->load('classes');
        $allClassIds = $exam->classes->pluck('id');
        $students = \App\Models\Student::whereIn('student_class_group_id', $allClassIds)
            ->with('classGroup')
            ->get();

        $setting = \App\Models\Setting::first();
        
        // Fetch headmaster from teachers table
        $headmaster = \App\Models\Teacher::where('position', 'LIKE', '%Kepala Madrasah%')->first();
        
        // Override setting if headmaster found
        if ($headmaster) {
            $setting->headmaster_name = $headmaster->name;
            $setting->headmaster_nip = $headmaster->nip;
        }

        $pdf = Pdf::loadView('admin.cbt.exam.export.exam_cards_pdf', compact('exam', 'students', 'setting'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->stream("Kartu_Ujian_{$exam->name}.pdf");
    }

    public function exportRdm(CbtExam $exam)
    {
        $studentExams = \App\Models\CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('status', 'finished')
            ->with('student')
            ->get();

        if ($studentExams->isEmpty()) {
            return back()->with('error', 'Belum ada data nilai untuk diekspor.');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CbtRdmExport($studentExams), 
            "Format_RDM_{$exam->name}.xlsx"
        );
    }
}
