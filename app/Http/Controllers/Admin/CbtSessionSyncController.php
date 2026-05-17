<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CbtSessionSyncController extends Controller
{
    protected function getActiveAcademicYearId()
    {
        $activeYear = \App\Models\AcademicYear::where('current_semester', 1)->first();
        return $activeYear ? $activeYear->id : null;
    }

    public function index()
    {
        $activeYearId = $this->getActiveAcademicYearId();
        $activeYear = \App\Models\AcademicYear::find($activeYearId);
        
        $classGroups = ClassGroup::withCount('students')
            ->where('academic_year_id', $activeYearId)
            ->orderBy('class_group')->orderBy('sub_class_group')->get();
            
        $sessionTimes = \App\Models\CbtSessionTime::orderBy('session_number')->get();

        $stats = [
            'total_students' => Student::where('academic_year_id', $activeYearId)->count(),
            'assigned_students' => Student::where('academic_year_id', $activeYearId)->whereNotNull('cbt_session')->count(),
            'unassigned_students' => Student::where('academic_year_id', $activeYearId)->whereNull('cbt_session')->count(),
            'total_rooms' => Student::where('academic_year_id', $activeYearId)->whereNotNull('cbt_room')->distinct()->count('cbt_room'),
        ];

        return view('admin.cbt.session-sync.index', compact('classGroups', 'sessionTimes', 'stats', 'activeYear'));
    }

    public function listData(Request $request)
    {
        $activeYearId = $this->getActiveAcademicYearId();
        
        $query = Student::with('classGroup')
            ->leftJoin('cbt_session_times', 'students.cbt_session', '=', 'cbt_session_times.session_number')
            ->where('students.academic_year_id', $activeYearId);

        // Apply Filters
        if ($request->level) {
            $query->whereHas('classGroup', function($q) use ($request) {
                $q->where('class_group', $request->level);
            });
        }
        if ($request->class_group_id) {
            $query->where('student_class_group_id', $request->class_group_id);
        }
        if ($request->status === 'assigned') {
            $query->whereNotNull('students.cbt_session');
        } elseif ($request->status === 'unassigned') {
            $query->whereNull('students.cbt_session');
        }

        $query->select(
            'students.id', 
            'students.nama_lengkap', 
            'students.student_class_group_id', 
            'students.cbt_wave', 
            'students.cbt_session', 
            'students.cbt_room',
            'cbt_session_times.start_time',
            'cbt_session_times.end_time'
        );

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kelas', function($student) {
                return $student->classGroup ? $student->classGroup->class_group . ' ' . $student->classGroup->sub_class_group : '-';
            })
            ->addColumn('waktu_sesi', function($student) {
                if ($student->start_time && $student->end_time) {
                    return \Carbon\Carbon::parse($student->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($student->end_time)->format('H:i');
                }
                return '-';
            })
            ->make(true);
    }

    public function sync(Request $request)
    {
        $request->validate([
            'class_group_id' => 'required',
            'wave' => 'required|integer|min:1|max:4',
            'session' => 'required|integer|min:1|max:4',
            'room' => 'nullable|string|max:50',
        ]);

        try {
            $activeYearId = $this->getActiveAcademicYearId();
            $query = Student::query()->where('academic_year_id', $activeYearId);
            
            if ($request->class_group_id !== 'all') {
                $query->where('student_class_group_id', $request->class_group_id);
            }

            $count = $query->count();
            
            $query->update([
                'cbt_wave' => $request->wave,
                'cbt_session' => $request->session,
                'cbt_room' => $request->room,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Berhasil menyinkronkan {$count} siswa ke Gelombang {$request->wave}, Sesi {$request->session}."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function autoDistribute(Request $request)
    {
        $request->validate([
            'class_level' => 'required|array',
            'room_count' => 'required|integer|min:1',
            'pc_per_room' => 'required|integer|min:1',
            'session_count' => 'required|integer|min:1|max:10',
        ]);

        try {
            DB::beginTransaction();

            // Save Session Times
            \App\Models\CbtSessionTime::query()->delete();
            for ($i = 1; $i <= $request->session_count; $i++) {
                if ($request->has("session_{$i}_start") && $request->has("session_{$i}_end")) {
                    \App\Models\CbtSessionTime::create([
                        'session_number' => $i,
                        'start_time' => $request->input("session_{$i}_start"),
                        'end_time' => $request->input("session_{$i}_end"),
                    ]);
                }
            }

            $activeYearId = $this->getActiveAcademicYearId();
            $query = Student::query()->where('academic_year_id', $activeYearId);
            
            if (!empty($request->class_level)) {
                $query->whereHas('classGroup', function($q) use ($request) {
                    $q->whereIn('class_group', $request->class_level);
                });
            }

            $students = $query->get();
            $totalStudents = $students->count();

            if ($totalStudents == 0) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Tidak ada siswa ditemukan untuk kriteria ini.']);
            }

            $pcPerRoom = (int)$request->pc_per_room;
            $roomCount = (int)$request->room_count;
            $sessionCount = (int)$request->session_count;

            $currentStudentIndex = 0;
            
            // Distribute students
            for ($wave = 1; $wave <= 4; $wave++) {
                for ($session = 1; $session <= $sessionCount; $session++) {
                    for ($roomNum = 1; $roomNum <= $roomCount; $roomNum++) {
                        $roomLabel = "R" . $roomNum;
                        
                        // Assign students to this Room + Session + Wave
                        for ($pc = 1; $pc <= $pcPerRoom; $pc++) {
                            if ($currentStudentIndex < $totalStudents) {
                                $student = $students[$currentStudentIndex];
                                $student->update([
                                    'cbt_wave' => $wave,
                                    'cbt_session' => $session,
                                    'cbt_room' => $roomLabel,
                                ]);
                                $currentStudentIndex++;
                            } else {
                                break 4; // All students assigned
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Berhasil mendistribusikan {$currentStudentIndex} siswa ke dalam {$roomCount} Ruang dan {$sessionCount} Sesi secara otomatis."
            ]);
        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return response()->json([
                'status' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'target' => 'required|in:class,level',
            'class_group_id' => 'required_if:target,class',
            'class_level' => 'required_if:target,level|array',
        ]);

        try {
            $activeYearId = $this->getActiveAcademicYearId();
            $query = Student::query()->where('academic_year_id', $activeYearId);

            if ($request->target === 'class') {
                if ($request->class_group_id !== 'all') {
                    $query->where('student_class_group_id', $request->class_group_id);
                }
            } else {
                $query->whereHas('classGroup', function($q) use ($request) {
                    $q->whereIn('class_group', $request->class_level);
                });
            }

            $count = $query->count();
            $query->update([
                'cbt_wave' => null,
                'cbt_session' => null,
                'cbt_room' => null,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Berhasil mereset penempatan untuk {$count} siswa."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSessionTimes(Request $request)
    {
        $request->validate([
            'session_count' => 'required|integer|min:1|max:10',
        ]);

        try {
            DB::beginTransaction();
            \App\Models\CbtSessionTime::query()->delete();
            
            for ($i = 1; $i <= $request->session_count; $i++) {
                if ($request->has("session_{$i}_start") && $request->has("session_{$i}_end")) {
                    \App\Models\CbtSessionTime::create([
                        'session_number' => $i,
                        'start_time' => $request->input("session_{$i}_start"),
                        'end_time' => $request->input("session_{$i}_end"),
                    ]);
                }
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Konfigurasi waktu sesi berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}
