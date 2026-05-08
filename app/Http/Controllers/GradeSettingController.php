<?php

namespace App\Http\Controllers;

use App\Models\GradeSetting;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeSettingController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        $setting = \App\Models\Setting::first();
        return view('admin.grades.settings.index', compact('subjects', 'setting'));
    }

    public function data(Request $request)
    {
        $query = GradeSetting::with('subject')->orderBy('level')->orderBy('type')->orderBy('order');
        
        if ($request->level) {
            $query->where('level', $request->level);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('subject_name', function($r) {
                return $r->subject->name ?? '-';
            })
            ->addColumn('type_badge', function($r) {
                $class = $r->type == 'raport' ? 'success' : 'primary';
                $text = $r->type == 'raport' ? 'Nilai Raport' : 'Ujian Madrasah';
                return '<span class="badge badge-'.$class.'">'.$text.'</span>';
            })
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="deleteData(`' . route('grade-settings.destroy', $r->id) . '`, `' . ($r->subject->name ?? '') . '`)" class="btn btn-xs btn-danger" title="Hapus">
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
            'level' => 'required|in:MI,MTs,MA',
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required|in:raport,ujian_madrasah',
            'order' => 'nullable|integer',
        ]);

        // Prevent duplicate
        $exists = GradeSetting::where([
            'level' => $request->level,
            'subject_id' => $request->subject_id,
            'type' => $request->type,
        ])->exists();

        if ($exists) {
            return response()->json(['message' => 'Mata pelajaran ini sudah ada untuk jenjang dan tipe tersebut'], 422);
        }

        $data = $request->all();
        if (!$request->order) {
            $lastOrder = GradeSetting::where('level', $request->level)
                ->where('type', $request->type)
                ->max('order');
            $data['order'] = ($lastOrder ?? 0) + 1;
        }

        GradeSetting::create($data);
        return response()->json(['message' => 'Konfigurasi mata pelajaran berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        $setting = GradeSetting::findOrFail($id);
        $level = $setting->level;
        $type = $setting->type;
        
        $setting->delete();

        // Reset nomor urut (reordering)
        $remaining = GradeSetting::where('level', $level)
            ->where('type', $type)
            ->orderBy('order')
            ->get();

        foreach ($remaining as $index => $item) {
            $item->update(['order' => $index + 1]);
        }

        return response()->json(['message' => 'Konfigurasi mata pelajaran berhasil dihapus dan nomor urut diperbarui']);
    }

    public function updateWeights(Request $request)
    {
        $request->validate([
            'weight_raport' => 'required|integer|min:0|max:100',
            'weight_exam' => 'required|integer|min:0|max:100',
        ]);

        if (($request->weight_raport + $request->weight_exam) != 100) {
            return response()->json(['message' => 'Total bobot harus 100%'], 422);
        }

        $setting = \App\Models\Setting::first();
        if ($setting) {
            $setting->update([
                'weight_raport' => $request->weight_raport,
                'weight_exam' => $request->weight_exam,
            ]);
        }

        return response()->json(['message' => 'Bobot penilaian berhasil diperbarui']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return response()->json(['message' => 'Tidak ada data yang dipilih'], 422);
        }

        $settings = GradeSetting::whereIn('id', $ids)->get();
        $affectedLevelsTypes = [];

        foreach ($settings as $setting) {
            $key = $setting->level . '|' . $setting->type;
            if (!in_array($key, $affectedLevelsTypes)) {
                $affectedLevelsTypes[] = $key;
            }
            $setting->delete();
        }

        // Reorder for all affected levels and types
        foreach ($affectedLevelsTypes as $key) {
            list($level, $type) = explode('|', $key);
            $remaining = GradeSetting::where('level', $level)
                ->where('type', $type)
                ->orderBy('order')
                ->get();

            foreach ($remaining as $index => $item) {
                $item->update(['order' => $index + 1]);
            }
        }

        return response()->json(['message' => 'Data berhasil dihapus dan nomor urut diperbarui']);
    }

    public function resetOrder(Request $request)
    {
        $level = $request->level;
        $type = $request->type;

        if (!$level || !$type) {
            return response()->json(['message' => 'Pilih Jenjang dan Tipe terlebih dahulu untuk mereset urutan'], 422);
        }

        $items = GradeSetting::where('level', $level)
            ->where('type', $type)
            ->orderBy('order')
            ->get();

        foreach ($items as $index => $item) {
            $item->update(['order' => $index + 1]);
        }

        return response()->json(['message' => "Nomor urut untuk $level ($type) berhasil di-reset"]);
    }
}
