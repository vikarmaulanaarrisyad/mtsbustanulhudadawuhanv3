<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionPhase;
use App\Models\StudentAdmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdmissionPhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYear = AcademicYear::with('semester')
            ->where('current_semester', 1)
            ->first();

        $studentAdmission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();

        $statusPendaftaran = ($studentAdmission && $studentAdmission->admission_status == 'open') ? 'Dibuka' : 'Ditutup';

        return view('admin.admission.admission-phases.index', compact('academicYear', 'studentAdmission', 'statusPendaftaran'));
    }

    public function data()
    {
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $query = AdmissionPhase::with('academicYear')->where('academic_year_id', $academicYear->id)->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('academic_year', function ($q) {
                return $q->academicYear->academic_year . ' ' . $q->academicYear->semester->semester_name;
            })
            ->editColumn('phase_start_date', function ($q) {
                return tanggal_indonesia($q->phase_start_date);
            })
            ->editColumn('phase_end_date', function ($q) {
                return tanggal_indonesia($q->phase_end_date);
            })
            ->addColumn('action', function ($q) {
                return '
            <button onclick="editForm(`' . route('admission-phases.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button onclick="deleteData(`' . route('admission-phases.destroy', $q->id) . '`,`' . $q->phase_name . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
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
        $academicYear = AcademicYear::with('semester')
            ->where('current_semester', 1)
            ->first();

        $studentAdmission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();

        $validator = Validator::make($request->all(), [
            'phase_name' => 'required',
            'phase_start_date' => 'required|date|after_or_equal:' . $studentAdmission->admission_start_date,
            'phase_end_date' => 'required|date_format:Y-m-d|after:phase_start_date',
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

            $exists = AdmissionPhase::where('academic_year_id', $academicYear->id)->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun pelajaran pendaftaran tersebut sudah ada.',
                ], 409); // Conflict
            }

            // Simpan data baru
            $query = AdmissionPhase::create([
                'academic_year_id' => $academicYear->id,
                'phase_name' => $request->phase_name,
                'phase_start_date' => $request->phase_start_date,
                'phase_end_date' => $request->phase_end_date,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data Gelombang berhasil disimpan.',
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
        $query = AdmissionPhase::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $academicYear = AcademicYear::with('semester')
            ->where('current_semester', 1)
            ->first();

        $studentAdmission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();

        $validator = Validator::make($request->all(), [
            'phase_name' => 'required',
            'phase_start_date' => 'required|date|after_or_equal:' . $studentAdmission->admission_start_date,
            'phase_end_date' => 'required|date_format:Y-m-d|after:phase_start_date',
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

            $admissionPhase = AdmissionPhase::find($id);

            if (!$admissionPhase) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Gelombang tidak ditemukan.',
                ], 404);
            }

            $admissionPhase->update([
                'phase_name' => $request->phase_name,
                'phase_start_date' => $request->phase_start_date,
                'phase_end_date' => $request->phase_end_date,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data Gelombang berhasil diperbarui.',
                'data' => $admissionPhase
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
        $query = AdmissionPhase::findOrfail($id);
        $query->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
