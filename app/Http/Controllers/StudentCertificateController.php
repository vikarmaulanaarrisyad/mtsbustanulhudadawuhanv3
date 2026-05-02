<?php

namespace App\Http\Controllers;

use App\Models\StudentCertificate;
use App\Models\Student;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentCertificateController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nama_lengkap')->get();
        $mailSetting = MailSetting::first();
        return view('admin.mail.certificates.index', compact('students', 'mailSetting'));
    }

    public function data()
    {
        $query = StudentCertificate::with('student')->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_name', fn($r) => $r->student->nama_lengkap ?? '-')
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('student-certificates.print', $r->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak PDF">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="editForm(`' . route('student-certificates.show', $r->id) . '`)" class="btn btn-xs" style="background-color:#6755a5;color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('student-certificates.destroy', $r->id) . '`, `' . $r->certificate_number . '`)" class="btn btn-xs" style="background-color:#d81b60;color:#fff;" title="Hapus">
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
            'certificate_number' => 'required|unique:student_certificates,certificate_number',
            'certificate_date' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        StudentCertificate::create($data);

        return response()->json(['message' => 'Surat Keterangan berhasil disimpan']);
    }

    public function show($id)
    {
        return response()->json(['data' => StudentCertificate::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $cert = StudentCertificate::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'certificate_number' => 'required|unique:student_certificates,certificate_number,' . $id,
            'certificate_date' => 'required|date',
            'purpose' => 'required|string|max:255',
        ]);

        $cert->update($request->all());

        return response()->json(['message' => 'Surat Keterangan berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        StudentCertificate::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Keterangan berhasil dihapus']);
    }

    public function print($id)
    {
        $cert = StudentCertificate::with(['student.classGroup', 'student.parents'])->findOrFail($id);
        $setting = MailSetting::first();

        $pdf = Pdf::loadView('admin.mail.pdf.certificate', compact('cert', 'setting'));
        return $pdf->stream('Surat_Keterangan_' . $cert->student->nama_lengkap . '.pdf');
    }
}
