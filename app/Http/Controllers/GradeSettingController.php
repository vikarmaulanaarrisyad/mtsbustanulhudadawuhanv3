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

        GradeSetting::create($request->all());
        return response()->json(['message' => 'Konfigurasi mata pelajaran berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        GradeSetting::findOrFail($id)->delete();
        return response()->json(['message' => 'Konfigurasi mata pelajaran berhasil dihapus']);
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
}
