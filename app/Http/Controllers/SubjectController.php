<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Imports\SubjectsImport;
use App\Exports\SubjectsTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new SubjectsTemplateExport, 'template_mapel.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new SubjectsImport, $request->file('file'));
        return response()->json(['message' => 'Data mata pelajaran berhasil diimport']);
    }

    public function index()
    {
        return view('admin.academic.subjects.index');
    }

    public function data()
    {
        $query = Subject::latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('category_badge', function($r) {
                return '<span class="badge badge-secondary">' . ($r->category ?? 'Lainnya') . '</span>';
            })
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('subjects.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('subjects.destroy', $r->id) . '`, `' . $r->name . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:20|unique:subjects,code',
            'category' => 'nullable|string',
        ]);

        Subject::create($request->all());
        return response()->json(['message' => 'Mata pelajaran berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(['data' => Subject::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'nullable|string|max:20|unique:subjects,code,' . $id,
            'category' => 'nullable|string',
        ]);

        $subject->update($request->all());
        return response()->json(['message' => 'Mata pelajaran berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();
        return response()->json(['message' => 'Mata pelajaran berhasil dihapus']);
    }
}
