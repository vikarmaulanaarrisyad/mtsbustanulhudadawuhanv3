<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmissionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.admission.admission-types.index');
    }
    public function data()
    {
        $query = AdmissionType::all();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('academic_year', function ($q) {
                return $q->academicYear->academic_year . ' ' . $q->academicYear->semester->semester_name;
            })
            ->addColumn('action', function ($q) {
                return '
                   <button onclick="editForm(`' . route('admission-types.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                    <i class="fa fa-pencil-alt"></i>
                </button>
                <button onclick="deleteData(`' . route('admission-types.destroy', $q->id) . '`,`' . $q->admission_type_name . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
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
            'admission_type_name' => 'required',
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

            $academicYear = AcademicYear::where('current_semester', 1)
                ->where('admission_semester', 1)
                ->first();

            if (!$academicYear) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun akademik tidak ditemukan.',
                ], 404);
            }

            $exists = AdmissionType::where('admission_type_name', $request->admission_type_name)->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jalur pendaftaran tersebut sudah ada.',
                ], 409); // Conflict
            }

            // Simpan data baru
            $query = AdmissionType::create([
                'academic_year_id' => $academicYear->id,
                'admission_type_name' => $request->admission_type_name,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $query
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
        $query = AdmissionType::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $query = AdmissionType::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admission_type_name' => 'required',
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

            $AdmissionType = AdmissionType::findOrFail($id);

            // Ambil tahun akademik aktif saat ini
            $academicYear = AcademicYear::where('current_semester', 1)
                ->where('admission_semester', 1)
                ->first();

            if (!$academicYear) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun akademik tidak ditemukan.',
                ], 404);
            }

            $exists = AdmissionType::where('admission_type_name', $request->admission_type_name)->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jalur pendaftaran tersebut sudah ada.',
                ], 409); // Conflict
            }

            // Update data
            $AdmissionType->update([
                'academic_year_id' => $academicYear->id,
                'admission_type_name' => $request->admission_type_name,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jalur pendaftaran berhasil diperbarui.',
                'data' => $AdmissionType
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $query = AdmissionType::findOrfail($id);

        $query->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
