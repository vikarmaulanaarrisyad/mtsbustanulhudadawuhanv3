<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\PerformanceAssessment;
use App\Models\PerformanceAssessmentDetail;
use App\Models\PerformanceIndicator;
use App\Models\Teacher;
use App\Models\Setting;
use App\Imports\PerformanceIndicatorImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PerformanceRankingExport;

class PerformanceController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $currentAY = AcademicYear::where('current_semester', 1)->first();
        
        $teachers = Teacher::all();
        
        // Summarize performance by teacher for the current year
        $rankings = PerformanceAssessment::with('teacher')
            ->where('academic_year_id', $currentAY->id ?? 0)
            ->where('status', 'submitted')
            ->select('teacher_id', DB::raw('AVG(total_score) as final_score'))
            ->groupBy('teacher_id')
            ->orderByDesc('final_score')
            ->get();

        // Stats for glassmorphism widgets
        $totalTeachers = $teachers->count();
        $assessedCount = $rankings->count();
        $avgScore = $rankings->count() > 0 ? round($rankings->avg('final_score'), 1) : 0;

        // Indicators for management
        $indicators = PerformanceIndicator::all();
        $indicatorsByCategory = $indicators->groupBy('category');

        return view('admin.performance.index', compact(
            'academicYears', 'currentAY', 'teachers', 'rankings',
            'totalTeachers', 'assessedCount', 'avgScore',
            'indicators', 'indicatorsByCategory'
        ));
    }

    public function manageIndicators()
    {
        $indicators = PerformanceIndicator::all();
        $indicatorsByCategory = $indicators->groupBy('category');

        return view('admin.performance.indicators', compact('indicators', 'indicatorsByCategory'));
    }

    public function create(Request $request)
    {
        $teacherId = $request->teacher_id;
        $teacher = Teacher::findOrFail($teacherId);
        $indicators = PerformanceIndicator::all()->groupBy('category');
        $currentAY = AcademicYear::where('current_semester', 1)->first();
        
        return view('admin.performance.form', compact('teacher', 'indicators', 'currentAY'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'assessor_type' => 'required|in:headmaster,peer,student',
            'scores' => 'required|array',
            'scores.*' => 'required|integer|between:1,5',
        ]);

        try {
            DB::beginTransaction();

            $totalPoints = array_sum($request->scores);
            $maxPoints = count($request->scores) * 5;
            $percentage = ($totalPoints / $maxPoints) * 100;

            $assessment = PerformanceAssessment::create([
                'teacher_id' => $request->teacher_id,
                'assessor_id' => auth()->id(), // Assuming the assessor is the logged-in user
                'assessor_type' => $request->assessor_type,
                'academic_year_id' => $request->academic_year_id,
                'total_score' => $percentage,
                'status' => 'submitted',
                'notes' => $request->notes,
            ]);

            foreach ($request->scores as $indicatorId => $score) {
                PerformanceAssessmentDetail::create([
                    'performance_assessment_id' => $assessment->id,
                    'performance_indicator_id' => $indicatorId,
                    'score' => $score,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Penilaian kinerja berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Import indicators from Excel file.
     */
    public function importIndicators(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new PerformanceIndicatorImport();
            Excel::import($import, $request->file('file'));

            return response()->json([
                'message' => "Berhasil import {$import->getImported()} indikator. {$import->getSkipped()} data dilewati (duplikat/kosong)."
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal import: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a single indicator manually.
     */
    public function storeSingleIndicator(Request $request)
    {
        $request->validate([
            'category'       => 'required|string|max:255',
            'indicator_text' => 'required|string',
            'weight'         => 'nullable|integer|min:1|max:5',
            'target_role'    => 'nullable|string|max:50',
        ]);

        try {
            $exists = PerformanceIndicator::where('category', $request->category)
                ->where('indicator_text', $request->indicator_text)
                ->exists();

            if ($exists) {
                return response()->json(['message' => 'Indikator ini sudah ada di kategori tersebut.'], 422);
            }

            PerformanceIndicator::create([
                'category'       => $request->category,
                'indicator_text' => $request->indicator_text,
                'weight'         => $request->weight ?? 1,
                'target_role'    => $request->target_role ?? 'guru',
            ]);

            return response()->json(['message' => 'Indikator berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download Excel template for indicator import.
     */
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Indikator PKG');

        // Header
        $headers = ['kategori', 'indikator', 'bobot', 'target_role'];
        foreach ($headers as $i => $h) {
            $col = chr(65 + $i); // A, B, C, D
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('D6EAF8');
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Sample data
        $samples = [
            ['Pedagogik', 'Menguasai karakteristik peserta didik', 1, 'guru'],
            ['Pedagogik', 'Menguasai teori belajar dan prinsip pembelajaran', 1, 'guru'],
            ['Kepribadian', 'Bertindak sesuai norma agama, hukum, sosial', 1, 'guru'],
            ['Sosial', 'Bersikap inklusif dan objektif terhadap peserta didik', 1, 'guru'],
            ['Profesional', 'Menguasai materi dan struktur bidang studi', 1, 'guru'],
        ];

        foreach ($samples as $i => $row) {
            $rowNum = $i + 2;
            $sheet->setCellValue('A' . $rowNum, $row[0]);
            $sheet->setCellValue('B' . $rowNum, $row[1]);
            $sheet->setCellValue('C' . $rowNum, $row[2]);
            $sheet->setCellValue('D' . $rowNum, $row[3]);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Template_Indikator_PKG.xlsx';
        $path = storage_path('app/' . $filename);
        $writer->save($path);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Delete an indicator.
     */
    public function destroyIndicator($id)
    {
        try {
            $indicator = PerformanceIndicator::findOrFail($id);
            $indicator->delete();
            return response()->json(['message' => 'Indikator berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show breakdown for a teacher's performance.
     */
    public function show($teacherId)
    {
        $currentAY = AcademicYear::where('current_semester', 1)->first();
        if (!$currentAY) {
            return response()->json(['message' => 'Tahun akademik aktif tidak ditemukan.'], 404);
        }

        $teacher = Teacher::select('id', 'name', 'nip', 'position')->findOrFail($teacherId);

        // Optimization: Use aggregates instead of loading all models to memory
        $summary = PerformanceAssessment::where('teacher_id', $teacherId)
            ->where('academic_year_id', $currentAY->id)
            ->where('status', 'submitted')
            ->select('assessor_type', DB::raw('COUNT(*) as count'), DB::raw('AVG(total_score) as avg_score'))
            ->groupBy('assessor_type')
            ->get()
            ->keyBy('assessor_type')
            ->map(function ($item) {
                return [
                    'count' => (int) $item->count,
                    'avg_score' => round((float) $item->avg_score, 1)
                ];
            });

        // Detail breakdown by indicator category - optimized join query
        $detailsByCategory = DB::table('performance_assessment_details')
            ->join('performance_assessments', 'performance_assessment_details.performance_assessment_id', '=', 'performance_assessments.id')
            ->join('performance_indicators', 'performance_assessment_details.performance_indicator_id', '=', 'performance_indicators.id')
            ->where('performance_assessments.teacher_id', $teacherId)
            ->where('performance_assessments.academic_year_id', $currentAY->id)
            ->where('performance_assessments.status', 'submitted')
            ->select('performance_indicators.category', DB::raw('AVG(performance_assessment_details.score) as avg_score'))
            ->groupBy('performance_indicators.category')
            ->get();

        return response()->json([
            'teacher' => $teacher,
            'summary' => $summary,
            'details' => $detailsByCategory,
            'academic_year' => $currentAY->academic_year
        ]);
    }

    /**
     * Export rankings to Excel.
     */
    public function exportExcel()
    {
        $currentAY = AcademicYear::where('current_semester', 1)->first();
        $filename = 'Peringkat_Kinerja_Guru_' . str_replace('/', '-', $currentAY->academic_year ?? 'Report') . '.xlsx';
        
        return Excel::download(new PerformanceRankingExport(), $filename);
    }

    /**
     * Export rankings to PDF.
     */
    public function exportPdf()
    {
        $currentAY = AcademicYear::where('current_semester', 1)->first();
        
        $rankings = PerformanceAssessment::with('teacher')
            ->where('academic_year_id', $currentAY->id ?? 0)
            ->where('status', 'submitted')
            ->select('teacher_id', DB::raw('AVG(total_score) as final_score'))
            ->groupBy('teacher_id')
            ->orderByDesc('final_score')
            ->get();

        $pdf = Pdf::loadView('admin.performance.pdf-report', compact('rankings', 'currentAY'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan_Kinerja_Guru_' . time() . '.pdf');
    }

    /**
     * Export individual teacher performance report (PKG Format).
     */
    public function exportTeacherPdf($teacherId)
    {
        $currentAY = AcademicYear::with('semester')->where('current_semester', 1)->first();
        if (!$currentAY) {
            return back()->with('error', 'Tahun akademik aktif tidak ditemukan.');
        }

        $teacher = Teacher::findOrFail($teacherId);
        $setting = Setting::first();

        // Aggregate multiple assessments for this teacher in this AY
        $details = DB::table('performance_assessment_details')
            ->join('performance_assessments', 'performance_assessment_details.performance_assessment_id', '=', 'performance_assessments.id')
            ->join('performance_indicators', 'performance_assessment_details.performance_indicator_id', '=', 'performance_indicators.id')
            ->where('performance_assessments.teacher_id', $teacherId)
            ->where('performance_assessments.academic_year_id', $currentAY->id)
            ->where('performance_assessments.status', 'submitted')
            ->select(
                'performance_indicators.category',
                'performance_indicators.indicator_text',
                DB::raw('AVG(performance_assessment_details.score) as avg_score')
            )
            ->groupBy('performance_indicators.id', 'performance_indicators.category', 'performance_indicators.indicator_text')
            ->get();

        $groupedDetails = $details->groupBy('category');

        // Stats for recap
        $totalScore = $details->sum('avg_score');
        $maxScore = $details->count() * 5;
        $finalPercentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

        // Assessor info (latest assessment)
        $latestAssessment = PerformanceAssessment::where('teacher_id', $teacherId)
            ->where('academic_year_id', $currentAY->id)
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->first();

        $assessor = null;
        if ($latestAssessment) {
            // Find assessor in users or teachers
            $assessor = \App\Models\User::find($latestAssessment->assessor_id);
        }

        $pdf = Pdf::loadView('admin.performance.teacher-report', compact(
            'teacher', 'setting', 'currentAY', 'groupedDetails', 
            'totalScore', 'maxScore', 'finalPercentage', 'assessor'
        ));
        
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('PKG_' . str_replace(' ', '_', $teacher->name) . '_' . time() . '.pdf');
    }
}
