<?php

namespace App\Http\Controllers;

use App\Models\StudentActiveStatement;
use App\Models\Student;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentActiveStatementController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('nama_lengkap')->get();
        $mailSetting = MailSetting::first();
        return view('admin.mail.active_statements.index', compact('students', 'mailSetting'));
    }

    public function data()
    {
        $query = StudentActiveStatement::with('students')->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('student_list', function ($r) {
                if ($r->type === 'individual') {
                    return $r->students->first()->nama_lengkap ?? '-';
                }
                return $r->students->count() . ' Siswa (Kolektif)';
            })
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('active-statements.print', $r->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak PDF">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="editForm(`' . route('active-statements.show', $r->id) . '`)" class="btn btn-xs" style="background-color:#6755a5;color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('active-statements.destroy', $r->id) . '`, `' . $r->letter_number . '`)" class="btn btn-xs" style="background-color:#d81b60;color:#fff;" title="Hapus">
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
            'letter_number' => 'required|unique:student_active_statements,letter_number',
            'letter_date' => 'required|date',
            'type' => 'required|in:individual,collective',
            'student_ids' => 'required|array|min:1',
            'purpose' => 'required|string|max:255',
            'signer_name' => 'nullable|string|max:150',
            'signer_position' => 'nullable|string|max:150',
            'signer_nip' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $statement = StudentActiveStatement::create([
                'letter_number' => $request->letter_number,
                'letter_date' => $request->letter_date,
                'type' => $request->type,
                'purpose' => $request->purpose,
                'signer_name' => $request->signer_name,
                'signer_position' => $request->signer_position,
                'signer_nip' => $request->signer_nip,
                'created_by' => Auth::id(),
            ]);

            $statement->students()->attach($request->student_ids);

            DB::commit();
            return response()->json(['message' => 'Surat Keterangan Aktif berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $statement = StudentActiveStatement::with('students')->findOrFail($id);
        return response()->json([
            'data' => $statement,
            'student_ids' => $statement->students->pluck('id')
        ]);
    }

    public function update(Request $request, $id)
    {
        $statement = StudentActiveStatement::findOrFail($id);

        $request->validate([
            'letter_number' => 'required|unique:student_active_statements,letter_number,' . $id,
            'letter_date' => 'required|date',
            'type' => 'required|in:individual,collective',
            'student_ids' => 'required|array|min:1',
            'purpose' => 'required|string|max:255',
            'signer_name' => 'nullable|string|max:150',
            'signer_position' => 'nullable|string|max:150',
            'signer_nip' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $statement->update([
                'letter_number' => $request->letter_number,
                'letter_date' => $request->letter_date,
                'type' => $request->type,
                'purpose' => $request->purpose,
                'signer_name' => $request->signer_name,
                'signer_position' => $request->signer_position,
                'signer_nip' => $request->signer_nip,
            ]);

            $statement->students()->sync($request->student_ids);

            DB::commit();
            return response()->json(['message' => 'Surat Keterangan Aktif berhasil diperbaharui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        StudentActiveStatement::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Keterangan Aktif berhasil dihapus']);
    }

    public function print($id)
    {
        $statement = StudentActiveStatement::with(['students.classGroup', 'students.parents'])->findOrFail($id);
        $setting = MailSetting::first();

        $view = $statement->type === 'individual' 
            ? 'admin.mail.pdf.active_statement_individual' 
            : 'admin.mail.pdf.active_statement_collective';

        $pdf = Pdf::loadView($view, compact('statement', 'setting'));
        return $pdf->stream('Surat_Keterangan_Aktif_' . str_replace('/', '-', $statement->letter_number) . '.pdf');
    }
}
