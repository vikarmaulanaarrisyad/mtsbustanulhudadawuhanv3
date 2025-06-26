<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionQuotas;
use App\Models\AdmissionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmissionQuotasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admissionTypes = AdmissionType::all();

        return view('admin.admission.admission-quotas.index', compact('admissionTypes'));
    }

    public function data()
    {
        $query = AdmissionQuotas::all();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('academic_year', function ($q) {
                return $q->academicYear->academic_year ?? '';
            })
            ->editColumn('admission_types', function ($q) {
                return $q->admissionTypes->admission_type_name ?? '';
            })
            ->addColumn('action', function ($q) {
                return '
                   <button onclick="editForm(`' . route('admission-quotas.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                    <i class="fa fa-pencil-alt"></i>
                </button>
                <button onclick="deleteData(`' . route('admission-quotas.destroy', $q->id) . '`,`' . $q->admissionTypes->admission_type_name . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
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
            'admission_types_id' => 'required',
            'quota' => 'required',
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

            $exists = AdmissionQuotas::where('admission_types_id', $request->admission_types_id)->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kuota pendaftaran tersebut sudah ada.',
                ], 409); // Conflict
            }

            // Simpan data baru
            $query = AdmissionQuotas::create([
                'academic_year_id' => $academicYear->id,
                'admission_types_id' => $request->admission_types_id,
                'quota' => $request->quota,
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
        $query = AdmissionQuotas::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $query = AdmissionQuotas::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admission_types_id' => 'required|exists:admission_types,id',
            'quota' => 'required|integer|min:0',
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

            $AdmissionQuotas = AdmissionQuotas::findOrFail($id);

            $academicYear = AcademicYear::where('current_semester', 1)
                ->where('admission_semester', 1)
                ->first();

            if (!$academicYear) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun akademik tidak ditemukan.',
                ], 404);
            }

            $exists = AdmissionQuotas::where('admission_types_id', $request->admission_types_id)
                ->where('id', '!=', $AdmissionQuotas->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kuota pendaftaran tersebut sudah ada.',
                ], 409);
            }

            $AdmissionQuotas->update([
                'admission_types_id' => $request->admission_types_id,
                'quota' => $request->quota,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kuota pendaftaran berhasil diperbarui.',
                'data' => $AdmissionQuotas
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
        $query = AdmissionQuotas::findOrfail($id);

        $query->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
