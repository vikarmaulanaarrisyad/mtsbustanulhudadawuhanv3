<?php

namespace App\Http\Controllers;

use App\Models\Quotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blog.quotes.index');
    }

    public function data()
    {
        $query = Quotes::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('selectAll', function ($q) {
                return '
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input row-checkbox" name="selected[]" value="' . $q->id . '" data-id="' . $q->id . '">
                    </div>
                ';
            })
            ->addColumn('action', function ($q) {
                return '
            <button onclick="editForm(`' . route('quotes.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button onclick="deleteData(`' . route('quotes.destroy', $q->id) . '`,`' . $q->quote . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                <i class="fa fa-trash"></i>
            </button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'quote_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Simpan data baru
            $query = Quotes::create([
                'quote' => $request->quote,
                'quote_by' => $request->quote_by ?? 'Anonim',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $query
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $query = Quotes::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $query = Quotes::findOrfail($id);

        $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'quote_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Simpan data baru
            $query->update([
                'quote' => $request->quote,
                'quote_by' => $request->quote_by ?? 'Anonim',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbaharui.',
                'data' => $query
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $query = Quotes::findOrfail($id);

        $query->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    /**
     * Remove All resource from storage.
     */
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        try {
            // Setelah semua file dihapus, hapus data dari database
            Quotes::whereIn('id', $ids)->delete();

            return response()->json(['message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
