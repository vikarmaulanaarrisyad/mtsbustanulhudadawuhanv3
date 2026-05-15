<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurriculumTarget;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CurriculumTargetController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        return view('admin.curriculum-target.index', compact('subjects', 'academicYears'));
    }

    public function data(Request $request)
    {
        $query = CurriculumTarget::with(['subject', 'academicYear']);

        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('subject_name', fn($r) => $r->subject->name ?? '-')
            ->addColumn('academic_year_name', fn($r) => $r->academicYear->academic_year ?? '-')
            ->editColumn('semester', fn($r) => $r->semester == 1 ? 'Ganjil' : 'Genap')
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('admin.curriculum-targets.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('admin.curriculum-targets.destroy', $r->id) . '`, `' . $r->title . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:1,2',
            'chapter_number' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        CurriculumTarget::create($request->all());

        return response()->json(['message' => 'Target kurikulum berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(['data' => CurriculumTarget::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $target = CurriculumTarget::findOrFail($id);

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester' => 'required|in:1,2',
            'chapter_number' => 'nullable|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $target->update($request->all());

        return response()->json(['message' => 'Target kurikulum berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        CurriculumTarget::findOrFail($id)->delete();
        return response()->json(['message' => 'Target kurikulum berhasil dihapus']);
    }
}
