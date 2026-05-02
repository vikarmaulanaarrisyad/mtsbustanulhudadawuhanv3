<?php

namespace App\Http\Controllers;

use App\Models\StudyPeriod;
use Illuminate\Http\Request;

class StudyPeriodController extends Controller
{
    public function index()
    {
        return view('admin.academic.study_periods.index');
    }

    public function data()
    {
        $query = StudyPeriod::orderBy('period_number');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('is_break_label', fn($r) => $r->is_break ? '<span class="badge badge-warning">Istirahat</span>' : '<span class="badge badge-success">Jam Pelajaran</span>')
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('study-periods.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('study-periods.destroy', $r->id) . '`, `Jam ke-' . $r->period_number . '`)" class="btn btn-xs btn-danger" title="Hapus">
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
            'period_number' => 'required|integer',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        StudyPeriod::create($request->all());
        return response()->json(['message' => 'Jam pelajaran berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(['data' => StudyPeriod::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $period = StudyPeriod::findOrFail($id);
        $request->validate([
            'period_number' => 'required|integer',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $period->update($request->all());
        return response()->json(['message' => 'Jam pelajaran berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        StudyPeriod::findOrFail($id)->delete();
        return response()->json(['message' => 'Jam pelajaran berhasil dihapus']);
    }
}
