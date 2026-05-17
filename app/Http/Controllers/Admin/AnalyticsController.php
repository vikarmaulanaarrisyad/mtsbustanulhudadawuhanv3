<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentGrade;
use App\Models\AcademicYear;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $selectedYearId = $request->academic_year_id ?? ($academicYears->first()->id ?? null);
        
        $stats = [
            'total_students' => Student::active()->count(),
            'avg_attendance' => $this->calculateAvgAttendance($selectedYearId),
            'avg_grade' => $this->calculateAvgGrade($selectedYearId),
        ];

        return view('admin.analytics.index', compact('academicYears', 'selectedYearId', 'stats'));
    }

    public function attendanceTrends(Request $request)
    {
        $yearId = $request->academic_year_id;
        
        $trends = StudentAttendance::where('academic_year_id', $yearId)
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('COUNT(CASE WHEN status = "present" THEN 1 END) as present'),
                DB::raw('COUNT(CASE WHEN status = "absent" THEN 1 END) as absent'),
                DB::raw('COUNT(CASE WHEN status = "sick" OR status = "permit" THEN 1 END) as permit')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $presentData = [];
        $absentData = [];
        $permitData = [];

        foreach ($trends as $t) {
            $labels[] = Carbon::create()->month($t->month)->translatedFormat('F');
            $presentData[] = $t->present;
            $absentData[] = $t->absent;
            $permitData[] = $t->permit;
        }

        return response()->json([
            'labels' => $labels,
            'present' => $presentData,
            'absent' => $absentData,
            'permit' => $permitData,
        ]);
    }

    public function gradeDistribution(Request $request)
    {
        $yearId = $request->academic_year_id;
        
        $distribution = StudentGrade::whereHas('student', function($q) use ($yearId) {
                // Assuming students are linked to academic year via their class
            })
            ->select(
                DB::raw('CASE 
                    WHEN score >= 85 THEN "A (85-100)"
                    WHEN score >= 75 THEN "B (75-84)"
                    WHEN score >= 65 THEN "C (65-74)"
                    WHEN score >= 50 THEN "D (50-64)"
                    ELSE "E (<50)"
                END as bracket'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('bracket')
            ->orderBy('bracket')
            ->get();

        return response()->json($distribution);
    }

    public function ranking(Request $request)
    {
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        
        $class_group_id = $request->class_group_id;
        $academic_year_id = $request->academic_year_id;

        $rankings = collect([]);
        $stats = [
            'total_students' => 0,
            'average_class_score' => 0,
            'highest_score' => 0
        ];

        if ($class_group_id && $academic_year_id) {
            $rankings = Student::where('student_class_group_id', $class_group_id)
                ->with(['classGroup'])
                ->get()
                ->map(function($student) use ($academic_year_id) {
                    $avgGrade = StudentGrade::where('student_id', $student->id)
                        // Add filters for academic year if necessary
                        ->avg('score');
                        
                    $attendanceRate = $this->getStudentAttendanceRate($student->id, $academic_year_id);
                    
                    return [
                        'student' => $student,
                        'avg_grade' => round($avgGrade, 2),
                        'attendance_rate' => $attendanceRate,
                        'total_score' => StudentGrade::where('student_id', $student->id)->sum('score')
                    ];
                })
                ->sortByDesc('avg_grade')
                ->values();

            if ($rankings->isNotEmpty()) {
                $stats['total_students'] = $rankings->count();
                $stats['average_class_score'] = $rankings->avg('avg_grade');
                $stats['highest_score'] = $rankings->max('avg_grade');
            }
        }

        return view('admin.analytics.ranking', compact('classGroups', 'academicYears', 'rankings', 'class_group_id', 'academic_year_id', 'stats'));
    }

    public function exportRanking(Request $request)
    {
        $class_group_id = $request->class_group_id;
        $academic_year_id = $request->academic_year_id;

        $rankings = Student::where('student_class_group_id', $class_group_id)
            ->with(['classGroup'])
            ->get()
            ->map(function($student) use ($academic_year_id) {
                $avgGrade = StudentGrade::where('student_id', $student->id)->avg('score');
                $attendanceRate = $this->getStudentAttendanceRate($student->id, $academic_year_id);
                
                return [
                    'student' => $student,
                    'avg_grade' => round($avgGrade, 2),
                    'attendance_rate' => $attendanceRate,
                    'total_score' => StudentGrade::where('student_id', $student->id)->sum('score')
                ];
            })
            ->sortByDesc('avg_grade')
            ->values();

        $className = ClassGroup::find($class_group_id)->kelas_lengkap ?? 'Kelas';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StudentRankingExport($rankings), "Ranking_Siswa_{$className}.xlsx");
    }

    private function calculateAvgAttendance($yearId)
    {
        if (!$yearId) return 0;
        $total = StudentAttendance::where('academic_year_id', $yearId)->count();
        if ($total == 0) return 0;
        $present = StudentAttendance::where('academic_year_id', $yearId)->where('status', 'present')->count();
        return round(($present / $total) * 100, 1);
    }

    private function calculateAvgGrade($yearId)
    {
        return round(StudentGrade::avg('score') ?? 0, 1);
    }

    private function getStudentAttendanceRate($studentId, $yearId)
    {
        $total = StudentAttendance::where('student_id', $studentId)->where('academic_year_id', $yearId)->count();
        if ($total == 0) return 0;
        $present = StudentAttendance::where('student_id', $studentId)->where('academic_year_id', $yearId)->where('status', 'present')->count();
        return round(($present / $total) * 100, 1);
    }
}
