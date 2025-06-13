<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
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
        return view('admin.academic.class_group.index');
    }

    public function data()
    {
        $query = ClassGroup::orderBy('class_group')
            ->orderBy('sub_class_group')
            ->orderBy('class_level')
            ->get();

        return datatables($query)
            ->addIndexColumn()
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

            // Cek apakah kombinasi class_group dan semester_id sudah ada
            $exists = ClassGroup::where('class_group', $request->class_group)
                ->where('sub_class_group', $request->sub_class_group)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'kelas tersebut sudah ada.',
                ], 409); // 409 Conflict
            }

            // Simpan data baru
            $classGroup = ClassGroup::create([
                'class_group' => $request->class_group,
                'sub_class_group' => $request->sub_class_group,
                'class_level' => $request->class_level,
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

            // Cek apakah kombinasi class_group dan semester_id sudah ada
            $exists = ClassGroup::where('class_group', $request->class_group)
                ->where('sub_class_group', $request->sub_class_group)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'kelas tersebut sudah ada.',
                ], 409); // 409 Conflict
            }

            $data = [
                'class_group' => $request->class_group,
                'sub_class_group' => $request->sub_class_group,
                'class_level' => $request->class_level,
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
}
