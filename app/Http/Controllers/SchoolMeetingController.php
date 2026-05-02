<?php

namespace App\Http\Controllers;

use App\Models\SchoolMeeting;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SchoolMeetingController extends Controller
{
    public function index()
    {
        return view('admin.mail.meetings.index');
    }

    public function data()
    {
        $query = SchoolMeeting::latest();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function ($r) {
                return '
                <div class="btn-group">
                    <a href="' . route('school-meetings.print', $r->id) . '" target="_blank" class="btn btn-xs btn-info" title="Cetak PDF">
                        <i class="fas fa-print"></i>
                    </a>
                    <button onclick="editForm(`' . route('school-meetings.show', $r->id) . '`)" class="btn btn-xs" style="background-color:#6755a5;color:#fff;" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="deleteData(`' . route('school-meetings.destroy', $r->id) . '`, `' . $r->meeting_number . '`)" class="btn btn-xs" style="background-color:#d81b60;color:#fff;" title="Hapus">
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
            'meeting_number' => 'required|unique:school_meetings,meeting_number',
            'mail_date' => 'required|date',
            'meeting_subject' => 'required|string|max:255',
            'recipient_description' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'meeting_place' => 'required|string|max:255',
            'meeting_agenda' => 'required|string',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        SchoolMeeting::create($data);

        return response()->json(['message' => 'Surat Undangan berhasil disimpan']);
    }

    public function show($id)
    {
        return response()->json(['data' => SchoolMeeting::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $meeting = SchoolMeeting::findOrFail($id);

        $request->validate([
            'meeting_number' => 'required|unique:school_meetings,meeting_number,' . $id,
            'mail_date' => 'required|date',
            'meeting_subject' => 'required|string|max:255',
            'recipient_description' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'meeting_place' => 'required|string|max:255',
            'meeting_agenda' => 'required|string',
        ]);

        $meeting->update($request->all());

        return response()->json(['message' => 'Surat Undangan berhasil diperbaharui']);
    }

    public function destroy($id)
    {
        SchoolMeeting::findOrFail($id)->delete();
        return response()->json(['message' => 'Surat Undangan berhasil dihapus']);
    }

    public function print($id)
    {
        $meeting = SchoolMeeting::findOrFail($id);
        $setting = MailSetting::first();

        $pdf = Pdf::loadView('admin.mail.pdf.meeting', compact('meeting', 'setting'));
        return $pdf->stream('Undangan_Rapat_' . str_replace('/', '_', $meeting->meeting_number) . '.pdf');
    }
}
