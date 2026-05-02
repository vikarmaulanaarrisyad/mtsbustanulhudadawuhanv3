<?php

namespace App\Http\Controllers;

use App\Models\DutyLetter;
use App\Models\Teacher;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DutyLetterController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('name')->get();
        $mailSetting = MailSetting::first();
        return view('admin.mail.duty_letters.index', compact('teachers', 'mailSetting'));
    }

    public function data()
    {
        $query = DutyLetter::with('teachers')->latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('teacher_list', function ($r) {
                return $r->teachers->pluck('name')->implode(', ');
            })
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <div class="dropdown-menu">
                        <a href="' . route('duty-letters.print-st', $r->id) . '" target="_blank" class="dropdown-item">Surat Tugas</a>
                        <a href="' . route('duty-letters.print-sppd', $r->id) . '" target="_blank" class="dropdown-item">SPPD</a>
                    </div>
                    <button onclick="editForm(`' . route('duty-letters.show', $r->id) . '`)" class="btn btn-xs" style="background-color:#6755a5;color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('duty-letters.destroy', $r->id) . '`, `' . $r->letter_number . '`)" class="btn btn-xs" style="background-color:#d81b60;color:#fff;" title="Hapus">
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
            'letter_number' => 'required|unique:duty_letters,letter_number',
            'letter_date' => 'required|date',
            'purpose' => 'required|string',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'teacher_ids' => 'required|array|min:1',
            'signer_name' => 'nullable|string|max:150',
            'signer_position' => 'nullable|string|max:150',
            'signer_nip' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $letter = DutyLetter::create([
                'letter_number' => $request->letter_number,
                'letter_date' => $request->letter_date,
                'purpose' => $request->purpose,
                'destination' => $request->destination,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'transportation' => $request->transportation,
                'budget_source' => $request->budget_source,
                'signer_name' => $request->signer_name,
                'signer_position' => $request->signer_position,
                'signer_nip' => $request->signer_nip,
                'created_by' => Auth::id(),
            ]);

            $letter->teachers()->attach($request->teacher_ids);

            DB::commit();
            return response()->json(['message' => 'Surat Tugas berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $letter = DutyLetter::with('teachers')->findOrFail($id);
        return response()->json([
            'data' => $letter,
            'teacher_ids' => $letter->teachers->pluck('id')
        ]);
    }

    public function update(Request $request, $id)
    {
        $letter = DutyLetter::findOrFail($id);

        $request->validate([
            'letter_number' => 'required|unique:duty_letters,letter_number,' . $id,
            'letter_date' => 'required|date',
            'purpose' => 'required|string',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'teacher_ids' => 'required|array|min:1',
            'signer_name' => 'nullable|string|max:150',
            'signer_position' => 'nullable|string|max:150',
            'signer_nip' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $letter->update([
                'letter_number' => $request->letter_number,
                'letter_date' => $request->letter_date,
                'purpose' => $request->purpose,
                'destination' => $request->destination,
                'departure_date' => $request->departure_date,
                'return_date' => $request->return_date,
                'transportation' => $request->transportation,
                'budget_source' => $request->budget_source,
                'signer_name' => $request->signer_name,
                'signer_position' => $request->signer_position,
                'signer_nip' => $request->signer_nip,
            ]);

            $letter->teachers()->sync($request->teacher_ids);

            DB::commit();
            return response()->json(['message' => 'Surat Tugas berhasil diperbaharui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DutyLetter::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Tugas berhasil dihapus']);
    }

    public function printST($id)
    {
        $letter = DutyLetter::with('teachers')->findOrFail($id);
        $setting = MailSetting::first();
        $pdf = Pdf::loadView('admin.mail.pdf.duty_letter_st', compact('letter', 'setting'));
        return $pdf->stream('Surat_Tugas_' . $letter->letter_number . '.pdf');
    }

    public function printSPPD($id)
    {
        $letter = DutyLetter::with('teachers')->findOrFail($id);
        $setting = MailSetting::first();
        $pdf = Pdf::loadView('admin.mail.pdf.duty_letter_sppd', compact('letter', 'setting'));
        return $pdf->stream('SPPD_' . $letter->letter_number . '.pdf');
    }
}
