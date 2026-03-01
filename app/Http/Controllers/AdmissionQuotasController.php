<?php

namespace App\Http\Controllers;

use App\Services\AdmissionQuotaService;
use Illuminate\Http\Request;

class AdmissionQuotasController extends Controller
{
    protected $service;

    public function __construct(AdmissionQuotaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $admissionTypes = $this->service->getAdmissionTypes();

        return view('admin.admission.admission-quotas.index', compact('admissionTypes'));
    }

    public function data()
    {
        $query = $this->service->getAll();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('academic_year', fn($q) => $q->academicYear->academic_year ?? '')
            ->editColumn('admission_types', fn($q) => $q->admissionTypes->admission_type_name ?? '')
            ->addColumn('action', function ($q) {
                return '
                    <button onclick="editForm(`' . route('admission-quotas.show', $q->id) . '`)"
                        class="btn btn-sm" style="background-color:#6755a5; color:#fff;">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('admission-quotas.destroy', $q->id) . '`,`' . $q->admissionTypes->admission_type_name . '`)"
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
        $validator = $this->service->validateStore($request->all());

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
                'message' => 'Data successfully saved.',
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
        return response()->json([
            'data' => $this->service->find($id)
        ]);
    }

    public function edit($id)
    {
        return response()->json([
            'data' => $this->service->find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->service->validateUpdate($request->all());

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $data = $this->service->update($id, $request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Data successfully updated.',
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
            'message' => 'Data successfully deleted.'
        ]);
    }
}
