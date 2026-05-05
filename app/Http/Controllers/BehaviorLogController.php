<?php

namespace App\Http\Controllers;

use App\Models\BehaviorLog;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BehaviorLogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'type' => 'required|in:positive,negative',
            'points' => 'required|integer|min:1',
            'category' => 'nullable|string|max:100',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);

        $teacher = Teacher::where('user_id', Auth::id())->first();

        BehaviorLog::create([
            'student_id' => $request->student_id,
            'teacher_id' => $teacher ? $teacher->id : null,
            'type' => $request->type,
            'points' => $request->points,
            'category' => $request->category,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Poin karakter berhasil dicatat.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $log = BehaviorLog::findOrFail($id);
        $log->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Catatan poin berhasil dihapus.'
        ]);
    }
}
