<?php

namespace App\Http\Controllers;

use App\Imports\TransportationImport;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TransportationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.academic.transportations.index');
    }

    public function data()
    {
        $query = Transportation::all();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($q) {
                return '
            <button onclick="editForm(`' . route('transportations.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button onclick="deleteData(`' . route('transportations.destroy', $q->id) . '`,`' . $q->transportation_name . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
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
            'transportation_name' => 'required',
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

            // Cek apakah kombinasi transportation_name sudah ada
            $exists = Transportation::where('transportation_name', $request->transportation_name)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transportasi tersebut sudah ada.',
                ], 409); // 409 Conflict
            }

            // Simpan data baru
            $query = Transportation::create([
                'transportation_name' => $request->transportation_name,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transportasi berhasil ditambahkan.',
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
        $query = Transportation::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $query = Transportation::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transportation_name' => 'required',
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

            $query = Transportation::findOrfail($id);

            // Cek apakah kombinasi transportation_name sudah ada
            $exists = Transportation::where('transportation_name', $request->transportation_name)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transportasi tersebut sudah ada.',
                ], 409); // 409 Conflict
            }

            $data = [
                'transportation_name' => $request->transportation_name,
            ];

            $query->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kelas berhasil diperbaharui.',
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
        $query = Transportation::findOrfail($id);
        $query->delete();

        return response()->json(['message' => 'Tranportasi berhasil dihapus.']);
    }

    public function importEXCEL(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls|max:2048', // Maks 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Proses import menggunakan Laravel Excel
            Excel::import(new TransportationImport, $request->file('excelFile'), null, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diupload dan diproses!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
