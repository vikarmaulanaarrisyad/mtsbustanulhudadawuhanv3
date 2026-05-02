<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudentHistory;
use App\Models\StudentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPlacementController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        $studentStatuses = StudentStatus::all();
        return view('admin.academic.placements.index', compact('academicYears', 'classGroups', 'studentStatuses'));
    }

    public function data(Request $request)
    {
        $query = Student::with(['classGroup', 'academicYear', 'studentStatus'])
            ->where('is_active', true)
            ->when($request->academic_year_id, function($q) use ($request) {
                if ($request->academic_year_id === 'none') {
                    return $q->whereNull('academic_year_id');
                }
                return $q->where('academic_year_id', $request->academic_year_id);
            })
            ->whereNull('student_class_group_id')
            ->when($request->status_id, fn($q) => $q->where('student_status_id', $request->status_id))
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) {
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox">';
            })
            ->addColumn('kelas', fn($s) => $s->kelas_lengkap)
            ->addColumn('status', fn($s) => $s->studentStatus->student_status_name ?? '-')
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'target_academic_year_id' => 'required|exists:academic_years,id',
            'target_class_group_id' => 'required|exists:class_groups,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // Create History
                StudentHistory::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $request->target_academic_year_id,
                    'class_group_id' => $request->target_class_group_id,
                    'status' => 'enrolled',
                    'notes' => $request->notes ?? 'Penempatan Rombel',
                    'entry_date' => now(),
                ]);

                // Update Student Current State
                $student->update([
                    'academic_year_id' => $request->target_academic_year_id,
                    'student_class_group_id' => $request->target_class_group_id,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Penempatan rombel berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
