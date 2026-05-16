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
            'wave' => $request->wave ?: null,
            'session' => $request->session ?: null,
            'room' => $request->room,
            'token' => strtoupper(Str::random(6)),
            'is_active' => $request->boolean('is_active'),
            'display_result' => $request->boolean('display_result'),
            'generate_certificate' => $request->boolean('generate_certificate'),
            'passing_grade' => $request->passing_grade ?? 75,
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
            'wave' => $request->wave ?: null,
            'session' => $request->session ?: null,
            'room' => $request->room,
            'is_active' => $request->boolean('is_active'),
            'display_result' => $request->boolean('display_result'),
            'generate_certificate' => $request->boolean('generate_certificate'),
            'passing_grade' => $request->passing_grade ?? 75,
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
        $exam->load(['bank.questions', 'classes']);
        return view('admin.cbt.exam.monitor', compact('exam'));
    }

    public function monitorData(CbtExam $exam)
    {
        $exam->load(['bank.questions', 'classes']);
        $allClassIds = $exam->classes->pluck('id');
        
        $students = \App\Models\Student::whereIn('student_class_group_id', $allClassIds)
            ->with(['classGroup', 'cbtExams' => function($q) use ($exam) {
                $q->where('cbt_exam_id', $exam->id);
            }])
            ->get()
            ->map(function($student) use ($exam) {
                $studentExam = $student->cbtExams->first();
                $totalQuestions = $exam->bank->questions->count();
                $progress = 0;
                if ($studentExam && $totalQuestions > 0) {
                    // progress based on answers count
                    $answered = \App\Models\CbtStudentAnswer::where('cbt_student_exam_id', $studentExam->id)->count();
                    $progress = round(($answered / $totalQuestions) * 100);
                }

                return [
                    'id' => $student->id,
                    'exam_id' => $studentExam->id ?? null,
                    'nama' => $student->nama_lengkap,
                    'kelas' => $student->classGroup ? $student->classGroup->class_group . ' ' . $student->classGroup->sub_class_group : '-',
                    'session' => $student->cbt_session,
                    'room' => $student->cbt_room,
                    'status' => $studentExam->status ?? 'not_started',
                    'progress' => $progress,
                    'current_index' => ($studentExam->last_question_index ?? 0) + 1,
                    'violations' => $studentExam->violation_count ?? 0,
                    'is_logged_in' => $studentExam->is_logged_in ?? false,
                    'last_active' => $studentExam ? $studentExam->updated_at->diffForHumans() : '-',
                ];
            });

        return response()->json([
            'exam' => $exam,
            'students' => $students
        ]);
    }

    public function sendMessage(Request $request, CbtStudentExam $studentExam)
    {
        $request->validate(['message' => 'required|string|max:255']);
        $studentExam->update(['admin_message' => $request->message]);
        return response()->json(['message' => 'Pesan terkirim ke siswa.']);
    }

    public function printAttendance(CbtExam $exam, Request $request)
    {
        $exam->load('classes');
        $query = \App\Models\Student::whereIn('student_class_group_id', $exam->classes->pluck('id'))
            ->with('classGroup')
            ->orderBy('cbt_wave')
            ->orderBy('cbt_session')
            ->orderBy('cbt_room')
            ->orderBy('nama_lengkap');

        if ($request->wave) $query->where('cbt_wave', $request->wave);
        if ($request->session) $query->where('cbt_session', $request->session);
        if ($request->room) $query->where('cbt_room', $request->room);

        $students = $query->get();
        $setting = \App\Models\Setting::first();
        $mailSetting = \App\Models\MailSetting::first();
        $sessionTimes = \App\Models\CbtSessionTime::all()->keyBy('session_number');
        
        $pdf = Pdf::loadView('admin.cbt.exam.export.attendance_pdf', compact('exam', 'students', 'setting', 'mailSetting', 'request', 'sessionTimes'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream("Daftar_Hadir_{$exam->name}.pdf");
    }

    public function printBeritaAcara(CbtExam $exam, Request $request)
    {
        $exam->load(['classes', 'bank']);
        
        $wave = $request->wave;
        $session = $request->session;
        $room = $request->room;

        $studentQuery = \App\Models\Student::whereIn('student_class_group_id', $exam->classes->pluck('id'));
        if ($wave) $studentQuery->where('cbt_wave', $wave);
        if ($session) $studentQuery->where('cbt_session', $session);
        if ($room) $studentQuery->where('cbt_room', $room);
        
        $total = $studentQuery->count();
        
        // Present students matching filters
        $presentQuery = \App\Models\CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->whereIn('status', ['doing', 'finished'])
            ->whereHas('student', function($q) use ($wave, $session, $room) {
                if ($wave) $q->where('cbt_wave', $wave);
                if ($session) $q->where('cbt_session', $session);
                if ($room) $q->where('cbt_room', $room);
            });
            
        $present = $presentQuery->count();
        $absent = $total - $present;
        
        // Auto absent list
        $absentList = $studentQuery->whereDoesntHave('cbtStudentExams', function($q) use ($exam) {
            $q->where('cbt_exam_id', $exam->id);
        })->pluck('nama_lengkap')->toArray();

        // Fetch Duty Personnel
        $duty = \App\Models\CbtDutySchedule::where('cbt_exam_id', $exam->id)
            ->where('session_number', $session)
            ->where('room_name', $room)
            ->with(['proctor', 'supervisor'])
            ->first();

        // Fetch Session Time
        $sessionTime = \App\Models\CbtSessionTime::where('session_number', $session)->first();

        $stats = [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'absent_list_auto' => implode(', ', $absentList),
            'notes' => $request->notes ?? 'Ujian berjalan lancar.',
            'wave' => $wave,
            'session' => $session,
            'room' => $room,
            'absent_manual' => $request->absent_manual,
            'proctor' => $duty->proctor->name ?? '................................',
            'supervisor' => $duty->supervisor->name ?? '................................',
            'start_time' => $sessionTime->start_time ?? $exam->start_time,
            'end_time' => $sessionTime->end_time ?? $exam->end_time
        ];

        $setting = \App\Models\Setting::first();
        $mailSetting = \App\Models\MailSetting::first();
        $headmaster = \App\Models\Teacher::where('position', 'LIKE', '%Kepala Madrasah%')->first();
        
        $pdf = Pdf::loadView('admin.cbt.exam.export.berita_acara_pdf', compact('exam', 'stats', 'setting', 'mailSetting', 'headmaster', 'request'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream("Berita_Acara_{$exam->name}.pdf");
    }

    public function storeDutySchedule(Request $request, CbtExam $exam)
    {
        $request->validate([
            'session_number' => 'required',
            'room_name' => 'required',
            'proctor_id' => 'nullable|exists:teachers,id',
            'supervisor_id' => 'nullable|exists:teachers,id'
        ]);

        \App\Models\CbtDutySchedule::updateOrCreate(
            [
                'cbt_exam_id' => $exam->id,
                'session_number' => $request->session_number,
                'room_name' => $request->room_name,
            ],
            [
                'proctor_id' => $request->proctor_id,
                'supervisor_id' => $request->supervisor_id,
            ]
        );

        return response()->json(['message' => 'Jadwal petugas berhasil disimpan.']);
    }

    public function getDutyData(CbtExam $exam)
    {
        $duties = \App\Models\CbtDutySchedule::where('cbt_exam_id', $exam->id)
            ->with(['proctor', 'supervisor'])
            ->get();
        $teachers = \App\Models\Teacher::orderBy('name')->get();
        
        // Fetch unique rooms from students involved in this exam
        $rooms = \App\Models\Student::whereIn('student_class_group_id', $exam->classes->pluck('id'))
            ->whereNotNull('cbt_room')
            ->distinct()
            ->pluck('cbt_room')
            ->sort()
            ->values();
        
        return response()->json([
            'duties' => $duties,
            'teachers' => $teachers,
            'rooms' => $rooms
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

        $sessionTimes = \App\Models\CbtSessionTime::all()->keyBy('session_number');
        
        $pdf = Pdf::loadView('admin.cbt.exam.export.exam_cards_pdf', compact('exam', 'students', 'setting', 'sessionTimes'))
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
