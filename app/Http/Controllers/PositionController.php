<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function index()
    {
        $layout = auth()->user()->hasRole('Admin') ? 'layouts.admin' : 'layouts.guru';
        return view('admin.positions.index', compact('layout'));
    }

    public function data()
    {
        $query = Position::orderBy('sort_order', 'asc')->orderBy('name', 'asc');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('status', function ($r) {
                if ($r->is_active) {
                    return '<span class="badge badge-success px-3 py-2" style="border-radius: 50px;">Aktif</span>';
                }
                return '<span class="badge badge-danger px-3 py-2" style="border-radius: 50px;">Non-Aktif</span>';
            })
            ->addColumn('signer', function ($r) {
                if ($r->is_signer) {
                    return '<span class="badge badge-info px-3 py-2" style="border-radius: 50px;"><i class="fas fa-pen-fancy mr-1"></i> Penandatangan</span>';
                }
                return '<span class="text-muted small">-</span>';
            })
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('positions.show', $r->id) . '`)" class="btn btn-xs btn-primary" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('positions.destroy', $r->id) . '`, `' . $r->name . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['status', 'signer', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:positions,code',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Input tidak valid'], 422);
        }

        $position = Position::create($request->all());

        return response()->json([
            'message' => 'Jabatan berhasil ditambahkan',
            'data' => $position
        ]);
    }

    public function show(Position $position)
    {
        return response()->json(['data' => $position]);
    }

    public function update(Request $request, Position $position)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:positions,code,' . $position->id,
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Input tidak valid'], 422);
        }

        $position->update($request->all());

        return response()->json([
            'message' => 'Jabatan berhasil diperbaharui',
            'data' => $position
        ]);
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return response()->json(['message' => 'Jabatan berhasil dihapus']);
    }
}
