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
        $academicYears = AcademicYear::with('semester')->orderBy('academic_year', 'desc')->get();
        
        // We get all class groups, but frontend will filter them
        $classGroups = ClassGroup::orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        $studentStatuses = StudentStatus::all();
        
        return view('admin.academic.placements.index', compact('academicYears', 'classGroups', 'studentStatuses'));
    }

    public function data(Request $request)
    {
        $query = Student::with(['classGroup', 'academicYear', 'studentStatus'])
            ->where('is_active', true)
            ->whereNull('student_class_group_id')
            ->when($request->academic_year_id, function($q) use ($request) {
                if ($request->academic_year_id === 'none') {
                    return $q->whereNull('academic_year_id');
                }
                return $q->where('academic_year_id', $request->academic_year_id);
            })
            ->when($request->class_level, fn($q) => $q->where('current_class_level', $request->class_level))
            ->when($request->status_id, fn($q) => $q->where('student_status_id', $request->status_id))
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) {
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox">';
            })
            ->addColumn('kelas_info', function($s) {
                $level = $s->current_class_level ? "Tingkat $s->current_class_level" : "Belum ditentukan";
                return '<span class="badge badge-secondary">' . $level . '</span>';
            })
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
                
                $this->assignToClass($student, $request->target_class_group_id, $request->target_academic_year_id);
            }

            DB::commit();
            return response()->json(['message' => 'Penempatan rombel berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function autoPlacement(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_group_ids' => 'required|array|min:1',
            'max_capacity' => 'required|integer|min:1',
            'gender_balanced' => 'nullable',
        ]);

        $students = Student::where('is_active', true)
            ->whereNull('student_class_group_id')
            ->orderBy('nama_lengkap')
            ->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'Tidak ada siswa baru (tanpa kelas) yang tersedia untuk dipindahkan.'], 422);
        }

        $classGroups = ClassGroup::whereIn('id', $request->class_group_ids)->get();
        $totalCapacity = 0;
        
        // Hitung sisa kapasitas riil di setiap kelas
        $classCapacityMap = [];
        foreach ($classGroups as $cg) {
            $currentCount = Student::where('student_class_group_id', $cg->id)->count();
            $remaining = $request->max_capacity - $currentCount;
            if ($remaining > 0) {
                $classCapacityMap[] = [
                    'id' => $cg->id,
                    'remaining' => $remaining,
                    'current' => $currentCount
                ];
                $totalCapacity += $remaining;
            }
        }

        if ($students->count() > $totalCapacity) {
            return response()->json(['message' => 'Jumlah siswa (' . $students->count() . ') melebihi total sisa kapasitas kelas yang dipilih (' . $totalCapacity . ').'], 422);
        }

        try {
            DB::beginTransaction();

            $successCount = 0;
            
            if ($request->gender_balanced) {
                $males = $students->where('jenis_kelamin', 'L')->values();
                $females = $students->where('jenis_kelamin', 'P')->values();
                
                $mIndex = 0;
                $fIndex = 0;

                // Loop pembagian bergantian L & P agar merata
                while ($mIndex < $males->count() || $fIndex < $females->count()) {
                    foreach ($classCapacityMap as &$class) {
                        if ($class['remaining'] <= 0) continue;

                        // Coba masukkan Laki-laki
                        if ($mIndex < $males->count()) {
                            $this->assignToClass($males[$mIndex], $class['id'], $request->academic_year_id);
                            $class['remaining']--;
                            $mIndex++;
                            $successCount++;
                        }

                        if ($class['remaining'] <= 0) continue;

                        // Coba masukkan Perempuan
                        if ($fIndex < $females->count()) {
                            $this->assignToClass($females[$fIndex], $class['id'], $request->academic_year_id);
                            $class['remaining']--;
                            $fIndex++;
                            $successCount++;
                        }
                    }
                    
                    // Break if all students assigned
                    if ($mIndex >= $males->count() && $fIndex >= $females->count()) break;
                }
            } else {
                // Pembagian biasa (tanpa balancing gender)
                $sIndex = 0;
                foreach ($classCapacityMap as $class) {
                    for ($i = 0; $i < $class['remaining']; $i++) {
                        if ($sIndex >= $students->count()) break;
                        $this->assignToClass($students[$sIndex], $class['id'], $request->academic_year_id);
                        $sIndex++;
                        $successCount++;
                    }
                    if ($sIndex >= $students->count()) break;
                }
            }

            DB::commit();
            return response()->json(['message' => "Berhasil memindahkan {$successCount} siswa ke dalam " . count($request->class_group_ids) . " rombel secara otomatis."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    private function assignToClass($student, $classId, $academicYearId)
    {
        $class = ClassGroup::find($classId);

        StudentHistory::create([
            'student_id' => $student->id,
            'academic_year_id' => $academicYearId,
            'class_group_id' => $classId,
            'status' => 'enrolled',
            'notes' => 'Penempatan Rombel',
            'entry_date' => now(),
        ]);

        $student->update([
            'academic_year_id' => $academicYearId,
            'student_class_group_id' => $classId,
            'current_class_level' => $class->class_level ?? $student->current_class_level
        ]);
    }
}
