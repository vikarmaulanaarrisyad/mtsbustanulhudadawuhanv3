<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistrant;
use App\Models\PpdbDocument;
use App\Models\StudentAdmission;
use App\Models\AdmissionPhase;
use App\Models\AdmissionType;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PpdbDashboardController extends Controller
{
    /**
     * Dashboard PPDB siswa.
     */
    public function index()
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant()->with(['documents', 'admissionPhase', 'admissionType', 'verifier'])->first();

        // Cek PPDB aktif
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $admission = null;
        $phases = collect();
        $types = collect();
        $ppdbOpen = false;

        if ($academicYear) {
            $admission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();
            if ($admission && $admission->admission_status === 'open') {
                $ppdbOpen = true;
                $phases = AdmissionPhase::where('academic_year_id', $academicYear->id)->get();
                $types = AdmissionType::where('academic_year_id', $academicYear->id)->get();
            }
        }

        return view('ppdb.dashboard', compact(
            'user',
            'registrant',
            'admission',
            'phases',
            'types',
            'ppdbOpen',
            'academicYear'
        ));
    }

    /**
     * Simpan biodata pendaftaran.
     */
    public function storeBiodata(Request $request)
    {
        $user = Auth::user();

        // Cek sudah pernah mendaftar
        if ($user->ppdbRegistrant) {
            return redirect()->route('ppdb.dashboard')->with('error', 'Anda sudah mendaftar.');
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|max:100',
            'no_hp_ortu' => 'required|max:20',
            'asal_sekolah' => 'required',
            'student_admission_id' => 'required|exists:student_admissions,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'doc_akta' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'doc_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'doc_ijazah' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'doc_skhun' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'doc_rapor' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'doc_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'doc_kip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $admission = StudentAdmission::findOrFail($request->student_admission_id);

            $registrant = PpdbRegistrant::create([
                'user_id' => $user->id,
                'registration_number' => PpdbRegistrant::generateRegistrationNumber($admission->admission_year),
                'student_admission_id' => $request->student_admission_id,
                'admission_phase_id' => $request->admission_phase_id,
                'admission_type_id' => $request->admission_type_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_sekolah' => $request->asal_sekolah,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'alamat' => $request->alamat,
                'status' => 'pending',
            ]);

            // Upload foto
            if ($request->hasFile('foto')) {
                $registrant->foto = $request->file('foto')->store('ppdb/foto', 'public');
                $registrant->save();
            }

            // Upload berkas
            $docTypes = PpdbRegistrant::DOCUMENT_TYPES;
            foreach ($docTypes as $type => $name) {
                if ($request->hasFile("doc_{$type}")) {
                    $path = $request->file("doc_{$type}")->store('ppdb/documents/' . $registrant->id, 'public');
                    PpdbDocument::create([
                        'ppdb_registrant_id' => $registrant->id,
                        'document_name' => $name,
                        'document_type' => $type,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('ppdb.dashboard')->with('success', 'Pendaftaran berhasil! No. Pendaftaran Anda: ' . $registrant->registration_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update biodata yang sudah ada.
     */
    public function updateBiodata(Request $request)
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant;

        if (!$registrant) {
            return redirect()->route('ppdb.dashboard')->with('error', 'Data tidak ditemukan.');
        }

        // Hanya bisa edit jika status pending atau berkas tidak lengkap
        if (!in_array($registrant->status, ['pending', 'berkas_tidak_lengkap'])) {
            return redirect()->route('ppdb.dashboard')->with('error', 'Data tidak bisa diedit karena sudah diverifikasi.');
        }

        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|max:100',
            'no_hp_ortu' => 'required|max:20',
            'asal_sekolah' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $registrant->update([
                'admission_phase_id' => $request->admission_phase_id,
                'admission_type_id' => $request->admission_type_id,
                'nama_lengkap' => $request->nama_lengkap,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_sekolah' => $request->asal_sekolah,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'alamat' => $request->alamat,
                'status' => 'pending', // Reset ke pending setelah edit
            ]);

            // Update foto
            if ($request->hasFile('foto')) {
                if ($registrant->foto) Storage::disk('public')->delete($registrant->foto);
                $registrant->foto = $request->file('foto')->store('ppdb/foto', 'public');
                $registrant->save();
            }

            // Upload berkas baru (replace jika ada)
            $docTypes = PpdbRegistrant::DOCUMENT_TYPES;
            foreach ($docTypes as $type => $name) {
                if ($request->hasFile("doc_{$type}")) {
                    $oldDoc = $registrant->documents()->where('document_type', $type)->first();
                    if ($oldDoc) {
                        Storage::disk('public')->delete($oldDoc->file_path);
                        $oldDoc->delete();
                    }
                    $path = $request->file("doc_{$type}")->store('ppdb/documents/' . $registrant->id, 'public');
                    PpdbDocument::create([
                        'ppdb_registrant_id' => $registrant->id,
                        'document_name' => $name,
                        'document_type' => $type,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('ppdb.dashboard')->with('success', 'Data berhasil diperbaharui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Cetak Bukti Pendaftaran (Kartu).
     */
    public function printRegistration()
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant()->with(['admissionPhase', 'admissionType'])->firstOrFail();
        $source = \App\Models\MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ppdb.pdf.registration_card', compact('registrant', 'source', 'admission'));
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->download('Bukti_Pendaftaran_' . $registrant->registration_number . '.pdf');
    }

    /**
     * Cetak Lembar Verifikasi Berkas.
     */
    public function printVerification()
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant()->with(['documents', 'admissionPhase', 'admissionType'])->firstOrFail();
        $source = \App\Models\MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ppdb.pdf.verification_checklist', compact('registrant', 'source', 'admission'));
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->download('Lembar_Verifikasi_' . $registrant->registration_number . '.pdf');
    }
}
