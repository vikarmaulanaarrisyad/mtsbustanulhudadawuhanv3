<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AchievementController extends Controller
{
    public function index()
    {
        $student = $this->getStudent();
        if (!$student) return redirect()->back();

        $achievements = Achievement::where('student_id', $student->id)
            ->with('academicYear')
            ->orderBy('date', 'desc')
            ->paginate(10);

        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();

        return view('siswa.achievements.index', compact('student', 'achievements', 'academicYears'));
    }

    public function store(Request $request)
    {
        $student = $this->getStudent();
        if (!$student) return response()->json(['message' => 'Siswa tidak ditemukan.'], 404);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'event_name' => 'required|string|max:255',
            'category' => 'required|in:Akademik,Non-Akademik',
            'level' => 'required|string',
            'rank' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'certificate_path' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'trophy_path' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $data['student_id'] = $student->id;
            $data['student_name'] = $student->nama_lengkap;
            $data['academic_year_id'] = $student->academic_year_id;
            $data['year'] = date('Y', strtotime($request->date));
            $data['status'] = 'pending'; // Mandiri upload default pending

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

            return response()->json(['success' => true, 'message' => 'Prestasi Anda telah diajukan dan menunggu persetujuan admin!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    private function getStudent()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student && $user->ppdbRegistrant) {
            $student = Student::where('nisn', $user->ppdbRegistrant->nisn)->first();
        }

        return $student;
    }
}
