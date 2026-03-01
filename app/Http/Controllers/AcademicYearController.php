<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Semester;
use App\Services\AcademicYearService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AcademicYearController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX VIEW
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $semesters = Semester::all();

        return view('admin.academic.academic_year.index', compact('semesters'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATATABLES DATA
    |--------------------------------------------------------------------------
    */
    public function data()
    {
        $query = AcademicYear::with('semester')
            ->orderByDesc('id')
            ->get();

        return datatables($query)
            ->addIndexColumn()

            ->editColumn('current_semester', function ($q) {
                $icon = $q->current_semester
                    ? 'fa-toggle-on text-success'
                    : 'fa-toggle-off text-danger';

                return '
                    <button onclick="updateCurrentSemester(' . $q->id . ')"
                        class="btn btn-link p-0">
                        <i class="fas ' . $icon . ' fa-lg"></i>
                    </button>
                ';
            })

            ->editColumn('admission_semester', function ($q) {
                $icon = $q->admission_semester
                    ? 'fa-toggle-on text-success'
                    : 'fa-toggle-off text-danger';

                return '
                    <button onclick="updateAdmissionSemester(' . $q->id . ')"
                        class="btn btn-link p-0">
                        <i class="fas ' . $icon . ' fa-lg"></i>
                    </button>
                ';
            })

            ->escapeColumns([])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, AcademicYearService $service)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'semester_id'   => 'required|exists:semesters,id',
        ]);

        try {

            $academicYear = $service->create($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Tahun ajaran berhasil ditambahkan.',
                'data'    => $academicYear
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE CURRENT SEMESTER
    |--------------------------------------------------------------------------
    */
    public function updateCurrentSemester($id, AcademicYearService $service)
    {
        try {

            $academicYear = $service->setCurrentSemester($id);

            return response()->json([
                'status'     => true,
                'message'    => 'Semester aktif berhasil diperbarui.',
                'new_status' => $academicYear->current_semester
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ADMISSION SEMESTER (PPDB)
    |--------------------------------------------------------------------------
    */
    public function updateAdmissionSemester($id, AcademicYearService $service)
    {
        try {

            $academicYear = $service->setAdmissionSemester($id);

            return response()->json([
                'status'     => true,
                'message'    => 'Semester PPDB berhasil diperbarui.',
                'new_status' => $academicYear->admission_semester
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {

            $academicYear = AcademicYear::findOrFail($id);

            // Optional: prevent delete if active
            if ($academicYear->current_semester) {
                return response()->json([
                    'status' => false,
                    'message' => 'Semester aktif tidak dapat dihapus.'
                ], 400);
            }

            $academicYear->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Tahun ajaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
