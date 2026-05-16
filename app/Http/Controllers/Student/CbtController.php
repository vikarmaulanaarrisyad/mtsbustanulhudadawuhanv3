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
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;

class CbtController extends Controller
{
    public function dashboard()
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        
        // Ambil ujian yang sedang aktif untuk kelas siswa ini pada hari ini
        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();
        
        $sessionTime = \App\Models\CbtSessionTime::where('session_number', $student->cbt_session)->first();

        $activeExams = CbtExam::where('is_active', true)
            ->where('exam_date', $today)
            ->where(function($q) use ($student) {
                $q->whereNull('wave')->orWhere('wave', 0)->orWhere('wave', $student->cbt_wave);
            })
            ->where(function($q) use ($student) {
                $q->whereNull('session')->orWhere('session', 0)->orWhere('session', $student->cbt_session);
            })
            ->where(function($query) use ($student) {
                $query->where(function($q) use ($student) {
                    $q->where('exam_mode', 'all_class')
                      ->whereHas('classes', function($sq) use ($student) {
                          $sq->where('class_group_id', $student->student_class_group_id);
                      });
                })->orWhere(function($q) use ($student) {
                    $q->where('exam_mode', 'selected_students')
                      ->whereHas('studentExams', function($sq) use ($student) {
                          $sq->where('student_id', $student->id);
                      });
                });
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

        return view('student.cbt.dashboard', compact('activeExams', 'student', 'stats', 'sessionTime'));
    }

    public function join(Request $request, CbtExam $exam)
    {
        $request->validate(['token' => 'required']);
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // Validasi Token
        if (strtoupper(trim($request->token)) !== $exam->token) {
            return back()->with('error', 'Token Ujian tidak valid!');
        }

        // Validasi Waktu berdasarkan Sesi Siswa
        $sessionTime = \App\Models\CbtSessionTime::where('session_number', $student->cbt_session)->first();
        $now = Carbon::now();
        $currentTime = $now->toTimeString();

        if ($sessionTime) {
            if ($currentTime < $sessionTime->start_time) {
                return back()->with('error', "Ujian untuk Sesi {$student->cbt_session} belum dimulai. Dimulai pukul {$sessionTime->start_time}.");
            }
            if ($currentTime > $sessionTime->end_time) {
                return back()->with('error', "Waktu untuk Sesi {$student->cbt_session} sudah berakhir.");
            }
        } else {
            // Fallback ke waktu ujian jika tidak ada setting sesi khusus
            if ($currentTime < $exam->start_time) {
                return back()->with('error', 'Ujian belum dimulai.');
            }
            if ($currentTime > $exam->end_time) {
                return back()->with('error', 'Waktu ujian sudah berakhir.');
            }
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

        $data = [];
        if ($request->has('option_id')) $data['cbt_option_id'] = $request->option_id;
        if ($request->has('selected_options')) $data['selected_options'] = $request->selected_options;
        if ($request->has('matching_answers')) $data['matching_answers'] = $request->matching_answers;
        if ($request->has('answer_text')) $data['answer_text'] = $request->answer_text;
        if ($request->has('is_doubtful')) $data['is_doubtful'] = $request->is_doubtful;

        if ($request->has('last_question_index')) {
            $studentExam->update(['last_question_index' => $request->last_question_index]);
        }

        if ($request->has('question_id')) {
            $answer = CbtStudentAnswer::updateOrCreate(
                [
                    'cbt_student_exam_id' => $studentExam->id,
                    'cbt_question_id' => $request->question_id
                ],
                $data
            );
            return response()->json(['message' => 'Jawaban disimpan', 'data' => $answer]);
        }

        return response()->json(['message' => 'Progress diperbarui']);
    }

    public function reportViolation(Request $request, CbtExam $exam)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        if ($studentExam->status !== 'finished') {
            $studentExam->increment('violation_count');
            
            // Check settings for auto-finish
            if ($exam->auto_finish_on_limit && $studentExam->violation_count >= $exam->max_violations) {
                $this->calculateScore($studentExam);
                return response()->json([
                    'action' => 'force_submit', 
                    'message' => 'Ujian dihentikan paksa karena telah mencapai batas maksimal pelanggaran (' . $exam->max_violations . ' kali)!',
                    'violation_count' => $studentExam->violation_count
                ]);
            }
        }

        return response()->json([
            'message' => 'Pelanggaran dicatat',
            'violation_count' => $studentExam->violation_count
        ]);
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
            if (!$question) continue;

            $isCorrect = false;
            $questionScore = 0;

            switch ($question->question_type) {
                case 'pilihan_ganda':
                    $option = $question->options->where('id', $answer->cbt_option_id)->first();
                    if ($option && $option->is_correct) {
                        $isCorrect = true;
                        $questionScore = $question->score_weight;
                    }
                    break;

                case 'ganda_komplek':
                    $correctOptionIds = $question->options->where('is_correct', true)->pluck('id')->toArray();
                    $selectedOptionIds = is_array($answer->selected_options) ? $answer->selected_options : [];
                    
                    sort($correctOptionIds);
                    sort($selectedOptionIds);
                    
                    if ($correctOptionIds === $selectedOptionIds) {
                        $isCorrect = true;
                        $questionScore = $question->score_weight;
                    }
                    break;

                case 'penjodohan':
                    $correctPairs = is_array($question->matching_pairs) ? $question->matching_pairs : [];
                    $studentPairs = is_array($answer->matching_answers) ? $answer->matching_answers : [];
                    
                    $allMatch = true;
                    if (count($correctPairs) !== count($studentPairs)) {
                        $allMatch = false;
                    } else {
                        // studentPairs is [{premise: '...', response: '...'}, ...]
                        foreach ($studentPairs as $pair) {
                            $premise = $pair['premise'] ?? '';
                            $response = $pair['response'] ?? '';
                            if (!isset($correctPairs[$premise]) || $correctPairs[$premise] !== $response) {
                                $allMatch = false;
                                break;
                            }
                        }
                    }

                    if ($allMatch) {
                        $isCorrect = true;
                        $questionScore = $question->score_weight;
                    }
                    break;

                case 'essay':
                case 'uraian':
                    // Essay is usually graded manually or by AI. 
                    // We use the 'score' column which might have been filled by AI/Teacher.
                    $questionScore = $answer->score ?? 0;
                    $isCorrect = $questionScore > 0;
                    break;
            }

            $answer->update([
                'is_correct' => $isCorrect,
                'score' => ($question->question_type === 'essay' || $question->question_type === 'uraian') ? $answer->score : $questionScore
            ]);
            
            $obtainedScore += $questionScore;
        }

        $finalScore = $totalWeight > 0 ? ($obtainedScore / $totalWeight) * 100 : 0;

        $studentExam->update([
            'status' => 'finished',
            'end_time' => Carbon::now(),
            'final_score' => $finalScore
        ]);
    }

    public function loginViaQr($token)
    {
        $student = Student::where('qr_token', $token)->first();
        
        if (!$student || !$student->user_id) {
            return redirect()->route('login')->with('error', 'Token QR tidak valid atau siswa belum memiliki akun.');
        }

        Auth::loginUsingId($student->user_id);
        
        return redirect()->route('student.cbt.dashboard')->with('success', 'Berhasil login via QR Code. Selamat mengerjakan!');
    }

    public function downloadCertificate(CbtExam $exam)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        $studentExam = CbtStudentExam::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'finished')
            ->firstOrFail();

        // Security check
        if (!$exam->generate_certificate) {
            return back()->with('error', 'Sertifikat tidak diaktifkan untuk ujian ini.');
        }

        if ($studentExam->final_score < $exam->passing_grade) {
            return back()->with('error', 'Maaf, nilai Anda belum mencapai batas KKM untuk mendapatkan sertifikat.');
        }

        $setting = Setting::first();
        $exam->load('bank.subject');

        $pdf = Pdf::loadView('pdf.cbt.certificate', compact('student', 'exam', 'studentExam', 'setting'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("Sertifikat_{$exam->name}_{$student->nama_lengkap}.pdf");
    }
}
