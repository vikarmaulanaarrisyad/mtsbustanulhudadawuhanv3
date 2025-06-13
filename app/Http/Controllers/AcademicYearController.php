<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semesters = Semester::all();
        return view('admin.academic.academic_year.index', compact('semesters'));
    }

    public function data()
    {
        $query = AcademicYear::with('semester')
            ->orderBy('id', 'desc')
            ->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('current_semester', function ($q) {
                $icon = $q->current_semester ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger';
                return '
                <button onclick="updateCurrentSemester(' . $q->id . ')" class="status-toggle btn-link" id="' . $q->id . '">
                    <i class="fas ' . $icon . ' fa-lg"></i>
                </button>
            ';
            })
            ->editColumn('admission_semester', function ($q) {
                $icon = $q->admission_semester ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger';
                return '
                <button onclick="updateAdmissionSemester(' . $q->id . ')" class="status-toggle btn-link" id="' . $q->id . '">
                    <i class="fas ' . $icon . ' fa-lg"></i>
                </button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string',
            'semester_id' => 'required',
            'current_semester' => 'nullable',
            'admission_semester' => 'nullable'
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

            // Cek apakah kombinasi academic_year dan semester_id sudah ada
            $exists = AcademicYear::where('academic_year', $request->academic_year)
                ->where('semester_id', $request->semester_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kombinasi tahun ajaran dan semester tersebut sudah ada.',
                ], 409); // 409 Conflict
            }

            // Simpan data tahun ajaran baru
            $academicYear = AcademicYear::create([
                'academic_year' => $request->academic_year,
                'semester_id' => $request->semester_id,
                'current_semester' => 0,
                'admission_semester' => 0,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tahun ajaran berhasil ditambahkan.',
                'data' => $academicYear
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function updateCurrentSemester($id)
    {
        DB::beginTransaction();

        try {
            $academicYear = AcademicYear::findOrFail($id);

            // Cek apakah semester ini sudah aktif
            if ($academicYear->current_semester == 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Semester ini sudah menjadi semester aktif.'
                ], 400);
            }

            // Nonaktifkan semua semester lain
            AcademicYear::where('current_semester', 1)->update(['current_semester' => 0, 'admission_semester' => 0]);

            // Aktifkan semester yang dipilih
            $academicYear->current_semester = 1;
            $academicYear->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Semester aktif berhasil diperbarui.',
                'new_status' => $academicYear->current_semester
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function updateAdmissionSemester($id)
    {
        DB::beginTransaction();

        try {
            // Temukan data tahun ajaran berdasarkan ID
            $academicYear = AcademicYear::findOrFail($id);

            // Cek apakah semester yang dipilih adalah ganjil (misal semester_id = 1)
            if ($academicYear->semester_id == 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya semester ganjil yang dapat diaktifkan sebagai semester PPDB.'
                ], 400);
            }

            if ($academicYear->current_semester == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya semester aktif yang dapat diaktifkan sebagai semester PPDB.'
                ], 400);
            }

            // Nonaktifkan semua semester lain
            AcademicYear::where('admission_semester', 1)->update(['admission_semester' => 0]);

            // Set semester saat ini sebagai aktif
            $academicYear->admission_semester = 1;
            $academicYear->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Semester PPDB saat ini telah berhasil diperbarui.',
                'new_status' => $academicYear->admission_semester
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
