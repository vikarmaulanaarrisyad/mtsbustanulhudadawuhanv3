<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        // Get academic years that have graduated students
        $graduationYears = Student::where('student_status_id', 2)
            ->whereNotNull('academic_year_id')
            ->distinct()
            ->pluck('academic_year_id');
            
        $academicYears = AcademicYear::whereIn('id', $graduationYears)
            ->orderBy('academic_year', 'desc')
            ->get();
            
        return view('admin.academic.alumni.index', compact('academicYears'));
    }

    public function data(Request $request)
    {
        $query = Student::with(['classGroup', 'academicYear'])
            ->where('student_status_id', 2); // Status Lulus

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('ta_lulus', function($s) {
                return $s->academicYear->academic_year ?? '-';
            })
            ->addColumn('kelas_terakhir', function($s) {
                return $s->kelas_lengkap ?? '-';
            })
            ->addColumn('exit_date', function($s) {
                return $s->tanggal_keluar ?? '-';
            })
            ->addColumn('notes', function($s) {
                return $s->keterangan ?? '-';
            })
            ->addColumn('action', function ($s) {
                return '
                    <a href="' . route('students.show', $s->id) . '" class="btn btn-xs btn-primary" title="Detail"><i class="fas fa-eye"></i></a>
                    <a href="' . route('graduations.print-skl', $s->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak SKL"><i class="fas fa-print"></i></a>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }
}
