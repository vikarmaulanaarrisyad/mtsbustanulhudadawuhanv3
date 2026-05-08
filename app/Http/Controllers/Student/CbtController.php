<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CbtController extends Controller
{
    public function dashboard()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Ambil ujian yang sedang aktif untuk kelas siswa ini pada hari ini
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        $activeExams = CbtExam::where('is_active', true)
            ->where('exam_date', $today)
            ->whereHas('classes', function($q) use ($student) {
                $q->where('class_group_id', $student->student_class_group_id);
            })
            ->with(['bank', 'studentExams' => function($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->get();

        $stats = [
            'finished_count' => CbtStudentExam::where('student_id', $student->id)->where('status', 'finished')->count(),
            'average_score' => CbtStudentExam::where('student_id', $student->id)->where('status', 'finished')->avg('final_score') ?? 0,
            'total_violations' => CbtStudentExam::where('student_id', $student->id)->sum('violation_count')
        ];

        // Hitung Ranking Kelas (Berdasarkan Rata-rata Nilai)
        $classRankings = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->select('students.id', DB::raw('AVG(final_score) as avg_score'))
            ->where('students.student_class_group_id', $student->student_class_group_id)
            ->where('cbt_student_exams.status', 'finished')
            ->groupBy('students.id')
            ->orderBy('avg_score', 'DESC')
            ->get();

        $myRank = $classRankings->search(function($item) use ($student) {
            return $item->id == $student->id;
        });

        $stats['class_rank'] = $myRank !== false ? $myRank + 1 : '-';
        $stats['total_students'] = Student::where('student_class_group_id', $student->student_class_group_id)->count();

        return view('student.cbt.dashboard', compact('activeExams', 'student', 'stats'));
    }

    public function join(Request $request, CbtExam $exam)
    {
        $request->validate(['token' => 'required']);
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // Validasi Token
        if (strtoupper(trim($request->token)) !== $exam->token) {
            return back()->with('error', 'Token Ujian tidak valid!');
        }

        // Validasi Waktu
        $now = Carbon::now();
        if ($now->toTimeString() < $exam->start_time) {
            return back()->with('error', 'Ujian belum dimulai.');
        }
        if ($now->toTimeString() > $exam->end_time) {
            return back()->with('error', 'Waktu ujian sudah berakhir.');
        }

        // Cek apakah sudah pernah mulai
        $studentExam = CbtStudentExam::firstOrCreate(
            ['cbt_exam_id' => $exam->id, 'student_id' => $student->id],
            ['status' => 'doing', 'start_time' => $now]
        );

        if ($studentExam->status === 'finished') {
            return back()->with('error', 'Anda sudah menyelesaikan ujian ini.');
        }

        return redirect()->route('student.cbt.exam', $exam->id);
    }

    public function exam(CbtExam $exam)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($studentExam->status === 'finished') {
            return redirect()->route('student.cbt.dashboard')->with('error', 'Ujian sudah selesai.');
        }

        // Load bank and questions with randomized options (if needed)
        // For simplicity in V1, we just load them.
        $exam->load('bank.questions.options');

        // Load existing answers
        $answers = CbtStudentAnswer::where('cbt_student_exam_id', $studentExam->id)
            ->get()->keyBy('cbt_question_id');

        return view('student.cbt.exam', compact('exam', 'studentExam', 'answers'));
    }

    public function saveAnswer(Request $request, CbtExam $exam)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($studentExam->status === 'finished') {
            return response()->json(['message' => 'Ujian telah selesai'], 403);
        }

        $answer = CbtStudentAnswer::updateOrCreate(
            [
                'cbt_student_exam_id' => $studentExam->id,
                'cbt_question_id' => $request->question_id
            ],
            [
                'cbt_option_id' => $request->option_id,
                'selected_options' => $request->selected_options,
                'matching_answers' => $request->matching_answers,
                'answer_text' => $request->answer_text,
                'is_doubtful' => $request->is_doubtful ?? false
            ]
        );

        return response()->json(['message' => 'Jawaban disimpan', 'data' => $answer]);
    }

    public function reportViolation(Request $request, CbtExam $exam)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($studentExam->status !== 'finished') {
            $studentExam->increment('violation_count');
            
            // Force submit jika pelanggaran > 3
            if ($studentExam->violation_count >= 3) {
                $this->calculateScore($studentExam);
                return response()->json(['action' => 'force_submit', 'message' => 'Ujian dihentikan paksa karena pelanggaran sistem!']);
            }
        }

        return response()->json(['message' => 'Pelanggaran dicatat']);
    }

    public function finish(Request $request, CbtExam $exam)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $this->calculateScore($studentExam);

        return redirect()->route('student.cbt.dashboard')->with('success', 'Ujian berhasil diselesaikan!');
    }

    private function calculateScore(CbtStudentExam $studentExam)
    {
        $exam = $studentExam->exam;
        $exam->load('bank.questions.options');
        
        $totalWeight = $exam->bank->questions->sum('score_weight');
        $obtainedScore = 0;

        $answers = CbtStudentAnswer::where('cbt_student_exam_id', $studentExam->id)->get();

        foreach ($answers as $answer) {
            $question = $exam->bank->questions->where('id', $answer->cbt_question_id)->first();
            if ($question) {
                $option = $question->options->where('id', $answer->cbt_option_id)->first();
                if ($option && $option->is_correct) {
                    $answer->update(['is_correct' => true]);
                    $obtainedScore += $question->score_weight;
                } else {
                    $answer->update(['is_correct' => false]);
                }
            }
        }

        $finalScore = $totalWeight > 0 ? ($obtainedScore / $totalWeight) * 100 : 0;

        $studentExam->update([
            'status' => 'finished',
            'end_time' => Carbon::now(),
            'final_score' => $finalScore
        ]);
    }
}
