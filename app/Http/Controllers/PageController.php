<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{

    public function data()
    {
        $query = Page::orderBy('id', 'DESC');

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
            <button onclick="editForm(`' . route('pages.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button onclick="deleteData(`' . route('pages.destroy', $q->id) . '`,`' . $q->title . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                <i class="fa fa-trash"></i>
            </button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blog.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'body' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Cek apakah kombinasi title sudah ada
            $exists = Page::where('title', $request->title)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tersebut sudah ada.',
                ], 409); // 409 Conflict
            }

            $slug = Str::slug($request->title);

            $data = [
                'title' => $request->title,
                'slug' => $slug,
                'body' => $request->body
            ];

            Page::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pendidikan berhasil ditambahkan.',
                'data' => $data
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
