<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassGroup;
use App\Models\AcademicYear;
use App\Models\StudyPeriod;
use App\Imports\ClassSchedulesImport;
use App\Exports\ClassSchedulesTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    public function downloadTemplate()
    {
        return Excel::download(new ClassSchedulesTemplateExport, 'template_jadwal.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new ClassSchedulesImport, $request->file('file'));
        return response()->json(['message' => 'Data jadwal pelajaran berhasil diimport']);
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $classGroups = ClassGroup::orderBy('class_group')->orderBy('sub_class_group')->get();
        $academicYears = AcademicYear::orderBy('academic_year', 'desc')->get();
        $studyPeriods = StudyPeriod::orderBy('period_number')->get();
        
        return view('admin.academic.schedules.index', compact('subjects', 'teachers', 'classGroups', 'academicYears', 'studyPeriods'));
    }

    public function matrix(Request $request)
    {
        $request->validate([
            'class_group_id' => 'required|exists:class_groups,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $schedules = ClassSchedule::with(['subject', 'teacher', 'studyPeriod'])
            ->where('class_group_id', $request->class_group_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->get()
            ->groupBy(['day', 'study_period_id']);

        return response()->json($schedules);
    }

    public function data(Request $request)
    {
        $query = ClassSchedule::with(['subject', 'teacher', 'classGroup', 'academicYear', 'studyPeriod'])
            ->when($request->class_group_id, fn($q) => $q->where('class_group_id', $request->class_group_id))
            ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->orderBy('day')->orderBy('start_time');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('day_name', fn($r) => $r->day_name)
            ->addColumn('time', function($r) {
                if ($r->study_period_id) {
                    return 'Jam ke-' . $r->studyPeriod->period_number . ' (' . $r->studyPeriod->start_time . ' - ' . $r->studyPeriod->end_time . ')';
                }
                return $r->start_time . ' - ' . $r->end_time;
            })
            ->addColumn('subject_name', fn($r) => $r->subject->name)
            ->addColumn('teacher_name', fn($r) => $r->teacher->name)
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`' . route('class-schedules.show', $r->id) . '`)" class="btn btn-xs btn-info" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('class-schedules.destroy', $r->id) . '`, `' . $r->subject->name . '`)" class="btn btn-xs btn-danger" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'class_group_id' => 'required|exists:class_groups,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day' => 'required|integer|between:1,7',
            'study_period_id' => 'required|exists:study_periods,id',
        ]);

        $period = StudyPeriod::find($request->study_period_id);
        
        // Conflict check: Teacher
        $teacherConflict = ClassSchedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('study_period_id', $request->study_period_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->first();
        
        if ($teacherConflict) {
            return response()->json(['message' => 'Guru tersebut sudah mengajar di kelas lain pada jam yang sama.'], 422);
        }

        // Conflict check: Class
        $classConflict = ClassSchedule::where('class_group_id', $request->class_group_id)
            ->where('day', $request->day)
            ->where('study_period_id', $request->study_period_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->first();
            
        if ($classConflict) {
            return response()->json(['message' => 'Kelas tersebut sudah memiliki jadwal pelajaran lain pada jam yang sama.'], 422);
        }

        $data = $request->all();
        $data['start_time'] = $period->start_time;
        $data['end_time'] = $period->end_time;

        ClassSchedule::create($data);
        return response()->json(['message' => 'Jadwal pelajaran berhasil ditambahkan']);
    }

    public function show($id)
    {
        return response()->json(['data' => ClassSchedule::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'class_group_id' => 'required|exists:class_groups,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day' => 'required|integer|between:1,7',
            'study_period_id' => 'required|exists:study_periods,id'
        ]);

        $period = StudyPeriod::findOrFail($request->study_period_id);

        // Conflict check: Teacher
        $teacherConflict = ClassSchedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('study_period_id', $request->study_period_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('id', '!=', $id)
            ->first();

        if ($teacherConflict) {
            return response()->json(['message' => 'Guru tersebut sudah mengajar di kelas lain pada jam yang sama.'], 422);
        }

        // Conflict check: Class
        $classConflict = ClassSchedule::where('class_group_id', $request->class_group_id)
            ->where('day', $request->day)
            ->where('study_period_id', $request->study_period_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('id', '!=', $id)
            ->first();
            
        if ($classConflict) {
            return response()->json(['message' => 'Kelas tersebut sudah memiliki jadwal pelajaran lain pada jam yang sama.'], 422);
        }

        $data = $request->all();
        $data['start_time'] = $period->start_time;
        $data['end_time'] = $period->end_time;

        $schedule->update($data);
        return response()->json(['message' => 'Jadwal pelajaran berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        ClassSchedule::findOrFail($id)->delete();
        return response()->json(['message' => 'Jadwal pelajaran berhasil dihapus']);
    }
}
