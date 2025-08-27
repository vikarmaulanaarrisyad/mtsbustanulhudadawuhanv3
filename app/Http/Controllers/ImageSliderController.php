<?php

namespace App\Http\Controllers;

use App\Models\ImageSlider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImageSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blog.image-slider.index');
    }

    public function data()
    {
        $query = ImageSlider::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('image', function ($q) {
                $imageUrl = $q->image ? Storage::url($q->image) : asset('images/no-image.png');

                return '
                    <img src="' . $imageUrl . '" alt="' . e($q->caption) . '" style="max-height: 60px;" class="img-thumbnail">
                ';
            })

            ->addColumn('selectAll', function ($q) {
                return '
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input row-checkbox" name="selected[]" value="' . $q->id . '" data-id="' . $q->id . '">
                    </div>
                ';
            })
            ->editColumn('action', function ($q) {
                return '
                  <button onclick="deleteData(`' . route('image-sliders.destroy', $q->id) . '`,`' . $q->caption . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
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
        // Cek validasi awal
        $validator = Validator::make($request->all(), [
            'caption'        => 'required|string|max:255|unique:image_sliders,caption',
            'image'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Buat slug dari judul
        $slug = Str::slug($request->caption);

        // Cek apakah slug sudah digunakan
        if (ImageSlider::where('slug', $slug)->exists()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['caption' => ['Slug dari caption sudah digunakan, silakan gunakan caption lain.']],
                'message' => 'caption sudah digunakan.',
            ], 422);
        }

        $data = [
            'caption' => $request->caption,
            'slug' => $slug,
            'image' => $request->hasFile('image') ? upload('slider', $request->image, 'image') : null,
        ];

        ImageSlider::create($data);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ImageSlider $imageSlider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Ambil data berdasarkan ID
        $slider = ImageSlider::findOrFail($id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'caption' => [
                'required',
                'string',
                'max:255',
                Rule::unique('image_sliders', 'caption')->ignore($id),
            ],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Buat slug dari caption
        $slug = Str::slug($request->caption);

        // Cek apakah slug sudah digunakan oleh data lain
        if (ImageSlider::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'errors' => ['caption' => ['Slug dari caption sudah digunakan, silakan gunakan caption lain.']],
                'message' => 'Caption sudah digunakan.',
            ], 422);
        }
        // Hapus thumbnail lama jika ada thumbnail baru
        if ($request->hasFile('image') && $slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        // Update data
        $data = [
            'caption' => $request->caption,
            'slug' => $slug,
            'caption' => $request->caption,
            'image'     => $request->hasFile('image') ? upload('slider', $request->image, 'image', $slider->image) : $slider->image,
        ];

        $slider->update($data);

        return response()->json([
            'message' => 'Data berhasil diperbarui',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $query = ImageSlider::findOrfail($id);

        if (!empty($query->image)) {
            if (Storage::disk('public')->exists($query->image)) {
                Storage::disk('public')->delete($query->image);
            }
        }

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
            $imageSliders = ImageSlider::whereIn('id', $ids)->get();

            foreach ($imageSliders as $slider) {
                if (!empty($slider->image)) {
                    if (Storage::disk('public')->exists($slider->image)) {
                        Storage::disk('public')->delete($slider->image);
                    }
                }
            }

            // Setelah semua file dihapus, hapus data dari database
            ImageSlider::whereIn('id', $ids)->delete();

            return response()->json(['message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
