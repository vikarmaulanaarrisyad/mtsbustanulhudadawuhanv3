<?php

namespace App\Http\Controllers;

use App\Models\SchoolAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchoolAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.academic.agenda.index');
    }

    public function data()
    {
        $query = SchoolAgenda::orderBy('id', 'desc')->get();


        return datatables($query)
            ->addIndexColumn()
            ->editColumn('status', function ($q) {

                switch ($q->status) {
                    case 'active':
                        $class = 'badge-success';
                        $label = 'Active';
                        break;

                    case 'completed':
                        $class = 'badge-secondary';
                        $label = 'Completed';
                        break;

                    default:
                        $class = 'badge-warning';
                        $label = 'Draft';
                        $label = 'Draft';
                        break;
                }

                return '<span class="badge ' . $class . '">' . $label . '</span>';
            })

            ->editColumn('category', function ($q) {
                return ucfirst(str_replace('_', ' ', $q->category));
            })
            ->editColumn('start_date', function ($q) {
                return tanggal_indonesia($q->start_date);
            })
            ->editColumn('end_date', function ($q) {
                return tanggal_indonesia($q->end_date);
            })
            ->addColumn('action', function ($q) {
                return '
            <button onclick="editForm(`' . route('agenda.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button onclick="deleteData(`' . route('agenda.destroy', $q->id) . '`,`' . $q->title . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                <i class="fa fa-trash"></i>
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'required|in:academic,non_academic,holiday',
            'status' => 'required|in:draft,active,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        $schoolAgenda = SchoolAgenda::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'responsible_person' => $request->responsible_person,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'School agenda created successfully.',
            'data' => $schoolAgenda
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $query = SchoolAgenda::find($id);

        return response()->json([
            'status' => true,
            'data' => $query
        ]); 
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'required|in:academic,non_academic,holiday',
            'status' => 'required|in:draft,active,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors()
            ], 422);
        }

        $schoolAgenda = SchoolAgenda::find($id);
        if (!$schoolAgenda) {
            return response()->json([
                'status' => false,
                'message' => 'School agenda not found.'
            ], 404);
        }

        $schoolAgenda->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'responsible_person' => $request->responsible_person,
            'category' => $request->category,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'School agenda updated successfully.',
            'data' => $schoolAgenda
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schoolAgenda = SchoolAgenda::find($id);
        if (!$schoolAgenda) {
            return response()->json([
                'status' => false,
                'message' => 'School agenda not found.'
            ], 404);
        }

        $schoolAgenda->delete();

        return response()->json([
            'status' => true,
            'message' => 'School agenda deleted successfully.'
        ]);
    }
}
