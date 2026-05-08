<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\PerformanceAssessment;
use App\Models\PerformanceAssessmentDetail;
use App\Models\PerformanceIndicator;
use App\Models\Teacher;
use App\Imports\PerformanceIndicatorImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
}
