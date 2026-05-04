<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentHistory;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentGraduationController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('semester')->orderBy('academic_year', 'desc')->get();
        $classGroups = \App\Models\ClassGroup::whereIn('class_level', [6, 9, 12])
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();
        return view('admin.academic.graduations.index', compact('academicYears', 'classGroups'));
    }

    public function data(Request $request)
    {
        $query = Student::with(['classGroup', 'academicYear'])
            ->whereHas('classGroup', function($q) {
                $q->whereIn('class_level', [6, 9, 12]);
            })
            ->where(function($q) use ($request) {
                if ($request->is_graduated == '1') {
                    $q->where('student_status_id', 2);
                } else {
                    $q->where(function($sq) {
                        $sq->where('student_status_id', '!=', 2)
                           ->orWhereNull('student_status_id');
                    })->where('is_active', true);
                }
            })
            ->when($request->academic_year_id, function($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
                
                // Exclude students who were just PROMOTED to this year 
                // because they just started their final year and aren't ready to graduate yet.
                $q->whereDoesntHave('histories', function($sq) use ($request) {
                    $sq->where('academic_year_id', $request->academic_year_id)
                       ->where('status', 'promoted');
                });
            })
            ->when($request->class_group_id, fn($q) => $q->where('student_class_group_id', $request->class_group_id))
            ->orderBy('nama_lengkap');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($s) {
                return '<input type="checkbox" name="student_ids[]" value="' . $s->id . '" class="student-checkbox">';
            })
            ->addColumn('kelas', fn($s) => $s->kelas_lengkap)
            ->addColumn('action', function ($s) {
                if ($s->student_status_id == 2) {
                    return '<a href="' . route('graduations.print-skl', $s->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak Surat Keterangan Lulus"><i class="fas fa-print mr-1"></i> Cetak SKL</a>';
                }
                return '-';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function graduate(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'exit_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // Create History
                StudentHistory::create([
                    'student_id' => $student->id,
                    'academic_year_id' => $student->academic_year_id,
                    'class_group_id' => $student->student_class_group_id,
                    'status' => 'graduated',
                    'notes' => $request->notes,
                    'exit_date' => $request->exit_date,
                ]);

                // Update Student Current State
                $student->update([
                    'student_status_id' => 2, // Lulus
                    'is_active' => false,
                    'tanggal_keluar' => $request->exit_date,
                    'skl_number' => Student::generateLetterNumber('SKL', 'skl_number'),
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Proses kelulusan berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function undo(Request $request)
    {
        $request->validate(['student_ids' => 'required|array|min:1']);

        try {
            DB::beginTransaction();

            foreach ($request->student_ids as $id) {
                $student = Student::findOrFail($id);
                
                // Restore Active status (ID 1)
                $student->update([
                    'student_status_id' => 1, // Aktif
                    'is_active' => true,
                    'tanggal_keluar' => null,
                    'skl_number' => null,
                ]);

                // Delete 'graduated' history
                StudentHistory::where('student_id', $id)->where('status', 'graduated')->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Pembatalan kelulusan berhasil dilakukan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function printSKL($id)
    {
        $student = Student::with(['profile', 'parents', 'classGroup'])->findOrFail($id);
        $setting = MailSetting::first(); // Use mail settings for signer
        $pdf = Pdf::loadView('admin.mail.pdf.skl', compact('student', 'setting'));
        return $pdf->stream('SKL_' . str_replace('/', '-', ($student->skl_number ?? $student->nis)) . '.pdf');
    }
}
