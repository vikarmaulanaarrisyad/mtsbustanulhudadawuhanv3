<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\StudentAdmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil tahun ajaran yang sedang aktif untuk semester penerimaan
        $academicYear = AcademicYear::where('admission_semester', 1)->first();

        // Hitung jumlah data pendaftaran siswa untuk tahun ajaran tersebut
        $studentAdmissions = $academicYear
            ? StudentAdmission::where('academic_year_id', $academicYear->id)->count()
            : 0;

        return view('admin.admission.student-admissions.index', [
            'studentAdmissions' => $studentAdmissions,
            'academicYear' => $academicYear
        ]);
    }

    public function data()
    {
        $academicYear = AcademicYear::where('admission_semester', 1)->first();

        if (!$academicYear) {
            return datatables(collect([]))
                ->addIndexColumn()
                ->make(true);
        }

        $query = StudentAdmission::with('academicYear')
            ->where('academic_year_id', $academicYear->id)
            ->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('admission_start_date', function ($q) {
                return tanggal_indonesia($q->admission_start_date);
            })
            ->editColumn('admission_end_date', function ($q) {
                return tanggal_indonesia($q->admission_end_date);
            })
            ->editColumn('announcement_start_date', function ($q) {
                return tanggal_indonesia($q->announcement_start_date);
            })
            ->editColumn('announcement_end_date', function ($q) {
                return tanggal_indonesia($q->announcement_end_date);
            })
            ->addColumn('action', function ($q) {
                return '
                   <button onclick="editForm(`' . route('student-admissions.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                    <i class="fa fa-pencil-alt"></i>
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
            'admission_status' => 'required|in:open,close',
            'admission_year' => 'required|integer|min:2000|max:2100',
            'admission_start_date' => 'required|date',
            'admission_end_date' => 'required|date|after_or_equal:admission_start_date',
            'announcement_start_date' => 'required|date|after_or_equal:admission_end_date',
            'announcement_end_date' => 'required|date|after_or_equal:announcement_start_date',
            'ba_letter_number' => 'nullable|string|max:100',
            'sk_letter_number' => 'nullable|string|max:100',
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

            // Konversi tanggal secara fleksibel menggunakan parse()
            $data = $request->all();
            $data['admission_start_date'] = Carbon::parse($request->admission_start_date)->format('Y-m-d');
            $data['admission_end_date'] = Carbon::parse($request->admission_end_date)->format('Y-m-d');
            $data['announcement_start_date'] = Carbon::parse($request->announcement_start_date)->format('Y-m-d');
            $data['announcement_end_date'] = Carbon::parse($request->announcement_end_date)->format('Y-m-d');

            $academicYear = AcademicYear::where('current_semester', 1)
                ->where('admission_semester', 1)
                ->first();

            if (!$academicYear) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun akademik tidak ditemukan.',
                ], 404);
            }

            $exists = StudentAdmission::where('academic_year_id', $academicYear->id)->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun pelajaran pendaftaran tersebut sudah ada.',
                ], 409); // Conflict
            }

            // Simpan data baru
            $query = StudentAdmission::create([
                'academic_year_id' => $academicYear->id,
                'admission_status' => $data['admission_status'],
                'admission_year' => $data['admission_year'],
                'admission_start_date' => $data['admission_start_date'],
                'admission_end_date' => $data['admission_end_date'],
                'announcement_start_date' => $data['announcement_start_date'],
                'announcement_end_date' => $data['announcement_end_date'],
                'ba_letter_number' => $data['ba_letter_number'],
                'sk_letter_number' => $data['sk_letter_number'],
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data pendaftaran berhasil disimpan.',
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
        $query = StudentAdmission::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admission_status' => 'required|in:open,close',
            'admission_year' => 'required|integer|min:2000|max:2100',
            'admission_start_date' => 'required|date',
            'admission_end_date' => 'required|date|after_or_equal:admission_start_date',
            'announcement_start_date' => 'required|date|after_or_equal:admission_end_date',
            'announcement_end_date' => 'required|date|after_or_equal:announcement_start_date',
            'ba_letter_number' => 'nullable|string|max:100',
            'sk_letter_number' => 'nullable|string|max:100',
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

            // Konversi tanggal secara fleksibel menggunakan parse()
            $data = $request->all();
            $data['admission_start_date'] = Carbon::parse($request->admission_start_date)->format('Y-m-d');
            $data['admission_end_date'] = Carbon::parse($request->admission_end_date)->format('Y-m-d');
            $data['announcement_start_date'] = Carbon::parse($request->announcement_start_date)->format('Y-m-d');
            $data['announcement_end_date'] = Carbon::parse($request->announcement_end_date)->format('Y-m-d');

            $studentAdmission = StudentAdmission::findOrFail($id);

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

            // Cek apakah tahun akademik sudah digunakan oleh entri lain
            $exists = StudentAdmission::where('academic_year_id', $academicYear->id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun pelajaran pendaftaran tersebut sudah digunakan oleh entri lain.',
                ], 409); // Conflict
            }

            // Update data
            $studentAdmission->update([
                'academic_year_id' => $academicYear->id,
                'admission_status' => $data['admission_status'],
                'admission_year' => $data['admission_year'],
                'admission_start_date' => $data['admission_start_date'],
                'admission_end_date' => $data['admission_end_date'],
                'announcement_start_date' => $data['announcement_start_date'],
                'announcement_end_date' => $data['announcement_end_date'],
                'ba_letter_number' => $data['ba_letter_number'],
                'sk_letter_number' => $data['sk_letter_number'],
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data pendaftaran berhasil diperbarui.',
                'data' => $studentAdmission
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memperbarui: ' . $e->getMessage()
            ], 500);
        }
    }
}
