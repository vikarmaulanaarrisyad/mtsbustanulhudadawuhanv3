<?php

namespace App\Http\Controllers;

use App\Models\AdmissionPhase;
use App\Services\AdmissionPhaseService;
use Illuminate\Http\Request;

class AdmissionPhaseController extends Controller
{
    protected $service;

    public function __construct(AdmissionPhaseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $academicYear = $this->service->getCurrentAcademicYear();
        $studentAdmission = $this->service->getStudentAdmission($academicYear->id);

        $statusPendaftaran = ($studentAdmission && $studentAdmission->admission_status == 'open')
            ? 'Dibuka'
            : 'Ditutup';

        return view('admin.admission.admission-phases.index', compact(
            'academicYear',
            'studentAdmission',
            'statusPendaftaran'
        ));
    }

    public function data()
    {
        $academicYear = $this->service->getAdmissionSemesterYear();

        $query = AdmissionPhase::with('academicYear')
            ->where('academic_year_id', $academicYear->id)
            ->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('academic_year', function ($q) {
                return $q->academicYear->academic_year . ' ' .
                    $q->academicYear->semester->semester_name;
            })
            ->editColumn('phase_start_date', fn($q) => tanggal_indonesia($q->phase_start_date))
            ->editColumn('phase_end_date', fn($q) => tanggal_indonesia($q->phase_end_date))
            ->addColumn('action', function ($q) {
                return '
                    <button onclick="editForm(`' . route('admission-phases.show', $q->id) . '`)"
                        class="btn btn-sm" style="background-color:#6755a5; color:#fff;">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('admission-phases.destroy', $q->id) . '`,`' . $q->phase_name . '`)"
                        class="btn btn-sm" style="background-color:#d81b60; color:#fff;">
                        <i class="fa fa-trash"></i>
                    </button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $academicYear = $this->service->getCurrentAcademicYear();
        $studentAdmission = $this->service->getStudentAdmission($academicYear->id);

        $validator = $this->service->validate($request->all(), $studentAdmission);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $data = $this->service->store($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Admission phase created successfully.',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $data = AdmissionPhase::findOrFail($id);
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $this->service->update($id, $request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Admission phase updated successfully.',
                'data'    => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $this->service->destroy($id);

        return response()->json([
            'status'  => true,
            'message' => 'Data deleted successfully.'
        ]);
    }
}
