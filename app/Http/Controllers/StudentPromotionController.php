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
            ->orderBy('class_level', 'desc')
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        // Target classes will be filtered by year in frontend, but we pass all for initial load
        $targetClassGroups = ClassGroup::orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        // Statistics for promotion progress
        $stats = [
            'total' => Student::where('academic_year_id', $currentAY->id ?? 0)
                ->where('is_active', true)
                ->count(),
            'processed' => StudentHistory::where('academic_year_id', '!=', $currentAY->id ?? 0)
                ->whereIn('status', ['promoted', 'retained'])
                ->whereHas('student', fn($q) => $q->where('is_active', true)) // Simplified check
                ->count(),
        ];
        // Note: The processed count above is a bit simplified, but gives an idea.
        // Better: count students who have a history in current year but are now in a future year.
        $stats['processed'] = Student::whereHas('histories', fn($q) => $q->where('academic_year_id', $currentAY->id ?? 0))
            ->where('academic_year_id', '!=', $currentAY->id ?? 0)
            ->count();
        $stats['remaining'] = $stats['total']; // Total currently in this year is what remains
        
        return view('admin.academic.promotions.index', compact('academicYears', 'currentAY', 'sourceClassGroups', 'targetClassGroups', 'stats'));
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
                
                // VALIDATION: Top-Down Workflow enforcement
                $sourceLevel = $student->current_class_level;
                if (!$sourceLevel && $student->classGroup) {
                    $sourceLevel = $student->classGroup->class_level;
                }

                $higherStudentsCount = Student::where('academic_year_id', $student->academic_year_id)
                    ->where('is_active', true)
                    ->whereHas('classGroup', function($q) use ($sourceLevel) {
                        $q->where('class_level', '>', $sourceLevel);
                    })
                    ->count();

                if ($higherStudentsCount > 0) {
                    throw new \Exception("Alur Salah: Masih terdapat $higherStudentsCount siswa di tingkat yang lebih tinggi. Silakan proses (luluskan/naikkan) tingkat di atasnya terlebih dahulu untuk menjaga konsistensi data.");
                }
                
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

                // Helper to parse level from string if 0
                $parseLevel = function($cg) {
                    if (!$cg) return 0;
                    if ($cg->class_level > 0) return $cg->class_level;
                    
                    $val = $cg->class_group;
                    if (is_numeric($val)) return (int)$val;
                    
                    $romanMap = ['I'=>1,'II'=>2,'III'=>3,'IV'=>4,'V'=>5,'VI'=>6,'VII'=>7,'VIII'=>8,'IX'=>9,'X'=>10,'XI'=>11,'XII'=>12];
                    return $romanMap[strtoupper($val)] ?? 0;
                };

                // Calculate new level
                $newLevel = $student->current_class_level;
                
                // Fallback to current class level if student's record is null
                if (!$newLevel && $student->classGroup) {
                    $newLevel = $parseLevel($student->classGroup);
                }

                if ($targetClass) {
                    // If target class is selected, use its level directly
                    $newLevel = $parseLevel($targetClass);
                } else {
                    // If no target class (Rolling Mode), calculate based on status
                    if ($request->status == 'promoted' && $newLevel < 12) {
                        $newLevel++;
                    }
                    // if 'retained', $newLevel stays the same
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
                $updateData = [
                    'academic_year_id' => $request->target_academic_year_id,
                    'student_class_group_id' => $request->target_class_group_id,
                    'current_class_level' => $newLevel
                ];

                // Auto-graduation if new level reaches transition points (7, 10, 13)
                // and no target class is specified (meaning they leave the school)
                if (in_array($newLevel, [7, 10, 13]) && !$request->target_class_group_id) {
                    $updateData['student_status_id'] = 2; // Lulus
                    $updateData['is_active'] = false;
                    $updateData['tanggal_keluar'] = now();
                }

                $student->update($updateData);
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
