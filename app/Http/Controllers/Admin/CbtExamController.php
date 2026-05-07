<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtBank;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CbtExamController extends Controller
{
    public function index()
    {
        return view('admin.cbt.exam.index');
    }

    public function data(Request $request)
    {
        $query = CbtExam::with(['bank', 'classes'])->withCount('studentExams');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                            <a href="'.route('admin.cbt.exam.monitor', $row->id).'" class="btn btn-sm btn-info" title="Live Monitoring"><i class="fas fa-tv"></i> Monitor</a>
                            <button onclick="editExam('.$row->id.')" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteExam('.$row->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->editColumn('classes', function($row) {
                return $row->classes->pluck('class_group')->implode(', ');
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cbt_bank_id' => 'required',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|numeric',
            'classes' => 'required|array',
        ]);

        $exam = CbtExam::create([
            'name' => $request->name,
            'cbt_bank_id' => $request->cbt_bank_id,
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'token' => strtoupper(Str::random(6)),
            'is_active' => $request->has('is_active')
        ]);

        $exam->classes()->sync($request->classes);

        return response()->json(['message' => 'Jadwal Ujian berhasil ditambahkan']);
    }

    public function edit(CbtExam $exam)
    {
        $exam->load('classes');
        return response()->json($exam);
    }

    public function update(Request $request, CbtExam $exam)
    {
        $request->validate([
            'name' => 'required',
            'cbt_bank_id' => 'required',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|numeric',
            'classes' => 'required|array',
        ]);

        $exam->update([
            'name' => $request->name,
            'cbt_bank_id' => $request->cbt_bank_id,
            'exam_date' => $request->exam_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'is_active' => $request->has('is_active')
        ]);

        $exam->classes()->sync($request->classes);

        return response()->json(['message' => 'Jadwal Ujian berhasil diperbarui']);
    }

    public function destroy(CbtExam $exam)
    {
        $exam->delete();
        return response()->json(['message' => 'Jadwal Ujian berhasil dihapus']);
    }

    public function refreshToken(CbtExam $exam)
    {
        $exam->update(['token' => strtoupper(Str::random(6))]);
        return response()->json(['message' => 'Token berhasil diperbarui', 'token' => $exam->token]);
    }

    public function monitor(CbtExam $exam)
    {
        $exam->load(['bank', 'classes', 'studentExams.student']);
        return view('admin.cbt.exam.monitor', compact('exam'));
    }
}
