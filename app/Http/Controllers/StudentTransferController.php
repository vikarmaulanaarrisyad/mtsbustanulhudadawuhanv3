<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentHistory;
use App\Models\MailSetting;
use App\Models\ClassGroup;
use App\Services\DocumentVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentTransferController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('semester')->orderBy('academic_year', 'desc')->get();
        $currentAY = AcademicYear::where('current_semester', true)->first();
        
        $classGroups = ClassGroup::where('academic_year_id', $currentAY->id ?? 0)
            ->orderBy('class_level')
            ->orderBy('class_group')
            ->get();

        // Stats for Widgets
        $stats = [
            'total_out' => Student::where('student_status_id', 4)->count(),
            'total_in' => Student::where('student_status_id', 3)->count(),
            'total_active' => Student::where('student_status_id', 1)->count(),
        ];

        return view('admin.academic.transfers.index', compact('academicYears', 'currentAY', 'classGroups', 'stats'));
    }

    public function data(Request $request)
    {
        $status = $request->type == 'in' ? 3 : 4; // 3: Masuk, 4: Keluar
        
        $query = Student::with(['classGroup', 'academicYear'])
            ->where('student_status_id', $status);

        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kelas', fn($s) => $s->kelas_lengkap)
            ->addColumn('tanggal', fn($s) => $s->tanggal_keluar ? tanggal_indonesia($s->tanggal_keluar) : ($s->tanggal_masuk ? tanggal_indonesia($s->tanggal_masuk) : '-'))
            ->addColumn('action', function ($s) use ($status) {
                $btn = '<a href="' . route('students.show', $s->id) . '" class="btn btn-xs btn-primary" title="Detail"><i class="fas fa-eye"></i></a> ';
                if ($status == 4) { // Keluar
                    $btn .= '<a href="' . route('transfers.print', $s->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak Surat Pindah"><i class="fas fa-print"></i></a> ';
                }
                $btn .= '<button onclick="undoTransfer(' . $s->id . ')" class="btn btn-xs btn-danger" title="Batalkan Mutasi"><i class="fas fa-undo"></i></button>';
                return $btn;
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function storeOut(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exit_date' => 'required|date',
            'pindah_ke' => 'required|string',
            'alasan_pindah' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($request->student_id);
            
            // Create History
            StudentHistory::create([
                'student_id' => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id' => $student->student_class_group_id,
                'status' => 'mutated_out',
                'notes' => $request->alasan_pindah,
                'exit_date' => $request->exit_date,
            ]);

            // Update Student
            $student->update([
                'student_status_id' => 4, // Mutasi Keluar
                'is_active' => false,
                'tanggal_keluar' => $request->exit_date,
                'pindah_ke' => $request->pindah_ke,
                'alasan_pindah' => $request->alasan_pindah,
                'surat_pindah_number' => Student::generateLetterNumber('SKP', 'surat_pindah_number'),
            ]);

            DB::commit();
            return response()->json(['message' => 'Siswa berhasil dimutasi keluar. Surat pindah telah digenerate.', 'id' => $student->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function undo(Request $request)
    {
        try {
            $student = Student::findOrFail($request->id);
            
            $student->update([
                'student_status_id' => 1, // Aktif
                'is_active' => true,
                'tanggal_keluar' => null,
                'pindah_ke' => null,
                'alasan_pindah' => null,
                'surat_pindah_number' => null,
            ]);

            StudentHistory::where('student_id', $student->id)
                ->whereIn('status', ['mutated_out', 'mutated_in'])
                ->delete();

            return response()->json(['message' => 'Status mutasi berhasil dibatalkan.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function print($id, DocumentVerificationService $verificationService)
    {
        $student = Student::with(['profile', 'parents', 'classGroup'])->findOrFail($id);
        $setting = MailSetting::first();
        $appSetting = \App\Models\Setting::first();

        $verification = $verificationService->generate(
            $student, 
            'Surat Keterangan Pindah (SKP)', 
            $student->surat_pindah_number ?? $student->nis,
            ['destination' => $student->pindah_ke],
            get_kepala_madrasah()->name ?? null
        );

        $qrCode = $verificationService->generateQrCode($verification->verification_code, 80);

        $pdf = Pdf::loadView('admin.mail.pdf.surat_pindah', compact('student', 'setting', 'verification', 'qrCode', 'appSetting'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Surat_Pindah_' . str_replace('/', '-', ($student->surat_pindah_number ?? $student->nis)) . '.pdf');
    }
}
