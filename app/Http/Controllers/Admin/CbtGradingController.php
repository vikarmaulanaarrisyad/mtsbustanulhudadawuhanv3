<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtStudentExam;
use App\Models\CbtStudentAnswer;
use App\Services\GeminiAiService;
use App\Services\GroqAiService;
use App\Models\Setting;
use Illuminate\Http\Request;

class CbtGradingController extends Controller
{
    /**
     * Show detailed student exam answers for grading.
     */
    public function show(CbtStudentExam $studentExam)
    {
        $studentExam->load([
            'student.classGroup',
            'exam.bank',
            'answers' => function($q) {
                $q->with(['question.options', 'option']);
            }
        ]);

        return view('admin.cbt.exam.grading', compact('studentExam'));
    }

    /**
     * Update manual score for an answer.
     */
    public function updateScore(Request $request, CbtStudentAnswer $answer)
    {
        $request->validate([
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string'
        ]);

        $answer->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'is_correct' => $request->score > 0
        ]);

        // Recalculate total score for student exam
        $this->recalculateTotalScore($answer->studentExam);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil diperbarui',
            'new_total_score' => $answer->studentExam->final_score
        ]);
    }

    /**
     * Grade an answer using AI.
     */
    public function aiGrade(CbtStudentAnswer $answer)
    {
        $question = $answer->question;
        
        if ($question->question_type !== 'essay' && $question->question_type !== 'uraian') {
            return response()->json(['success' => false, 'message' => 'AI Correction hanya untuk soal Essay/Uraian'], 400);
        }

        if (empty($answer->student_answer)) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak menjawab soal ini.'], 400);
        }

        try {
            $setting = Setting::first();
            $provider = $setting->ai_provider ?? 'gemini';
            
            if ($provider === 'groq') {
                $aiService = app(GroqAiService::class);
            } else {
                $aiService = app(GeminiAiService::class);
            }

            $result = $aiService->evaluateAnswer(
                $question->question_text,
                $question->answer_key ?? '',
                $answer->student_answer,
                $question->score_weight ?? 10,
                $question->bank->class_level ?? null
            );

            $answer->update([
                'score' => $result['score'] ?? 0,
                'feedback' => ($result['feedback'] ?? '') . ' (AI Graded)',
                'is_correct' => ($result['score'] ?? 0) > 0
            ]);

            $this->recalculateTotalScore($answer->studentExam);

            return response()->json([
                'success' => true,
                'score' => $answer->score,
                'feedback' => $answer->feedback,
                'new_total_score' => $answer->studentExam->final_score
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'AI Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Recalculate total score.
     */
    protected function recalculateTotalScore(CbtStudentExam $studentExam)
    {
        $totalScore = $studentExam->answers()->sum('score');
        $studentExam->update(['final_score' => $totalScore]);
    }
}
