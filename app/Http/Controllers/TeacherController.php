<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Position;
use App\Imports\TeachersImport;
use App\Exports\TeachersTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new TeachersTemplateExport, 'template_guru.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new TeachersImport, $request->file('file'));
            return response()->json(['message' => 'Data guru berhasil diimport']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Terdapat kesalahan pada file Excel:<br><br>';
            foreach ($failures as $failure) {
                $errorMsg .= "<b>Baris " . $failure->row() . "</b> (" . $failure->attribute() . "): " . $failure->errors()[0] . "<br>";
            }
            return response()->json(['message' => $errorMsg], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengimport data: ' . $e->getMessage()], 500);
        }
    }

    public function index()
    {
        // Ambil user yang belum terhubung dengan guru manapun
        // dan kecualikan Super Admin jika diperlukan
        $users = User::whereDoesntHave('teacher')
            ->whereDoesntHave('roles', function($q) {
                $q->where('name', 'Super Admin');
            })
            ->orderBy('name')
            ->get();
            
        $stats = [
            'total' => Teacher::count(),
            'laki' => Teacher::where('gender', 'L')->count(),
            'perempuan' => Teacher::where('gender', 'P')->count(),
            'kepala_madrasah' => get_kepala_madrasah(),
            'tu' => Teacher::where('position', 'LIKE', '%TU%')
                           ->orWhere('position', 'LIKE', '%Tata Usaha%')
                           ->orWhere('position', 'LIKE', '%Kepala Urusan%')
                           ->count(),
        ];

        $positions = Position::where('is_active', true)->orderBy('sort_order')->get();
        $educatorPositions = $positions->where('category', 'pendidik');
        $structuralPositions = $positions->where('category', 'struktural');
            
        return view('admin.teachers.index', compact('users', 'stats', 'positions', 'educatorPositions', 'structuralPositions'));
    }

    public function data()
    {
        $query = Teacher::latest();
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('teachers.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('teachers.destroy', $r->id) . '`, `' . $r->name . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:150',
            'nip' => 'nullable|max:30',
            'position' => 'nullable|string|max:100',
            'additional_duty' => 'nullable|string|max:100',
            'rank' => 'nullable|string|max:100',
            'nik' => 'nullable|max:16',
            'nuptk' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'employment_status' => 'nullable|string|max:50',
            'education' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:150',
            'start_date' => 'nullable|date',
            'certification_status' => 'nullable|boolean',
            'bank_name' => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:100',
            'base_salary' => 'nullable|numeric',
        ];

        $request->validate($rules);

        // Validasi Tugas Tambahan Unik (Hanya 1 orang per jabatan penting)
        $uniqueDuties = ['Kepala Madrasah', 'Bendahara Madrasah', 'Bendahara BOS', 'Kepala Tata Usaha', 'Waka Kurikulum', 'Waka Kesiswaan', 'Waka Sarana Prasarana', 'Waka Humas'];
        if ($request->additional_duty && in_array($request->additional_duty, $uniqueDuties)) {
            $exists = Teacher::where('additional_duty', $request->additional_duty)->exists();
            if ($exists) {
                $holder = Teacher::where('additional_duty', $request->additional_duty)->first();
                return response()->json(['message' => "Jabatan <b>{$request->additional_duty}</b> sudah diisi oleh <b>{$holder->name}</b>. Silakan kosongkan jabatan guru tersebut terlebih dahulu."], 422);
            }
        }

        Teacher::create($request->all());
        return response()->json(['message' => 'Guru/Staf berhasil ditambahkan']);
    }

    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        $data = $teacher->toArray();

        // Format tanggal agar sesuai dengan input type="date" (Y-m-d)
        foreach (['date_of_birth', 'start_date'] as $dateField) {
            if (!empty($data[$dateField])) {
                $data[$dateField] = \Carbon\Carbon::parse($data[$dateField])->format('Y-m-d');
            }
        }

        // Hilangkan .00 dari nominal gaji
        if (!empty($data['base_salary'])) {
            $data['base_salary'] = (int) $data['base_salary'];
        }

        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $rules = [
            'name' => 'required|string|max:150',
            'nip' => 'nullable|max:30',
            'position' => 'nullable|string|max:100',
            'additional_duty' => 'nullable|string|max:100',
            'rank' => 'nullable|string|max:100',
            'nik' => 'nullable|max:16',
            'nuptk' => 'nullable|string|max:20',
            'gender' => 'nullable|in:L,P',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'employment_status' => 'nullable|string|max:50',
            'education' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:100',
            'university' => 'nullable|string|max:150',
            'start_date' => 'nullable|date',
            'certification_status' => 'nullable|boolean',
            'bank_name' => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:100',
            'base_salary' => 'nullable|numeric',
        ];
        $request->validate($rules);

        // Validasi Tugas Tambahan Unik (Hanya 1 orang per jabatan penting)
        $uniqueDuties = ['Kepala Madrasah', 'Bendahara Madrasah', 'Bendahara BOS', 'Kepala Tata Usaha', 'Waka Kurikulum', 'Waka Kesiswaan', 'Waka Sarana Prasarana', 'Waka Humas'];
        if ($request->additional_duty && in_array($request->additional_duty, $uniqueDuties)) {
            $exists = Teacher::where('additional_duty', $request->additional_duty)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                $holder = Teacher::where('additional_duty', $request->additional_duty)->first();
                return response()->json(['message' => "Jabatan <b>{$request->additional_duty}</b> sudah diisi oleh <b>{$holder->name}</b>. Silakan kosongkan jabatan guru tersebut terlebih dahulu."], 422);
            }
        }

        $teacher->update($request->all());
        return response()->json(['message' => 'Guru/Staf berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        Teacher::findOrFail($id)->delete();
        return response()->json(['message' => 'Guru/Staf berhasil dihapus']);
    }
}
