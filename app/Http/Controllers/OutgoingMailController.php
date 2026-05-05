<?php

namespace App\Http\Controllers;

use App\Models\OutgoingMail;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OutgoingMailController extends Controller
{
    public function index()
    {
        $mailSetting = MailSetting::first();
        return view('admin.mail.outgoing.index', compact('mailSetting'));
    }

    public function data(Request $request)
    {
        $query = OutgoingMail::latest();

        if ($request->mail_subject) {
            $query->where('mail_subject', 'like', "%{$request->mail_subject}%")
                  ->orWhere('mail_recipient', 'like', "%{$request->mail_subject}%");
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('mail_date', [$request->start_date, $request->end_date]);
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('outgoing-mails.print', $r->id) . '" target="_blank" class="btn btn-xs btn-soft-info rounded-pill px-2" title="Cetak PDF">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="editForm(`' . route('outgoing-mails.show', $r->id) . '`)" class="btn btn-xs btn-soft-primary mx-1 rounded-pill px-2" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('outgoing-mails.destroy', $r->id) . '`, `' . $r->mail_number . '`)" class="btn btn-xs btn-soft-danger rounded-pill px-2" title="Hapus">
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
            'mail_number' => 'required|unique:outgoing_mails,mail_number',
            'mail_date' => 'required|date',
            'mail_subject' => 'required|string|max:255',
            'mail_recipient' => 'required|string|max:255',
            'mail_content' => 'required',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        OutgoingMail::create($data);

        return response()->json(['message' => 'Surat Keluar berhasil disimpan']);
    }

    public function show($id)
    {
        return response()->json(['data' => OutgoingMail::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $mail = OutgoingMail::findOrFail($id);

        $request->validate([
            'mail_number' => 'required|unique:outgoing_mails,mail_number,' . $id,
            'mail_date' => 'required|date',
            'mail_subject' => 'required|string|max:255',
            'mail_recipient' => 'required|string|max:255',
            'mail_content' => 'required',
        ]);

        $mail->update($request->all());

        return response()->json(['message' => 'Surat Keluar berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        OutgoingMail::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Keluar berhasil dihapus']);
    }

    public function print($id)
    {
        $mail = OutgoingMail::findOrFail($id);
        $setting = MailSetting::first();

        $pdf = Pdf::loadView('admin.mail.pdf.outgoing', compact('mail', 'setting'));
        return $pdf->stream('Surat_Keluar_' . str_replace('/', '-', $mail->mail_number) . '.pdf');
    }
}
