<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassTransferController extends Controller
{
    public function index()
    {
        $currentAy = AcademicYear::where('current_semester', true)->first();
        if (!$currentAy) {
            $currentAy = AcademicYear::orderBy('academic_year', 'desc')->first();
        }

        $classGroups = ClassGroup::where('academic_year_id', $currentAy->id)
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        return view('admin.academic.class_transfer.index', compact('currentAy', 'classGroups'));
    }

    public function data(Request $request)
    {
        $query = Student::with(['classGroup'])
            ->where('is_active', true)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('student_class_group_id', $request->class_group_id)
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) {
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox">';
            })
            ->addColumn('nis_nisn', fn($s) => ($s->nis ?? '-') . ' / ' . ($s->nisn ?? '-'))
            ->escapeColumns([])
            ->make(true);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'target_class_group_id' => 'required|exists:class_groups,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $targetClass = ClassGroup::findOrFail($request->target_class_group_id);

            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // Record History for the transfer (within same year)
                StudentHistory::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $student->academic_year_id,
                    'class_group_id' => $targetClass->id,
                    'status' => 'enrolled',
                    'notes' => $request->notes ?? 'Mutasi Rombel (Internal)',
                    'entry_date' => now(),
                ]);

                // Update Student Class
                $student->update([
                    'student_class_group_id' => $targetClass->id,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Mutasi rombel berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
