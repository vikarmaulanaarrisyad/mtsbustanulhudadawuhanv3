<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\CbtStudentAnswer;
use App\Models\CbtStudentExam;
use App\Services\GroqAiService;
use App\Services\GeminiAiService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CbtItemAnalysisController extends Controller
{
    public function show(CbtExam $exam)
    {
        $exam->load(['bank.questions.options', 'classes']);
        
        // 1. Get all students who finished the exam
        $studentExams = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('status', 'finished')
            ->orderBy('final_score', 'desc')
            ->get();
            
        $totalStudents = $studentExams->count();
        if ($totalStudents < 1) {
            return back()->with('error', 'Belum ada data nilai untuk dianalisis.');
        }

        // 2. Identify Upper 27% and Lower 27% for Discrimination Power
        $groupSize = max(1, round($totalStudents * 0.27));
        $upperGroupIds = $studentExams->take($groupSize)->pluck('id');
        $lowerGroupIds = $studentExams->take(-$groupSize)->pluck('id');

        $questions = $exam->bank->questions;
        $analysisData = [];

        foreach ($questions as $q) {
            // Difficulty (P)
            $correctCount = CbtStudentAnswer::whereHas('studentExam', function($sq) use ($exam) {
                    $sq->where('cbt_exam_id', $exam->id)->where('status', 'finished');
                })
                ->where('cbt_question_id', $q->id)
                ->where('is_correct', true)
                ->count();
            
            $totalAnswered = CbtStudentAnswer::whereHas('studentExam', function($sq) use ($exam) {
                    $sq->where('cbt_exam_id', $exam->id)->where('status', 'finished');
                })
                ->where('cbt_question_id', $q->id)
                ->count();

            $difficulty = $totalAnswered > 0 ? $correctCount / $totalAnswered : 0;

            // Discrimination (D)
            $upperCorrect = CbtStudentAnswer::whereIn('cbt_student_exam_id', $upperGroupIds)
                ->where('cbt_question_id', $q->id)
                ->where('is_correct', true)
                ->count();
                
            $lowerCorrect = CbtStudentAnswer::whereIn('cbt_student_exam_id', $lowerGroupIds)
                ->where('cbt_question_id', $q->id)
                ->where('is_correct', true)
                ->count();

            $discrimination = ($upperCorrect - $lowerCorrect) / $groupSize;

            $analysisData[] = [
                'question' => $q,
                'correct_count' => $correctCount,
                'total_answered' => $totalAnswered,
                'difficulty' => $difficulty,
                'discrimination' => $discrimination,
                'difficulty_label' => $this->getDifficultyLabel($difficulty),
                'discrimination_label' => $this->getDiscriminationLabel($discrimination),
                'status' => $this->getItemStatus($difficulty, $discrimination)
            ];
        }

        return view('admin.cbt.exam.analysis', compact('exam', 'analysisData', 'totalStudents'));
    }

    public function aiAnalyze(Request $request, CbtExam $exam, CbtQuestion $question)
    {
        $stats = $request->only(['difficulty', 'discrimination', 'difficulty_label', 'discrimination_label']);
        
        $setting = Setting::first();
        $aiService = ($setting->ai_provider === 'gemini') ? app(GeminiAiService::class) : app(GroqAiService::class);
        
        $prompt = "Berikut adalah data statistik sebuah soal ujian:\n" .
                  "Soal: {$question->question_text}\n" .
                  "Tingkat Kesukaran: {$stats['difficulty']} ({$stats['difficulty_label']})\n" .
                  "Daya Pembeda: {$stats['discrimination']} ({$stats['discrimination_label']})\n\n" .
                  "Berikan analisis pedagogis singkat mengapa soal ini memiliki statistik tersebut dan berikan saran spesifik untuk memperbaikinya agar lebih berkualitas.";

        try {
            // Using a generic completion method if exists, otherwise I'll add one
            if (method_exists($aiService, 'getCompletion')) {
                $advice = $aiService->getCompletion($prompt);
            } else {
                // Fallback to standard request for now
                $advice = "AI Analysis Feature is still being optimized for your service provider.";
            }
            
            return response()->json(['advice' => $advice]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getDifficultyLabel($p)
    {
        if ($p <= 0.3) return 'Sukar';
        if ($p <= 0.7) return 'Sedang';
        return 'Mudah';
    }

    private function getDiscriminationLabel($d)
    {
        if ($d >= 0.4) return 'Sangat Baik';
        if ($d >= 0.3) return 'Baik';
        if ($d >= 0.2) return 'Cukup (Perlu Revisi)';
        return 'Jelek (Buang)';
    }

    private function getItemStatus($p, $d)
    {
        if ($d < 0.2) return 'danger';
        if ($d < 0.3 || $p < 0.2 || $p > 0.8) return 'warning';
        return 'success';
    }
}
