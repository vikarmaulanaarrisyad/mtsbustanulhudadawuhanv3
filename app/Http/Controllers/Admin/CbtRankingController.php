<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtStudentExam;
use App\Models\ClassGroup;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CbtRankingController extends Controller
{
    public function index(Request $request)
    {
        $classGroups = ClassGroup::all();
        $exams = CbtExam::with('bank')->get();

        $selectedClass = $request->class_group_id;
        $selectedExam = $request->cbt_exam_id;

        $rankings = [];
        $stats = [
            'total_students' => 0,
            'average_score' => 0,
            'highest_score' => 0
        ];

        if ($selectedExam) {
            // Ranking Per Mata Pelajaran / Ujian
            $query = CbtStudentExam::with(['student.classGroup'])
                ->where('cbt_exam_id', $selectedExam)
                ->where('status', 'finished');

            if ($selectedClass) {
                $query->whereHas('student', function($q) use ($selectedClass) {
                    $q->where('student_class_group_id', $selectedClass);
                });
            }

            $rankings = $query->orderBy('final_score', 'DESC')->get();
            
            if ($rankings->isNotEmpty()) {
                $stats['total_students'] = $rankings->count();
                $stats['average_score'] = $rankings->avg('final_score');
                $stats['highest_score'] = $rankings->max('final_score');
            }
        } else {
            // Global Ranking (Accumulated)
            $query = DB::table('cbt_student_exams')
                ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
                ->leftJoin('class_groups', 'students.student_class_group_id', '=', 'class_groups.id')
                ->select(
                    'students.id',
                    'students.nama_lengkap',
                    'students.nisn',
                    'class_groups.class_group',
                    'class_groups.sub_class_group',
                    DB::raw('SUM(final_score) as total_score'),
                    DB::raw('AVG(final_score) as average_score'),
                    DB::raw('COUNT(cbt_student_exams.id) as exams_count')
                )
                ->where('cbt_student_exams.status', 'finished')
                ->groupBy('students.id', 'students.nama_lengkap', 'students.nisn', 'class_groups.class_group', 'class_groups.sub_class_group');

            if ($selectedClass) {
                $query->where('students.student_class_group_id', $selectedClass);
            }

            $rankings = $query->orderBy('total_score', 'DESC')->get();

            if ($rankings->isNotEmpty()) {
                $stats['total_students'] = $rankings->count();
                $stats['average_score'] = $rankings->avg('average_score');
                $stats['highest_score'] = $rankings->max('total_score');
            }
        }

        return view('admin.cbt.ranking.index', compact('classGroups', 'exams', 'rankings', 'selectedClass', 'selectedExam', 'stats'));
    }
}
