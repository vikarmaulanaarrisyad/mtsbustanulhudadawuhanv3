<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::orderBy('name')->get();
        $academicYears = \App\Models\AcademicYear::with('semester')->orderBy('academic_year', 'desc')->get();
        $currentAY = \App\Models\AcademicYear::where('current_semester', true)->first();
        
        return view('admin.academic.class_group.index', compact('teachers', 'academicYears', 'currentAY'));
    }

    public function data(Request $request)
    {
        $ayId = $request->academic_year_id;
        
        $query = ClassGroup::with(['homeroomTeacher', 'academicYear.semester'])
            ->when($ayId, fn($q) => $q->where('academic_year_id', $ayId))
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->orderBy('class_level')
            ->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('wali_kelas', function($q) {
                return $q->homeroomTeacher->name ?? '<span class="badge badge-warning">Belum diatur</span>';
            })
            ->addColumn('ta_semester', function($q) {
                if (!$q->academicYear) return '-';
                return $q->academicYear->academic_year . ' (' . $q->academicYear->semester->semester_name . ')';
            })
            ->addColumn('action', function ($q) {
                return '
                <button onclick="editForm(`' . route('class-groups.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                    <i class="fa fa-pencil-alt"></i>
                </button>
                <button onclick="deleteData(`' . route('class-groups.destroy', $q->id) . '`,`' . $q->class_group . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_group' => 'required',
            'sub_class_group' => 'required',
            'class_level' => 'required',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Cek apakah kombinasi class_group, sub_class_group, dan academic_year_id sudah ada
            $exists = ClassGroup::where('class_group', $request->class_group)
                ->where('sub_class_group', $request->sub_class_group)
                ->where('academic_year_id', $request->academic_year_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kelas tersebut sudah ada di tahun pelajaran/semester ini.',
                ], 409);
            }

            $classGroup = ClassGroup::create([
                'class_group' => $request->class_group,
                'sub_class_group' => $request->sub_class_group,
                'class_level' => $request->class_level,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => $request->academic_year_id,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kelas berhasil ditambahkan.',
                'data' => $classGroup
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $query = ClassGroup::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $query = ClassGroup::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_group' => 'required',
            'sub_class_group' => 'required',
            'class_level' => 'required',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $classGroup = ClassGroup::findOrfail($id);

            // Cek apakah kombinasi sudah ada (kecuali id saat ini)
            $exists = ClassGroup::where('class_group', $request->class_group)
                ->where('sub_class_group', $request->sub_class_group)
                ->where('academic_year_id', $request->academic_year_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kelas tersebut sudah ada di tahun pelajaran/semester ini.',
                ], 409);
            }

            $data = [
                'class_group' => $request->class_group,
                'sub_class_group' => $request->sub_class_group,
                'class_level' => $request->class_level,
                'teacher_id' => $request->teacher_id,
                'academic_year_id' => $request->academic_year_id,
            ];

            $classGroup->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kelas berhasil diperbaharui.',
                'data' => $classGroup
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classGroup = ClassGroup::findOrfail($id);
        $classGroup->delete();

        return response()->json(['message' => 'Kelas berhasil dihapus.']);
    }

    public function syncFromGanjil(Request $request)
    {
        $targetAyId = $request->target_academic_year_id;
        $targetAy = \App\Models\AcademicYear::with('semester')->findOrFail($targetAyId);
        
        if ($targetAy->semester->semester_name != 'Genap') {
            return response()->json(['message' => 'Sinkronisasi hanya bisa dilakukan ke Semester Genap.'], 422);
        }

        // Cari semester ganjil di tahun pelajaran yang sama
        $sourceAy = \App\Models\AcademicYear::where('academic_year', $targetAy->academic_year)
            ->whereHas('semester', function($q) {
                $q->where('semester_name', 'Ganjil');
            })
            ->first();

        if (!$sourceAy) {
            return response()->json(['message' => 'Data Semester Ganjil untuk tahun ini tidak ditemukan.'], 404);
        }

        $sourceClasses = ClassGroup::where('academic_year_id', $sourceAy->id)->get();
        
        if ($sourceClasses->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data kelas di Semester Ganjil untuk disinkronkan.'], 404);
        }

        try {
            DB::beginTransaction();
            $count = 0;
            foreach ($sourceClasses as $sc) {
                // Check if already exists in target
                $exists = ClassGroup::where('class_group', $sc->class_group)
                    ->where('sub_class_group', $sc->sub_class_group)
                    ->where('academic_year_id', $targetAyId)
                    ->exists();
                
                if (!$exists) {
                    ClassGroup::create([
                        'class_group' => $sc->class_group,
                        'sub_class_group' => $sc->sub_class_group,
                        'class_level' => $sc->class_level,
                        'teacher_id' => $sc->teacher_id,
                        'academic_year_id' => $targetAyId,
                    ]);
                    $count++;
                }
            }
            DB::commit();
            return response()->json(['message' => "$count kelas berhasil disinkronkan dari Semester Ganjil."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
