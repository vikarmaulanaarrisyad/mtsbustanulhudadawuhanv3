<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $sourceClassGroups = ClassGroup::whereNotIn('class_level', [6, 9, 12])
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();
        $targetClassGroups = ClassGroup::orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();
        return view('admin.academic.promotions.index', compact('academicYears', 'sourceClassGroups', 'targetClassGroups'));
    }

    public function data(Request $request)
    {
        $query = Student::with(['classGroup', 'academicYear'])
            ->where('is_active', true)
            ->whereHas('classGroup', function($q) {
                $q->whereNotIn('class_level', [6, 9, 12]);
            })
            ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->when($request->class_group_id, fn($q) => $q->where('student_class_group_id', $request->class_group_id))
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) {
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox">';
            })
            ->addColumn('kelas', fn($s) => $s->kelas_lengkap)
            ->escapeColumns([])
            ->make(true);
    }

    public function promote(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'target_academic_year_id' => 'required|exists:academic_years,id',
            'target_class_group_id' => 'required|exists:class_groups,id',
            'status' => 'required|in:promoted,retained,enrolled',
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
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'entry_date' => now(),
                ]);

                // Update Student Current State
                $student->update([
                    'academic_year_id' => $request->target_academic_year_id,
                    'student_class_group_id' => $request->target_class_group_id,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Proses kenaikan/pindah rombel berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function undo(Request $request)
    {
        $request->validate(['student_ids' => 'required|array|min:1']);

        try {
            DB::beginTransaction();

            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // Find latest history to delete
                $latestHistory = StudentHistory::where('student_id', $id)->latest()->first();
                
                if ($latestHistory) {
                    $latestHistory->delete();
                    
                    // Find the NEW latest history to restore student state
                    $previousHistory = StudentHistory::where('student_id', $id)->latest()->first();
                    
                    if ($previousHistory) {
                        $student->update([
                            'academic_year_id' => $previousHistory->academic_year_id,
                            'student_class_group_id' => $previousHistory->class_group_id,
                        ]);
                    } else {
                        // If no more history, set to null or keep current? 
                        // Usually there should be at least one history (enrolled)
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Proses pembatalan berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
