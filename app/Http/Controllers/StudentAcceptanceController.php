<?php

namespace App\Http\Controllers;

use App\Models\StudentAcceptance;
use App\Models\Student;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentAcceptanceController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nama_lengkap')->get();
        $mailSetting = MailSetting::first();
        return view('admin.mail.acceptances.index', compact('students', 'mailSetting'));
    }

    public function data(Request $request)
    {
        $query = StudentAcceptance::with('student')->latest();

        if ($request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('acceptance_date', [$request->start_date, $request->end_date]);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_name', fn($r) => $r->student->nama_lengkap ?? '-')
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('student-acceptances.print', $r->id) . '" target="_blank" class="btn btn-xs btn-soft-info rounded-pill px-2" title="Cetak PDF">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="editForm(`' . route('student-acceptances.show', $r->id) . '`)" class="btn btn-xs btn-soft-primary mx-1 rounded-pill px-2" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('student-acceptances.destroy', $r->id) . '`, `' . $r->acceptance_number . '`)" class="btn btn-xs btn-soft-danger rounded-pill px-2" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'acceptance_number' => 'required|unique:student_acceptances,acceptance_number',
            'acceptance_date' => 'required|date',
            'origin_school' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Fallback to default mail settings for signer if not provided
        $mailSetting = MailSetting::first();
        if (empty($data['signer_name'])) $data['signer_name'] = $mailSetting->default_signer_name ?? '';
        if (empty($data['signer_position'])) $data['signer_position'] = $mailSetting->default_signer_position ?? '';
        if (empty($data['signer_nip'])) $data['signer_nip'] = $mailSetting->default_signer_nip ?? '';

        StudentAcceptance::create($data);

        return response()->json(['message' => 'Surat Keterangan Diterima berhasil disimpan']);
    }

    public function show($id)
    {
        return response()->json(['data' => StudentAcceptance::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $acceptance = StudentAcceptance::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'acceptance_number' => 'required|unique:student_acceptances,acceptance_number,' . $id,
            'acceptance_date' => 'required|date',
            'origin_school' => 'required|string|max:255',
        ]);

        $acceptance->update($request->all());

        return response()->json(['message' => 'Surat Keterangan Diterima berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        StudentAcceptance::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Keterangan Diterima berhasil dihapus']);
    }

    public function print($id)
    {
        $acceptance = StudentAcceptance::with(['student.classGroup', 'student.parents'])->findOrFail($id);
        $setting = MailSetting::first();

        $pdf = Pdf::loadView('admin.mail.pdf.acceptance', compact('acceptance', 'setting'));
        return $pdf->stream('Surat_Diterima_' . str_replace('/', '-', $acceptance->acceptance_number) . '.pdf');
    }
}
