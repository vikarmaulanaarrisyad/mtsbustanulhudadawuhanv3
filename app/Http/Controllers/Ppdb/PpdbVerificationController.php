<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistrant;
use App\Models\MailSetting;
use App\Models\StudentAdmission;
use App\Traits\LogsPpdbActivity;
use Illuminate\Http\Request;

class PpdbVerificationController extends Controller
{
    use LogsPpdbActivity;
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
        if (!auth()->check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate([
            'id' => 'required|exists:ppdb_registrants,id',
            'action' => 'required|in:verify_doc,verify_payment,reject,incomplete',
            'catatan' => 'nullable|string|max:500'
        ]);

        $registrant = PpdbRegistrant::findOrFail($request->id);
        
        switch ($request->action) {
            case 'verify_doc':
                if (!auth()->user()->can('ppdb.verify.berkas')) {
                    return response()->json(['message' => 'Anda tidak memiliki hak akses verifikasi berkas.'], 403);
                }
                $registrant->update([
                    'status' => 'berkas_lengkap',
                    'verifier_id' => auth()->id(),
                    'verified_at' => now(),
                    'catatan_verifikasi' => $request->catatan,
                ]);

                $this->logPpdbActivity(
                    $registrant->id, 
                    'verify_berkas', 
                    $request->catatan ?? 'Berkas pendaftaran diverifikasi dan dinyatakan lengkap.',
                    'berkas_lengkap'
                );

                $msg = "Berkas pendaftaran berhasil diverifikasi.";
                break;

            case 'incomplete':
                if (!auth()->user()->can('ppdb.verify.berkas')) {
                    return response()->json(['message' => 'Anda tidak memiliki hak akses verifikasi berkas.'], 403);
                }
                $registrant->update([
                    'status' => 'berkas_tidak_lengkap',
                    'verifier_id' => auth()->id(),
                    'verified_at' => now(),
                    'catatan_verifikasi' => $request->catatan ?? 'Berkas tidak lengkap, silakan perbaiki.',
                ]);

                $this->logPpdbActivity(
                    $registrant->id, 
                    'berkas_incomplete', 
                    $request->catatan ?? 'Berkas ditandai tidak lengkap.',
                    'berkas_tidak_lengkap'
                );

                $msg = "Status berkas ditandai tidak lengkap.";
                break;

            case 'reject':
                if (!auth()->user()->can('ppdb.verify.berkas')) {
                    return response()->json(['message' => 'Anda tidak memiliki hak akses verifikasi berkas.'], 403);
                }
                $registrant->update([
                    'status' => 'ditolak',
                    'verifier_id' => auth()->id(),
                    'verified_at' => now(),
                    'catatan_verifikasi' => $request->catatan ?? 'Pendaftaran ditolak.',
                ]);

                $this->logPpdbActivity(
                    $registrant->id, 
                    'reject_ppdb', 
                    $request->catatan ?? 'Pendaftaran ditolak.',
                    'ditolak'
                );

                $msg = "Pendaftaran ditolak.";
                break;

            case 'verify_payment':
                if (!auth()->user()->can('ppdb.verify.daftar_ulang')) {
                    return response()->json(['message' => 'Anda tidak memiliki hak akses verifikasi daftar ulang.'], 403);
                }
                $registrant->update([
                    'status' => 'daftar_ulang_terverifikasi',
                    'verifier_id' => auth()->id(),
                    're_registration_verified_at' => now(),
                ]);

                $this->logPpdbActivity(
                    $registrant->id, 
                    'verify_payment', 
                    'Pembayaran daftar ulang diverifikasi.',
                    'daftar_ulang_terverifikasi'
                );

                $msg = "Pembayaran daftar ulang berhasil diverifikasi.";
                break;
        }

        return response()->json(['message' => $msg]);
    }

    /**
     * Halaman Scanner Kamera untuk Petugas
     */
    public function scanner()
    {
        if (!auth()->user()->can('ppdb.verify') && !auth()->user()->can('ppdb.verify.berkas') && !auth()->user()->can('ppdb.verify.daftar_ulang')) {
            abort(403, 'Anda tidak memiliki hak akses verifikasi.');
        }
        return view('ppdb.scanner');
    }
}
