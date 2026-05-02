<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeachersImport;
use App\Exports\TeachersTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new TeachersTemplateExport, 'template_guru.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new TeachersImport, $request->file('file'));
        return response()->json(['message' => 'Data guru berhasil diimport']);
    }

    public function index()
    {
        // Ambil user yang belum terhubung dengan guru manapun
        // dan kecualikan Super Admin jika diperlukan
        $users = User::whereDoesntHave('teacher')
            ->whereDoesntHave('roles', function($q) {
                $q->where('name', 'Super Admin');
            })
            ->orderBy('name')
            ->get();
            
        return view('admin.teachers.index', compact('users'));
    }

    public function data()
    {
        $query = Teacher::latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('teachers.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('teachers.destroy', $r->id) . '`, `' . $r->name . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'nip' => 'nullable|string|max:30',
            'position' => 'nullable|string|max:100',
            'rank' => 'nullable|string|max:100',
        ]);

        Teacher::create($request->all());
        return response()->json(['message' => 'Guru/Staf berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(['data' => Teacher::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:150',
            'nip' => 'nullable|string|max:30',
            'position' => 'nullable|string|max:100',
            'rank' => 'nullable|string|max:100',
        ]);

        $teacher->update($request->all());
        return response()->json(['message' => 'Guru/Staf berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        Teacher::findOrFail($id)->delete();
        return response()->json(['message' => 'Guru/Staf berhasil dihapus']);
    }
}
