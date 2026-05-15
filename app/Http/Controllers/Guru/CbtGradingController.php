<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use App\Models\Teacher;
use App\Models\Setting;
use App\Services\GeminiAiService;
use App\Services\GroqAiService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CbtGradingController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        
        // Get exams that belong to this teacher's banks
        $exams = CbtExam::whereHas('bank', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
        ->withCount(['studentExams' => function($q) {
            $q->where('status', 'finished');
        }])
        ->orderBy('exam_date', 'desc')
        ->get();

        return view('guru.cbt.grading.index', compact('exams'));
    }

    public function show(CbtExam $exam)
    {
        $exam->load(['bank', 'studentExams.student.profile']);
        
        $students = $exam->studentExams()
            ->where('status', 'finished')
            ->get();

        return view('guru.cbt.grading.show', compact('exam', 'students'));
    }

    public function grade(CbtStudentExam $studentExam)
    {
        $studentExam->load(['student', 'exam.bank']);
        
        // Get all essay questions from the bank
        $questions = $studentExam->exam->bank->questions()
            ->whereIn('question_type', ['essay', 'uraian'])
            ->get();

        // Get student answers for these questions
        $answers = CbtStudentAnswer::where('cbt_student_exam_id', $studentExam->id)
            ->whereIn('cbt_question_id', $questions->pluck('id'))
            ->get()
            ->keyBy('cbt_question_id');

        // Map answers to questions so the view always shows all questions
        $essayAnswers = $questions->map(function($q) use ($answers, $studentExam) {
            $ans = $answers->get($q->id) ?? new CbtStudentAnswer([
                'cbt_student_exam_id' => $studentExam->id,
                'cbt_question_id' => $q->id,
                'answer_text' => null,
                'score' => 0
            ]);
            $ans->setRelation('question', $q);
            return $ans;
        });

        return view('guru.cbt.grading.grade', compact('studentExam', 'essayAnswers'));
    }

    public function saveGrade(Request $request)
    {
        $request->validate([
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
            'student_exam_id' => 'required|exists:cbt_student_exams,id',
            'question_id' => 'required|exists:cbt_questions,id'
        ]);

        $answer = CbtStudentAnswer::updateOrCreate(
            [
                'cbt_student_exam_id' => $request->student_exam_id,
                'cbt_question_id' => $request->question_id
            ],
            [
                'score' => $request->score,
                'feedback' => $request->feedback,
                'is_correct' => $request->score > 0
            ]
        );

        // Recalculate final score for the exam
        $this->recalculateFinalScore($answer->studentExam);

        return response()->json([
            'success' => true, 
            'message' => 'Nilai berhasil disimpan',
            'new_total_score' => $answer->studentExam->fresh()->final_score
        ]);
    }

    public function aiGrade(CbtStudentAnswer $answer)
    {
        $answer->load('question');
        $setting = Setting::first();
        $aiService = ($setting->ai_provider === 'gemini') ? app(GeminiAiService::class) : app(GroqAiService::class);
        
        try {
            $result = $aiService->evaluateAnswer(
                $answer->question->question_text,
                $answer->question->answer_key ?? '',
                $answer->answer_text ?? '',
                $answer->question->score_weight
            );

            return response()->json([
                'success' => true,
                'score' => $result['score'] ?? 0,
                'feedback' => $result['feedback'] ?? 'Analisis AI selesai.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function recalculateFinalScore(CbtStudentExam $studentExam)
    {
        $exam = $studentExam->exam;
        $totalWeight = $exam->bank->questions->sum('score_weight');
        $obtainedScore = CbtStudentAnswer::where('cbt_student_exam_id', $studentExam->id)->sum('score');

        $finalScore = $totalWeight > 0 ? ($obtainedScore / $totalWeight) * 100 : 0;

        $studentExam->update([
            'final_score' => $finalScore
        ]);
    }
}
