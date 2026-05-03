<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistrant;
use App\Models\MailSetting;
use App\Models\StudentAdmission;
use Illuminate\Http\Request;

class PpdbVerificationController extends Controller
{
    /**
     * Halaman verifikasi publik (hasil scan QR)
     */
    public function check($regNumber)
    {
        $registrant = PpdbRegistrant::with(['admissionPhase', 'admissionType', 'studentAdmission', 'documents', 'verifier'])
            ->where('registration_number', $regNumber)
            ->firstOrFail();
            
        $source = MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);

        return view('ppdb.public_verify', compact('registrant', 'source', 'admission'));
    }

    /**
     * Proses verifikasi dari hasil scan
     */
    public function processVerify(Request $request)
    {
        if (!auth()->check() || !auth()->user()->can('ppdb.verify')) {
            return response()->json(['message' => 'Anda tidak memiliki hak akses verifikasi.'], 403);
        }

        $request->validate([
            'id' => 'required|exists:ppdb_registrants,id',
            'action' => 'required|in:verify_doc,verify_payment'
        ]);

        $registrant = PpdbRegistrant::findOrFail($request->id);
        
        if ($request->action === 'verify_doc') {
            $registrant->update([
                'status' => 'verified',
                'verifier_id' => auth()->id(),
                'verified_at' => now()
            ]);
            $msg = "Berkas pendaftaran berhasil diverifikasi.";
        } else {
            $registrant->update([
                'status' => 'daftar_ulang',
                'verifier_id' => auth()->id(),
                're_registration_verified_at' => now()
            ]);
            $msg = "Pembayaran daftar ulang berhasil diverifikasi.";
        }

        return response()->json(['message' => $msg]);
    }

    /**
     * Halaman Scanner Kamera untuk Petugas
     */
    public function scanner()
    {
        if (!auth()->user()->can('ppdb.verify')) {
            abort(403, 'Anda tidak memiliki hak akses verifikasi.');
        }
        return view('ppdb.scanner');
    }
}
