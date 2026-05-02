<?php

namespace App\Http\Controllers;

use App\Models\StudentTransfer;
use App\Models\Student;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentTransferController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nama_lengkap')->get();
        $mailSetting = MailSetting::first();
        return view('admin.mail.transfers.index', compact('students', 'mailSetting'));
    }

    public function data()
    {
        $query = StudentTransfer::with('student')->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_name', fn($r) => $r->student->nama_lengkap ?? '-')
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('student-transfers.print', $r->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak PDF">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="editForm(`' . route('student-transfers.show', $r->id) . '`)" class="btn btn-xs" style="background-color:#6755a5;color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('student-transfers.destroy', $r->id) . '`, `' . $r->transfer_number . '`)" class="btn btn-xs" style="background-color:#d81b60;color:#fff;" title="Hapus">
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
            'transfer_number' => 'required|unique:student_transfers,transfer_number',
            'transfer_date' => 'required|date',
            'destination_school' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        StudentTransfer::create($data);

        return response()->json(['message' => 'Surat Mutasi berhasil disimpan']);
    }

    public function show($id)
    {
        return response()->json(['data' => StudentTransfer::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $transfer = StudentTransfer::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'transfer_number' => 'required|unique:student_transfers,transfer_number,' . $id,
            'transfer_date' => 'required|date',
            'destination_school' => 'required|string|max:255',
        ]);

        $transfer->update($request->all());

        return response()->json(['message' => 'Surat Mutasi berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        StudentTransfer::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Mutasi berhasil dihapus']);
    }

    public function print($id)
    {
        $transfer = StudentTransfer::with(['student.classGroup', 'student.parents', 'student.academicYear'])->findOrFail($id);
        $setting = MailSetting::first();

        $pdf = Pdf::loadView('admin.mail.pdf.transfer', compact('transfer', 'setting'));
        return $pdf->stream('Surat_Mutasi_' . $transfer->student->nama_lengkap . '.pdf');
    }
}
