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
        $academicYears = AcademicYear::with('semester')->orderBy('academic_year', 'desc')->get();
        $currentAY = AcademicYear::where('current_semester', true)->first();
        
        $sourceClassGroups = ClassGroup::where('academic_year_id', $currentAY->id ?? 0)
            ->whereNotIn('class_level', [6, 9, 12])
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        // Target classes will be filtered by year in frontend, but we pass all for initial load
        $targetClassGroups = ClassGroup::orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        return view('admin.academic.promotions.index', compact('academicYears', 'currentAY', 'sourceClassGroups', 'targetClassGroups'));
    }

    public function data(Request $request)
    {
        $academicYearId = $request->academic_year_id;
        $classGroupId = $request->class_group_id;

        $query = Student::with(['classGroup', 'academicYear'])
            ->where('is_active', true)
            ->where(function($q) use ($academicYearId, $classGroupId) {
                // Current state matches
                $q->where(function($q2) use ($academicYearId, $classGroupId) {
                    $q2->when($academicYearId, fn($q3) => $q3->where('academic_year_id', $academicYearId))
                       ->when($classGroupId, fn($q3) => $q3->where('student_class_group_id', $classGroupId));
                });
                
                // Had a history in this year/class
                $q->orWhereHas('histories', function($q2) use ($academicYearId, $classGroupId) {
                    $q2->when($academicYearId, fn($q3) => $q3->where('academic_year_id', $academicYearId))
                       ->when($classGroupId, fn($q3) => $q3->where('class_group_id', $classGroupId));
                });
            })
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) use ($academicYearId) {
                $isProcessed = ($academicYearId && $s->academic_year_id != $academicYearId) ? 'true' : 'false';
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox" data-processed="' . $isProcessed . '">';
            })
            ->addColumn('history_info', function($s) use ($academicYearId) {
                if ($academicYearId && $s->academic_year_id != $academicYearId) {
                    return '<span class="badge badge-success mb-1">Sudah Diproses ke ' . ($s->academicYear->academic_year ?? '-') . '</span>' . 
                           '<br><button type="button" onclick="undoSinglePromotion(' . $s->id . ')" class="btn btn-xs btn-outline-danger rounded-pill"><i class="fas fa-undo"></i> Batal</button>';
                }
                return '<span class="badge badge-warning">Belum Diproses</span>';
            })
            ->addColumn('kelas', fn($s) => $s->kelas_lengkap . ($s->current_class_level ? " (Tingkat $s->current_class_level)" : ""))
            ->escapeColumns([])
            ->make(true);
    }

    public function promote(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'target_academic_year_id' => 'required|exists:academic_years,id',
            'target_class_group_id' => 'nullable|exists:class_groups,id',
            'status' => 'required|in:promoted,retained',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $targetClass = $request->target_class_group_id ? ClassGroup::find($request->target_class_group_id) : null;
            
            $successCount = 0;
            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // VALIDATION: Prevent double promotion to the same academic year
                $alreadyProcessed = StudentHistory::where('student_id', $student->id)
                    ->where('academic_year_id', $request->target_academic_year_id)
                    ->exists();
                
                if ($alreadyProcessed) {
                    continue; // Skip this student
                }
                
                // 1. Historical continuity (record old state if missing)
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

                // Calculate new level
                $newLevel = $student->current_class_level;
                if ($request->status == 'promoted' && $newLevel < 12) {
                    $newLevel++;
                }

                // 2. Create New History Record for Target
                StudentHistory::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $request->target_academic_year_id,
                    'class_group_id' => $request->target_class_group_id, // can be null
                    'status' => $request->status,
                    'notes' => $request->notes ?? ($request->status == 'promoted' ? 'Naik ke tingkat ' . $newLevel : 'Tinggal kelas'),
                    'entry_date' => now(),
                ]);

                // 3. Update Student Current State
                $student->update([
                    'academic_year_id' => $request->target_academic_year_id,
                    'student_class_group_id' => $request->target_class_group_id,
                    'current_class_level' => $newLevel
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Proses kenaikan/penempatan berhasil dilakukan.']);
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
