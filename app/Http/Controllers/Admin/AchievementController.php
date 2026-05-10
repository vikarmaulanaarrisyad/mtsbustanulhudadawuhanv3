<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
    public function index()
    {
        $students = Student::active()->orderBy('nama_lengkap')->get();
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        return view('admin.academic.achievements.index', compact('students', 'academicYears'));
    }

    public function data(Request $request)
    {
        $query = Achievement::with(['student.classGroup', 'academicYear'])->latest();

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_info', function($row) {
                if ($row->student) {
                    return $row->student->nama_lengkap . '<br><small class="text-muted">' . ($row->student->kelas_lengkap ?? '-') . '</small>';
                }
                return $row->student_name ?? '-';
            })
            ->addColumn('achievement_info', function($row) {
                return '<strong>' . $row->title . '</strong><br><small>' . $row->event_name . ' (' . $row->level . ')</small>';
            })
            ->editColumn('status', function($row) {
                $colors = [
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger'
                ];
                $color = $colors[$row->status] ?? 'secondary';
                return '<span class="badge badge-' . $color . '">' . strtoupper($row->status) . '</span>';
            })
            ->addColumn('action', function($row) {
                $btn = '<div class="btn-group">';
                $btn .= '<button onclick="editData(' . $row->id . ')" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></button>';
                $btn .= '<button onclick="deleteData(' . $row->id . ')" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                
                if ($row->status == 'pending') {
                    $btn .= '<button onclick="updateStatus(' . $row->id . ', \'approved\')" class="btn btn-sm btn-success" title="Setujui"><i class="fas fa-check"></i></button>';
                    $btn .= '<button onclick="updateStatus(' . $row->id . ', \'rejected\')" class="btn btn-sm btn-warning" title="Tolak"><i class="fas fa-times"></i></button>';
                }
                
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['student_info', 'achievement_info', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'event_name' => 'required|string|max:255',
            'category' => 'required|string',
            'level' => 'required|string',
            'rank' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'certificate_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'trophy_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $student = Student::findOrFail($request->student_id);
        $data['student_name'] = $student->nama_lengkap;
        $data['year'] = date('Y', strtotime($request->date));

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('achievements/photos', 'public');
        }
        if ($request->hasFile('certificate_path')) {
            $data['certificate_path'] = $request->file('certificate_path')->store('achievements/certificates', 'public');
        }
        if ($request->hasFile('trophy_path')) {
            $data['trophy_path'] = $request->file('trophy_path')->store('achievements/trophies', 'public');
        }

        Achievement::create($data);

        return response()->json(['message' => 'Data prestasi berhasil disimpan.']);
    }

    public function show($id)
    {
        $achievement = Achievement::with(['student', 'academicYear'])->findOrFail($id);
        return response()->json($achievement);
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'event_name' => 'required|string|max:255',
            'category' => 'required|string',
            'level' => 'required|string',
            'rank' => 'required|string',
            'date' => 'required|date',
        ]);

        $data = $request->all();
        $student = Student::findOrFail($request->student_id);
        $data['student_name'] = $student->nama_lengkap;
        $data['year'] = date('Y', strtotime($request->date));

        if ($request->hasFile('image')) {
            if ($achievement->image) Storage::disk('public')->delete($achievement->image);
            $data['image'] = $request->file('image')->store('achievements/photos', 'public');
        }
        if ($request->hasFile('certificate_path')) {
            if ($achievement->certificate_path) Storage::disk('public')->delete($achievement->certificate_path);
            $data['certificate_path'] = $request->file('certificate_path')->store('achievements/certificates', 'public');
        }
        if ($request->hasFile('trophy_path')) {
            if ($achievement->trophy_path) Storage::disk('public')->delete($achievement->trophy_path);
            $data['trophy_path'] = $request->file('trophy_path')->store('achievements/trophies', 'public');
        }

        $achievement->update($data);

        return response()->json(['message' => 'Data prestasi berhasil diperbarui.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->update(['status' => $request->status]);
        return response()->json(['message' => 'Status prestasi berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        if ($achievement->image) Storage::disk('public')->delete($achievement->image);
        if ($achievement->certificate_path) Storage::disk('public')->delete($achievement->certificate_path);
        if ($achievement->trophy_path) Storage::disk('public')->delete($achievement->trophy_path);
        
        $achievement->delete();
        return response()->json(['message' => 'Data prestasi berhasil dihapus.']);
    }

    public function print(Request $request)
    {
        $query = Achievement::with(['student.classGroup', 'academicYear'])->latest();

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $achievements = $query->get();
        $setting = Setting::first();

        $pdf = Pdf::loadView('admin.academic.achievements.pdf', compact('achievements', 'setting', 'request'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Rekapitulasi_Prestasi_Siswa.pdf');
    }
}
