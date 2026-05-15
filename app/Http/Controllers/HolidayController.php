<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        return view('admin.attendance.holidays.index');
    }

    public function data(Request $request)
    {
        $query = Holiday::query();
        
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('holiday_date', $request->year);
        }

        $query->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('holidays.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('holidays.destroy', $r->id) . '`, `' . $r->name . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'name' => 'required|string|max:150',
        ]);

        Holiday::create($request->all());
        return response()->json(['message' => 'Hari libur berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(['data' => Holiday::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays,holiday_date,' . $id,
            'name' => 'required|string|max:150',
        ]);

        $holiday->update($request->all());
        return response()->json(['message' => 'Hari libur berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        Holiday::findOrFail($id)->delete();
        return response()->json(['message' => 'Hari libur berhasil dihapus']);
    }

    public function syncHolidays(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $service = new \App\Services\HolidayService();
        $result = $service->syncHolidays($year);

        if ($result['success']) {
            return response()->json(['message' => $result['message']]);
        }

        return response()->json(['message' => $result['message']], 500);
    }
}
