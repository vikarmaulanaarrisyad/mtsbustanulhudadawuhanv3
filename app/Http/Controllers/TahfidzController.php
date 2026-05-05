<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahfidzLog;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class TahfidzController extends Controller
{
    /**
     * Store a new tahfidz log (Admin/Teacher side).
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'surah_name' => 'required|string',
            'verse_range' => 'nullable|string',
            'juz' => 'nullable|integer',
            'type' => 'required|in:ziyadah,murojaah',
            'grade' => 'required|string',
            'tajwid_score' => 'required|integer|min:0|max:100',
        ]);

        TahfidzLog::create([
            'student_id' => $request->student_id,
            'teacher_id' => Auth::id(),
            'date' => $request->date,
            'surah_name' => $request->surah_name,
            'verse_range' => $request->verse_range,
            'juz' => $request->juz,
            'type' => $request->type,
            'grade' => $request->grade,
            'tajwid_score' => $request->tajwid_score,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Data Tahfidz berhasil disimpan!');
    }
}
