<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentHistory;
use App\Models\MailSetting;
use App\Services\DocumentVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentGraduationController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('semester')->orderBy('academic_year', 'desc')->get();
        $currentAY = AcademicYear::where('current_semester', true)->first();
        
        $classGroups = \App\Models\ClassGroup::whereIn('class_level', [6, 9, 12])
            ->where('academic_year_id', $currentAY->id ?? 0)
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();

        // Statistics for current active year
        $stats = [
            'total' => Student::where('academic_year_id', $currentAY->id ?? 0)
                ->whereHas('classGroup', fn($q) => $q->whereIn('class_level', [6, 9, 12]))
                ->where('is_active', true)
                ->count(),
            'graduated' => Student::where('academic_year_id', $currentAY->id ?? 0)
                ->where('student_status_id', 2) // Lulus
                ->whereHas('classGroup', fn($q) => $q->whereIn('class_level', [6, 9, 12]))
                ->count(),
        ];
        $stats['remaining'] = $stats['total'] - $stats['graduated'];
            
        return view('admin.academic.graduations.index', compact('academicYears', 'currentAY', 'classGroups', 'stats'));
    }

    public function getClassesByYear(Request $request)
    {
        $classes = \App\Models\ClassGroup::whereIn('class_level', [6, 9, 12])
            ->where('academic_year_id', $request->academic_year_id)
            ->orderBy('class_group')
            ->orderBy('sub_class_group')
            ->get();
            
        return response()->json($classes);
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

            // VALIDATION: Block graduation in Ganjil semester
            $firstStudentId = $request->student_ids[0] ?? null;
            if ($firstStudentId) {
                $checkStudent = Student::with('academicYear.semester')->find($firstStudentId);
                if ($checkStudent && $checkStudent->academicYear && $checkStudent->academicYear->semester->semester_name == 'Ganjil') {
                    return response()->json(['message' => 'Aksi Ditolak: Kelulusan hanya diperbolehkan pada Semester Genap. Silakan periksa kembali Tahun Pelajaran yang aktif.'], 422);
                }
            }

            $principal = get_kepala_madrasah();

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

                // Helper to parse level from string if 0
                $parseLevel = function($cg) {
                    if (!$cg) return 0;
                    if ($cg->class_level > 0) return $cg->class_level;
                    $val = $cg->class_group;
                    if (is_numeric($val)) return (int)$val;
                    $romanMap = ['I'=>1,'II'=>2,'III'=>3,'IV'=>4,'V'=>5,'VI'=>6,'VII'=>7,'VIII'=>8,'IX'=>9,'X'=>10,'XI'=>11,'XII'=>12];
                    return $romanMap[strtoupper($val)] ?? 0;
                };

                // Calculate new level
                $newLevel = $student->current_class_level;
                if (!$newLevel && $student->classGroup) {
                    $newLevel = $parseLevel($student->classGroup);
                }
                $newLevel++; // Increment because they are graduating/finishing the current level

                // Update Student Current State
                $student->update([
                    'student_status_id' => 2, // Lulus
                    'is_active' => false,
                    'current_class_level' => $newLevel,
                    'tanggal_keluar' => $request->exit_date,
                    'keterangan' => $request->notes,
                    'graduated_principal_name' => $principal->name ?? '',
                    'graduated_principal_nip' => $principal->nip ?? '',
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

    public function printSKL($id, DocumentVerificationService $verificationService)
    {
        $student = Student::with(['profile', 'parents', 'classGroup'])->findOrFail($id);
        $setting = MailSetting::first(); 
        $appSetting = \App\Models\Setting::first();

        // Generate verification record
        $verification = $verificationService->generate(
            $student, 
            'Surat Keterangan Lulus (SKL)', 
            $student->skl_number ?? $student->nis,
            ['academic_year' => $student->academicYear->academic_year ?? '-'],
            $student->graduated_principal_name ?: (get_kepala_madrasah()->name ?? null)
        );

        $qrCode = $verificationService->generateQrCode($verification->verification_code, 80);

        $pdf = Pdf::loadView('admin.mail.pdf.skl', compact('student', 'setting', 'verification', 'qrCode', 'appSetting'));
        return $pdf->stream('SKL_' . str_replace('/', '-', ($student->skl_number ?? $student->nis)) . '.pdf');
    }
    public function printSKLMass(Request $request, DocumentVerificationService $verificationService)
    {
        $studentIds = $request->student_ids;
        if (!$studentIds || !is_array($studentIds)) {
            return back()->with('error', 'Silakan pilih siswa terlebih dahulu.');
        }

        $students = Student::with(['profile', 'parents', 'classGroup', 'academicYear'])
            ->whereIn('id', $studentIds)
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $setting = MailSetting::first();
        $appSetting = \App\Models\Setting::first();
        $data = [];

        foreach ($students as $student) {
            // Generate verification record if not exists
            $verification = $verificationService->generate(
                $student, 
                'Surat Keterangan Lulus (SKL)', 
                $student->skl_number ?? $student->nis,
                ['academic_year' => $student->academicYear->academic_year ?? '-'],
                $student->graduated_principal_name ?: (get_kepala_madrasah()->name ?? null)
            );

            $qrCode = $verificationService->generateQrCode($verification->verification_code, 80);
            
            $data[] = [
                'student' => $student,
                'verification' => $verification,
                'qrCode' => $qrCode
            ];
        }

        $pdf = Pdf::loadView('admin.mail.pdf.skl_mass', compact('data', 'setting', 'appSetting'))
            ->setPaper('a4', 'portrait');
            
        return $pdf->stream('SKL_Massal_' . date('Ymd_His') . '.pdf');
    }
}
