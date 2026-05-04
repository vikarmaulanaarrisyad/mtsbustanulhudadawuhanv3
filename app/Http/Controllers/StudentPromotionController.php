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
        $academicYearId = $request->academic_year_id;
        $classGroupId = $request->class_group_id;

        // Find students who have a history record matching the filter
        // OR whose current state matches the filter (for new students)
        $query = Student::with(['classGroup', 'academicYear'])
            ->where('is_active', true)
            ->whereHas('classGroup', function($q) {
                $q->whereNotIn('class_level', [6, 9, 12]);
            })
            ->where(function($q) use ($academicYearId, $classGroupId) {
                // Option 1: Current state matches
                $q->where(function($q2) use ($academicYearId, $classGroupId) {
                    $q2->when($academicYearId, fn($q3) => $q3->where('academic_year_id', $academicYearId))
                       ->when($classGroupId, fn($q3) => $q3->where('student_class_group_id', $classGroupId));
                });
                
                // Option 2: Had a history in this year/class
                $q->orWhereHas('histories', function($q2) use ($academicYearId, $classGroupId) {
                    $q2->when($academicYearId, fn($q3) => $q3->where('academic_year_id', $academicYearId))
                       ->when($classGroupId, fn($q3) => $q3->where('class_group_id', $classGroupId));
                });
            })
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) {
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox">';
            })
            ->addColumn('history_info', function($s) use ($academicYearId, $classGroupId) {
                // If the student's current year is DIFFERENT from the filter, they are "Promoted"
                if ($academicYearId && $s->academic_year_id != $academicYearId) {
                    return '<span class="badge badge-success">Sudah Diproses ke ' . $s->academicYear->academic_year . '</span>';
                }
                return '<span class="badge badge-warning">Belum Diproses</span>';
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
            'status' => 'required|in:promoted,retained',
            'notes' => 'nullable|string',
            'force' => 'nullable|boolean', // To bypass warning
        ]);

        try {
            DB::beginTransaction();

            $targetClass = ClassGroup::findOrFail($request->target_class_group_id);
            
            // Note: Occupancy check disabled temporarily to allow user to resolve data state issues.
            
            $successCount = 0;
            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // Skip if already in the target year and class (avoid duplicates)
                if ($student->academic_year_id == $request->target_academic_year_id && $student->student_class_group_id == $request->target_class_group_id) {
                    continue;
                }
                
                // 1. Record current state to history if it doesn't exist yet (Historical continuity)
                $hasCurrentHistory = StudentHistory::where('student_id', $student->id)
                    ->where('academic_year_id', $student->academic_year_id)
                    ->where('class_group_id', $student->student_class_group_id)
                    ->exists();
                
                if (!$hasCurrentHistory) {
                    StudentHistory::create([
                        'student_id' => $student->id,
                        'academic_year_id' => $student->academic_year_id,
                        'class_group_id' => $student->student_class_group_id,
                        'status' => 'enrolled',
                        'notes' => 'Catatan otomatis awal proses',
                        'entry_date' => $student->tanggal_masuk ?? now(),
                    ]);
                }

                // 2. Create New History Record for Target
                StudentHistory::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $request->target_academic_year_id,
                    'class_group_id' => $request->target_class_group_id,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'entry_date' => now(),
                ]);

                // 3. Update Student Current State
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
