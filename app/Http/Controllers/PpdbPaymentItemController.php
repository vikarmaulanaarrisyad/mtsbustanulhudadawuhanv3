<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PpdbPaymentItem;
use App\Models\AcademicYear;

class PpdbPaymentItemController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $activeYear = AcademicYear::where('admission_semester', 1)->first();
        
        return view('admin.admission.ppdb.payment_items', compact('academicYears', 'activeYear'));
    }

    public function data(Request $request)
    {
        $query = PpdbPaymentItem::with('academicYear');
        
        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('amount', fn($r) => 'Rp ' . number_format($r->amount, 0, ',', '.'))
            ->addColumn('action', function ($r) {
                return '<div class="btn-group">
                    <button onclick="editData(' . $r->id . ')" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></button>
                    <button onclick="deleteData(' . $r->id . ')" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                </div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        PpdbPaymentItem::create($request->all());

        return response()->json(['message' => 'Item biaya berhasil ditambahkan.']);
    }

    public function show($id)
    {
        return response()->json(['data' => PpdbPaymentItem::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $item = PpdbPaymentItem::findOrFail($id);
        $item->update($request->all());

        return response()->json(['message' => 'Item biaya berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        PpdbPaymentItem::findOrFail($id)->delete();
        return response()->json(['message' => 'Item biaya berhasil dihapus.']);
    }
}
