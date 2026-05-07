<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TeachingJournal;
use App\Models\ClassSchedule;
use App\Models\Teacher;
use App\Models\ClassGroup;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachingJournalController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if (!$teacher) return redirect()->back()->with('error', 'Profil tidak ditemukan.');

        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeekIso;

        // Schedules for today
        $schedules = ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
            ->where('teacher_id', $teacher->id)
            ->where('day', $dayOfWeek)
            ->orderBy('study_period_id')
            ->get();

        // Already filled journals today
        $filledJournals = TeachingJournal::where('teacher_id', $teacher->id)
            ->where('date', $today->toDateString())
            ->get()
            ->pluck('class_schedule_id')
            ->toArray();

        return view('guru.journal.index', compact('schedules', 'filledJournals', 'teacher'));
    }

    public function create(Request $request)
    {
        $schedule = ClassSchedule::with(['subject', 'classGroup', 'studyPeriod'])
            ->findOrFail($request->schedule_id);
        
        $teacher = Teacher::where('user_id', Auth::id())->first();
        
        // Students in this class
        $students = Student::where('student_class_group_id', $schedule->class_group_id)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        return view('guru.journal.create', compact('schedule', 'students', 'teacher'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_schedule_id' => 'required',
            'material_summary' => 'required',
            'date' => 'required|date'
        ]);

        $schedule = ClassSchedule::findOrFail($request->class_schedule_id);
        $teacher = Teacher::where('user_id', Auth::id())->first();

        TeachingJournal::create([
            'teacher_id' => $teacher->id,
            'class_schedule_id' => $schedule->id,
            'class_group_id' => $schedule->class_group_id,
            'subject_id' => $schedule->subject_id,
            'study_period_id' => $schedule->study_period_id,
            'date' => $request->date,
            'material_summary' => $request->material_summary,
            'student_notes' => $request->student_notes,
            'absent_students' => $request->absent_students ? implode(', ', $request->absent_students) : null,
        ]);

        return redirect()->route('guru.journal.index')->with('success', 'Jurnal berhasil disimpan.');
    }
}
