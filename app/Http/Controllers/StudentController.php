<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index2()
    {
        return view('maintenance');
    }

    public function index(Request $request)
    {
        $students = Student::with([
            'profile',
            'parents',
            'classGroup',
            'academicYear',
            'studentStatus'
        ])
            ->when(
                $request->academic_year,
                fn($q) =>
                $q->where('academic_year_id', $request->academic_year)
            )
            ->when(
                $request->class_group,
                fn($q) =>
                $q->where('student_class_group_id', $request->class_group)
            )
            ->latest()
            ->paginate(15);

        $totalStudents = Student::count();
        $activeStudents = Student::where('is_active', true)->count();
        $male = Student::where('jenis_kelamin', 'L')->count();
        $female = Student::where('jenis_kelamin', 'P')->count();

        return view('admin.academic.students.index', compact(
            'students',
            'totalStudents',
            'activeStudents',
            'male',
            'female'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
