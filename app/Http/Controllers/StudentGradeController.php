<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\GradeSetting;
use App\Models\StudentGrade;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentGradeController extends Controller
{
    // ==================== RAPORT ====================
    public function raportIndex(Request $request)
    {
        $selectedLevel = $request->level;
        $levels = ['MI', 'MTs', 'MA'];

        $queryClasses = ClassGroup::orderBy('class_level');
        if ($selectedLevel == 'MI') $queryClasses->where('class_level', 6);
        elseif ($selectedLevel == 'MTs') $queryClasses->where('class_level', 9);
        elseif ($selectedLevel == 'MA') $queryClasses->where('class_level', 12);
        else $queryClasses->whereIn('class_level', [6, 9, 12]);

        $classGroups = $queryClasses->get();
        
        $subjects = [];
        if ($selectedLevel) {
            $subjects = GradeSetting::where('level', $selectedLevel)
                ->where('type', 'raport')
                ->with('subject')
                ->orderBy('order')
                ->get();
        }

        if ($request->ajax()) {
            return response()->json([
                'classGroups' => $classGroups,
                'subjects' => $subjects
            ]);
        }

        $selectedClassId = $request->class_id;
        return view('admin.grades.raport.index', compact('classGroups', 'levels', 'subjects', 'selectedClassId', 'selectedLevel'));
    }

    public function raportData(Request $request)
    {
        $classId = $request->class_id;
        $level = $request->level;
        $subjectId = $request->subject_id;

        if (!$classId || !$level) {
            return datatables(collect([]))->make(true);
        }

        $students = Student::where('student_class_group_id', $classId)->orderBy('nama_lengkap')->get();
        
        // Get relevant class levels for the education level
        $classLevels = [];
        if ($level == 'MI') $classLevels = [4, 5, 6];
        elseif ($level == 'MTs') $classLevels = [7, 8, 9];
        elseif ($level == 'MA') $classLevels = [10, 11, 12];

        return datatables($students)
            ->addIndexColumn()
            ->addColumn('grades', function($student) use ($subjectId, $classLevels) {
                $result = [];
                
                if ($subjectId) {
                    $grades = StudentGrade::where('student_id', $student->id)
                        ->where('subject_id', $subjectId)
                        ->where('type', 'raport')
                        ->whereIn('class_level', $classLevels)
                        ->get();
                    
                    foreach ($classLevels as $cl) {
                        foreach ([1, 2] as $sem) {
                            $score = $grades->where('class_level', $cl)->where('semester', $sem)->first()->score ?? 0;
                            $result["c{$cl}s{$sem}"] = $score;
                        }
                    }
                } else {
                    foreach ($classLevels as $cl) {
                        foreach ([1, 2] as $sem) {
                            $result["c{$cl}s{$sem}"] = 0;
                        }
                    }
                }
                return $result;
            })
            ->addColumn('action', function($student) {
                return '<button type="button" class="btn btn-xs btn-primary btn-save-grade" data-id="'.$student->id.'">Simpan</button>';
            })
            ->make(true);
    }

    public function saveRaport(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
        ]);

        foreach ($request->grades as $key => $score) {
            // key format: c4s1, c4s2, etc.
            if (preg_match('/c(\d+)s(\d+)/', $key, $matches)) {
                $classLevel = $matches[1];
                $semester = $matches[2];
                
                StudentGrade::updateOrCreate(
                    [
                        'student_id' => $request->student_id,
                        'subject_id' => $request->subject_id,
                        'type' => 'raport',
                        'class_level' => $classLevel,
                        'semester' => $semester,
                    ],
                    ['score' => $score ?? 0]
                );
            }
        }

        return response()->json(['message' => 'Nilai berhasil disimpan']);
    }

    // ==================== EXAM ====================
    public function examIndex(Request $request)
    {
        $selectedLevel = $request->level;
        $levels = ['MI', 'MTs', 'MA'];

        $queryClasses = ClassGroup::orderBy('class_level');
        if ($selectedLevel == 'MI') $queryClasses->where('class_level', 6);
        elseif ($selectedLevel == 'MTs') $queryClasses->where('class_level', 9);
        elseif ($selectedLevel == 'MA') $queryClasses->where('class_level', 12);
        else $queryClasses->whereIn('class_level', [6, 9, 12]);

        $classGroups = $queryClasses->get();
        
        $subjects = [];
        if ($selectedLevel) {
            $subjects = GradeSetting::where('level', $selectedLevel)
                ->where('type', 'ujian_madrasah')
                ->with('subject')
                ->orderBy('order')
                ->get();
        }

        if ($request->ajax()) {
            return response()->json([
                'classGroups' => $classGroups,
                'subjects' => $subjects
            ]);
        }

        $selectedClassId = $request->class_id;
        return view('admin.grades.exam.index', compact('classGroups', 'levels', 'subjects', 'selectedClassId', 'selectedLevel'));
    }

    public function examData(Request $request)
    {
        $classId = $request->class_id;
        $level = $request->level;
        $subjectId = $request->subject_id;

        if (!$classId || !$level || !$subjectId) {
            return datatables(collect([]))->make(true);
        }

        $students = Student::where('student_class_group_id', $classId)->orderBy('nama_lengkap')->get();
        
        // Ujian Madrasah is usually for the final class level
        $finalClassLevel = 0;
        if ($level == 'MI') $finalClassLevel = 6;
        elseif ($level == 'MTs') $finalClassLevel = 9;
        elseif ($level == 'MA') $finalClassLevel = 12;

        return datatables($students)
            ->addIndexColumn()
            ->addColumn('score', function($student) use ($subjectId, $finalClassLevel) {
                $grade = StudentGrade::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('type', 'ujian_madrasah')
                    ->where('class_level', $finalClassLevel)
                    ->first();
                return $grade->score ?? 0;
            })
            ->make(true);
    }

    public function saveExam(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'level' => 'required|in:MI,MTs,MA',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $finalClassLevel = 0;
        if ($request->level == 'MI') $finalClassLevel = 6;
        elseif ($request->level == 'MTs') $finalClassLevel = 9;
        elseif ($request->level == 'MA') $finalClassLevel = 12;

        StudentGrade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'type' => 'ujian_madrasah',
                'class_level' => $finalClassLevel,
            ],
            ['score' => $request->score]
        );

        return response()->json(['message' => 'Nilai Ujian Madrasah berhasil disimpan']);
    }

    // ==================== EXPORT / IMPORT ====================
    public function exportRaport(Request $request)
    {
        $classId = $request->class_id;
        $level = $request->level;
        if (!$classId || !$level) return back()->with('error', 'Pilih jenjang dan kelas terlebih dahulu');

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RaportGradesExport($classId, $level), 'template_nilai_raport.xlsx');
    }

    public function importRaport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'level' => 'required|in:MI,MTs,MA',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\RaportGradesImport($request->level), $request->file('file'));
        return response()->json(['message' => 'Data nilai raport berhasil diimport']);
    }

    public function exportExam(Request $request)
    {
        $classId = $request->class_id;
        $level = $request->level;
        if (!$classId || !$level) return back()->with('error', 'Pilih jenjang dan kelas terlebih dahulu');

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ExamGradesExport($classId, $level), 'template_nilai_ujian.xlsx');
    }

    public function importExam(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'level' => 'required|in:MI,MTs,MA',
        ]);

        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\ExamGradesImport($request->level), $request->file('file'));
        return response()->json(['message' => 'Data nilai ujian berhasil diimport']);
    }

    // ==================== CERTIFICATE GENERATION ====================

    // Certificate generation methods
    public function printRaport($student_id)
    {
        $student = Student::with(['profile', 'parents', 'classGroup'])->findOrFail($student_id);
        $setting = \App\Models\MailSetting::first();
        
        $level = '';
        $classLevels = [];
        if ($student->classGroup) {
            $cl = $student->classGroup->class_level;
            if ($cl <= 6) { $level = 'MI'; $classLevels = [4, 5, 6]; }
            elseif ($cl <= 9) { $level = 'MTs'; $classLevels = [7, 8, 9]; }
            else { $level = 'MA'; $classLevels = [10, 11, 12]; }
        }

        $gradeSettings = GradeSetting::where('level', $level)
            ->where('type', 'raport')
            ->with('subject')
            ->orderBy('order')
            ->get();

        $grades = StudentGrade::where('student_id', $student_id)
            ->where('type', 'raport')
            ->get();

        $dataGrades = [];
        foreach ($gradeSettings as $gs) {
            $subjectGrades = $grades->where('subject_id', $gs->subject_id);
            $scores = [];
            $total = 0;
            $count = 0;

            foreach ($classLevels as $clvl) {
                foreach ([1, 2] as $sem) {
                    $score = $subjectGrades->where('class_level', $clvl)->where('semester', $sem)->first()->score ?? 0;
                    $scores["c{$clvl}s{$sem}"] = $score;
                    $total += $score;
                    if ($score > 0) $count++;
                }
            }

            $nr = $count > 0 ? $total / $count : 0;
            $dataGrades[] = [
                'subject' => $gs->subject->name,
                'scores' => $scores,
                'total' => $total,
                'nr' => $nr
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.grades.pdf.raport_certificate', compact('student', 'setting', 'dataGrades', 'classLevels', 'level'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('SK_Nilai_Raport_' . $student->nama_lengkap . '.pdf');
    }

    public function printSKL($student_id)
    {
        $student = Student::with(['profile', 'parents', 'classGroup'])->findOrFail($student_id);
        $setting = \App\Models\MailSetting::first();
        
        $level = '';
        $classLevels = [];
        $finalClass = 0;
        if ($student->classGroup) {
            $cl = $student->classGroup->class_level;
            if ($cl <= 6) { $level = 'MI'; $classLevels = [4, 5, 6]; $finalClass = 6; }
            elseif ($cl <= 9) { $level = 'MTs'; $classLevels = [7, 8, 9]; $finalClass = 9; }
            else { $level = 'MA'; $classLevels = [10, 11, 12]; $finalClass = 12; }
        }

        // Raport Subjects
        $raportSettings = GradeSetting::where('level', $level)
            ->where('type', 'raport')
            ->pluck('subject_id')
            ->toArray();

        // UM Subjects
        $examSettings = GradeSetting::where('level', $level)
            ->where('type', 'ujian_madrasah')
            ->with('subject')
            ->orderBy('order')
            ->get();

        $allGrades = StudentGrade::where('student_id', $student_id)->get();
        $weightRaport = $setting->weight_raport ?? 60;
        $weightExam = $setting->weight_exam ?? 40;

        $dataGrades = [];
        foreach ($examSettings as $es) {
            // NR (Average of Raport semesters)
            $raportGrades = $allGrades->where('type', 'raport')->where('subject_id', $es->subject_id);
            $totalRaport = 0;
            $countRaport = 0;
            foreach ($classLevels as $clvl) {
                foreach ([1, 2] as $sem) {
                    $score = $raportGrades->where('class_level', $clvl)->where('semester', $sem)->first()->score ?? 0;
                    $totalRaport += $score;
                    if ($score > 0) $countRaport++;
                }
            }
            $nr = $countRaport > 0 ? $totalRaport / $countRaport : 0;

            // UM
            $umScore = $allGrades->where('type', 'ujian_madrasah')->where('subject_id', $es->subject_id)->first()->score ?? 0;

            // NS (Final Grade) - Formula using weights
            $ns = ($nr * ($weightRaport / 100)) + ($umScore * ($weightExam / 100));

            $dataGrades[] = [
                'subject' => $es->subject->name,
                'nr' => $nr,
                'um' => $umScore,
                'ns' => $ns
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.grades.pdf.skl_grades', compact('student', 'setting', 'dataGrades', 'level'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Daftar_Nilai_SKL_' . $student->nama_lengkap . '.pdf');
    }

    /**
     * Generate Surat Keterangan Nilai Raport (SKNR) PDF.
     *
     * @param int    $student_id
     * @param string $target  "smp" = merge agama into one, "mts" = keep separate
     */
    public function certificate($student_id, $target)
    {
        $student = Student::with(['profile', 'parents', 'classGroup'])->findOrFail($student_id);
        $setting = \App\Models\MailSetting::first();

        // Determine current level and class levels
        $level = '';
        $semesterMap = []; // Map display semester (7, 8, 9, 10, 11) to database (class_level, semester)
        
        if ($student->classGroup) {
            $cl = $student->classGroup->class_level;
            if ($cl <= 6) { 
                $level = 'MI';
                $semesterMap = [
                    7  => ['class_level' => 4, 'semester' => 1],
                    8  => ['class_level' => 4, 'semester' => 2],
                    9  => ['class_level' => 5, 'semester' => 1],
                    10 => ['class_level' => 5, 'semester' => 2],
                    11 => ['class_level' => 6, 'semester' => 1],
                ];
            } elseif ($cl <= 9) { 
                $level = 'MTs';
                $semesterMap = [
                    7  => ['class_level' => 7, 'semester' => 1],
                    8  => ['class_level' => 7, 'semester' => 2],
                    9  => ['class_level' => 8, 'semester' => 1],
                    10 => ['class_level' => 8, 'semester' => 2],
                    11 => ['class_level' => 9, 'semester' => 1],
                ];
            } else { 
                $level = 'MA';
                $semesterMap = [
                    7  => ['class_level' => 10, 'semester' => 1],
                    8  => ['class_level' => 10, 'semester' => 2],
                    9  => ['class_level' => 11, 'semester' => 1],
                    10 => ['class_level' => 11, 'semester' => 2],
                    11 => ['class_level' => 12, 'semester' => 1],
                ];
            }
        }

        // Get all grade settings for raport
        $gradeSettings = GradeSetting::where('level', $level)
            ->where('type', 'raport')
            ->with('subject')
            ->orderBy('order')
            ->get();

        // Load all grades for the student
        $grades = StudentGrade::where('student_id', $student_id)
            ->where('type', 'raport')
            ->get();

        $dataGrades = [];
        $agamaKeywords = ['quran', 'hadis', 'akidah', 'aqidah', 'fikih', 'fiqih', 'ski', 'bahasa arab', 'agama', 'pai'];
        $mergeAgama = in_array(strtolower($target), ['smp', 'sma', 'negeri', 'umum']);

        foreach ($gradeSettings as $gs) {
            $subjectGrades = $grades->where('subject_id', $gs->subject_id);
            $scores = [];
            $totalRow = 0;
            $countRow = 0;

            foreach ($semesterMap as $displaySem => $dbInfo) {
                $score = $subjectGrades->where('class_level', $dbInfo['class_level'])
                    ->where('semester', $dbInfo['semester'])
                    ->first()->score ?? 0;
                
                $scores[$displaySem] = $score;
                $totalRow += $score;
                if ($score > 0) $countRow++;
            }

            $nr = $countRow > 0 ? $totalRow / $countRow : 0;
            $subjectNameLower = strtolower($gs->subject->name);
            $isAgama = false;
            foreach ($agamaKeywords as $keyword) {
                if (str_contains($subjectNameLower, $keyword)) {
                    $isAgama = true;
                    break;
                }
            }

            $dataGrades[] = [
                'subject'  => $gs->subject->name,
                'category' => $gs->subject->category ?? 'Lainnya',
                'scores'   => $scores,
                'total'    => $totalRow,
                'nr'       => $nr,
                'is_agama' => $isAgama,
            ];
        }

        // Grouping logic
        $groupedGrades = [
            'Kelompok A' => [],
            'Kelompok B' => [],
            'Lainnya'    => []
        ];

        if ($mergeAgama) {
            $agamaGroup = array_filter($dataGrades, fn($d) => $d['is_agama']);
            if (count($agamaGroup) > 0) {
                $merged = [
                    'subject' => 'Pendidikan Agama Islam',
                    'category'=> 'Kelompok A',
                    'scores'  => [],
                    'total'   => 0,
                    'nr'      => 0,
                ];
                $cnt = count($agamaGroup);
                foreach ($semesterMap as $sem => $info) {
                    $sumSem = 0;
                    foreach ($agamaGroup as $ag) { $sumSem += $ag['scores'][$sem]; }
                    $merged['scores'][$sem] = $cnt ? round($sumSem / $cnt, 2) : 0;
                    $merged['total'] += $merged['scores'][$sem];
                }
                $merged['nr'] = count($semesterMap) ? round($merged['total'] / count($semesterMap), 2) : 0;
                
                // Add merged PAI to Kelompok A
                $groupedGrades['Kelompok A'][] = $merged;
                
                // Add non-agama subjects to groups
                foreach ($dataGrades as $grade) {
                    if (!$grade['is_agama']) {
                        $cat = $grade['category'] ?: 'Lainnya';
                        $groupedGrades[$cat][] = $grade;
                    }
                }
            } else {
                 foreach ($dataGrades as $grade) {
                    $cat = $grade['category'] ?: 'Lainnya';
                    $groupedGrades[$cat][] = $grade;
                }
            }
        } else {
            // Keep separate
            foreach ($dataGrades as $grade) {
                $cat = $grade['category'] ?: 'Lainnya';
                $groupedGrades[$cat][] = $grade;
            }
        }

        // Clean empty groups
        $groupedGrades = array_filter($groupedGrades, fn($g) => count($g) > 0);

        // Ranking placeholder
        $rankData = [
            'rank' => 7, // Placeholder
            'total_students' => Student::where('student_class_group_id', $student->student_class_group_id)->count()
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.grades.pdf.sknr_certificate', compact(
            'student', 'setting', 'groupedGrades', 'semesterMap', 'level', 'target', 'rankData'
        ));
        
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Surat_Keterangan_Raport_' . $student->nama_lengkap . '.pdf');
    }

    public function printPDUM($student_id)
    {
        // PDUM format is often identical to SKL but maybe with different headers/styles
        return $this->printSKL($student_id);
    }
}
