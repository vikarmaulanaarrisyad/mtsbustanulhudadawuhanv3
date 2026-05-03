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
use App\Models\PpdbPaymentItem;

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
        $paymentItems = collect();
        $ppdbOpen = false;
        $isAnnouncementActive = false;

        if ($academicYear) {
            $admission = StudentAdmission::where('academic_year_id', $academicYear->id)->first();
            if ($admission && $admission->admission_status === 'open') {
                $ppdbOpen = true;
                $phases = AdmissionPhase::where('academic_year_id', $academicYear->id)->get();
                $types = AdmissionType::where('academic_year_id', $academicYear->id)->get();
            }

            // Ambil rincian biaya
            $paymentItems = PpdbPaymentItem::where('academic_year_id', $academicYear->id)
                ->where('is_active', true)
                ->get();
            
            if ($registrant) {
                $isAnnouncementActive = ($registrant->admissionPhase && $registrant->admissionPhase->announcement_date)
                    ? $registrant->admissionPhase->isAnnouncementActive()
                    : ($admission ? $admission->isAnnouncementActive() : false);
            } elseif ($admission) {
                $isAnnouncementActive = $admission->isAnnouncementActive();
            }
        }

        $student = null;
        $schedules = collect();
        $attendanceStats = ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0];
        $agendas = collect();
        $hasCheckedInToday = false;
        
        // Data Pembatasan Absen
        $attendanceSetting = \App\Models\AttendanceSetting::first();
        $isWorkDay = true;
        $isCheckInTime = true;
        $isHoliday = false;
        $attendanceMessage = "";

        if ($registrant && $registrant->status == 'sudah_masuk_siswa') {
            $student = \App\Models\Student::where('nisn', $registrant->nisn)
                ->with(['classGroup.homeroomTeacher', 'academicYear'])
                ->first();
            
            if ($student) {
                // 1. Jadwal Pelajaran
                if ($student->student_class_group_id) {
                    $schedules = \App\Models\ClassSchedule::where('class_group_id', $student->student_class_group_id)
                        ->with(['subject', 'teacher', 'studyPeriod'])
                        ->orderBy('day')
                        ->orderBy('start_time')
                        ->get()
                        ->groupBy('day');
                }

                // 2. Statistik Presensi
                $attendances = \App\Models\StudentAttendance::where('student_id', $student->id)
                    ->select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();
                
                $attendanceStats['H'] = $attendances['present'] ?? 0;
                $attendanceStats['I'] = $attendances['permit'] ?? 0;
                $attendanceStats['S'] = $attendances['sick'] ?? 0;
                $attendanceStats['A'] = $attendances['absent'] ?? 0;

                // 3. Kalender Akademik / Agenda (3 Event Terdekat)
                $agendas = \App\Models\SchoolAgenda::where('start_date', '>=', now()->format('Y-m-d'))
                    ->orderBy('start_date', 'asc')
                    ->limit(3)
                    ->get();

                // 4. Cek apakah sudah absen hari ini
                $hasCheckedInToday = \App\Models\StudentAttendance::where('student_id', $student->id)
                    ->where('date', now()->format('Y-m-d'))
                    ->exists();

                // 5. Validasi Aturan Absensi
                if ($attendanceSetting) {
                    $now = now();
                    $isWorkDay = in_array($now->dayOfWeekIso, (array) $attendanceSetting->work_days);
                    $isCheckInTime = $now->between($attendanceSetting->check_in_start, $attendanceSetting->check_in_end);
                    $isHoliday = \App\Models\Holiday::where('date', $now->format('Y-m-d'))->exists();

                    if (!$isWorkDay) $attendanceMessage = "Hari ini bukan hari sekolah.";
                    elseif ($isHoliday) $attendanceMessage = "Hari ini adalah hari libur sekolah.";
                    elseif (!$isCheckInTime) {
                        if ($now->lessThan($attendanceSetting->check_in_start)) {
                            $attendanceMessage = "Absen dibuka jam " . substr($attendanceSetting->check_in_start, 0, 5);
                        } else {
                            $attendanceMessage = "Waktu absen masuk sudah berakhir.";
                        }
                    }
                }
            }
        }

        return view('ppdb.dashboard', compact(
            'user',
            'registrant',
            'admission',
            'phases',
            'types',
            'paymentItems',
            'ppdbOpen',
            'isAnnouncementActive',
            'academicYear',
            'student',
            'schedules',
            'attendanceStats',
            'agendas',
            'hasCheckedInToday',
            'isWorkDay',
            'isCheckInTime',
            'isHoliday',
            'attendanceMessage'
        ));
    }

    /**
     * Absensi Mandiri Siswa.
     */
    public function storeAttendance(Request $request)
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant;

        if (!$registrant || $registrant->status != 'sudah_masuk_siswa') {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $student = \App\Models\Student::where('nisn', $registrant->nisn)->first();

        if (!$student) {
            return response()->json(['message' => 'Data siswa tidak ditemukan.'], 404);
        }

        // Cek Aturan Absensi Global
        $setting = \App\Models\AttendanceSetting::first();
        if ($setting) {
            $now = now();
            if (!in_array($now->dayOfWeekIso, (array) $setting->work_days)) {
                return response()->json(['message' => 'Hari ini bukan hari sekolah.'], 422);
            }
            if (!$now->between($setting->check_in_start, $setting->check_in_end)) {
                return response()->json(['message' => 'Waktu absen sudah ditutup atau belum dibuka.'], 422);
            }
            if (\App\Models\Holiday::where('date', $now->format('Y-m-d'))->exists()) {
                return response()->json(['message' => 'Hari ini adalah hari libur.'], 422);
            }
        }

        // Cek sudah absen hari ini
        $exists = \App\Models\StudentAttendance::where('student_id', $student->id)
            ->where('date', now()->format('Y-m-d'))
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Anda sudah melakukan absensi hari ini.'], 422);
        }

        try {
            \App\Models\StudentAttendance::create([
                'student_id' => $student->id,
                'academic_year_id' => $student->academic_year_id,
                'class_group_id' => $student->student_class_group_id,
                'date' => now()->format('Y-m-d'),
                'time' => now()->format('H:i:s'),
                'status' => 'present',
                'notes' => 'Absensi Mandiri Dashboard',
            ]);

            return response()->json(['message' => 'Absensi berhasil! Selamat belajar.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal melakukan absensi: ' . $e->getMessage()], 500);
        }
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

            DB::commit();

            return redirect()->route('ppdb.dashboard')->with('success', 'Data identitas berhasil disimpan! Silakan lengkapi berkas persyaratan di bawah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Upload Berkas Satu-per-satu via AJAX
     */
    public function uploadDocument(Request $request)
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant;

        if (!$registrant) {
            return response()->json(['message' => 'Silakan lengkapi data identitas terlebih dahulu.'], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'type' => 'required|string'
        ]);

        try {
            $type = $request->type;
            $name = PpdbRegistrant::DOCUMENT_TYPES[$type] ?? $type;
            
            // Hapus file lama jika ada
            $oldDoc = PpdbDocument::where('ppdb_registrant_id', $registrant->id)
                ->where('document_type', $type)
                ->first();
                
            if ($oldDoc) {
                Storage::disk('public')->delete($oldDoc->file_path);
                $oldDoc->delete();
            }

            // Simpan file baru
            $path = $request->file('file')->store('ppdb/documents/' . $registrant->id, 'public');
            
            PpdbDocument::create([
                'ppdb_registrant_id' => $registrant->id,
                'document_name' => $name,
                'document_type' => $type,
                'file_path' => $path,
            ]);

            return response()->json([
                'message' => $name . ' berhasil diunggah.',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengunggah: ' . $e->getMessage()], 500);
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
    public function confirmReRegistration(Request $request)
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant;

        if (!$registrant || $registrant->status !== 'diterima') {
            return redirect()->back()->with('error', 'Hanya siswa berstatus DITERIMA yang dapat melakukan daftar ulang.');
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:transfer,tunai,midtrans',
            'payment_proof' => 'required_if:payment_method,transfer|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Get active academic year payment items
        $academicYear = AcademicYear::where('admission_semester', 1)->first();
        $paymentItems = PpdbPaymentItem::where('academic_year_id', $academicYear->id)
            ->where('is_active', true)
            ->get();
        $totalAmount = $paymentItems->sum('amount');

        try {
            if ($request->payment_method === 'transfer') {
                if ($request->hasFile('payment_proof')) {
                    $path = $request->file('payment_proof')->store('ppdb/payments', 'public');
                    $registrant->update([
                        'payment_method' => 'transfer',
                        'payment_proof' => $path,
                        'payment_amount' => $totalAmount,
                        'payment_status' => 'pending',
                        'confirmed_at' => now(),
                        'status' => 'daftar_ulang'
                    ]);
                }
                return redirect()->route('ppdb.dashboard')->with('success', 'Konfirmasi daftar ulang berhasil terkirim. Admin akan memverifikasi pembayaran Anda.');
            } 
            elseif ($request->payment_method === 'tunai') {
                $registrant->update([
                    'payment_method' => 'tunai',
                    'payment_amount' => $totalAmount,
                    'payment_status' => 'pending',
                    'confirmed_at' => now(),
                    'status' => 'daftar_ulang'
                ]);
                return redirect()->route('ppdb.dashboard')->with('success', 'Berhasil! Silakan lakukan pembayaran tunai di sekolah untuk menyelesaikan verifikasi.');
            }
            elseif ($request->payment_method === 'midtrans') {
                $setting = \App\Models\Setting::first();
                if (!$setting || !$setting->midtrans_server_key) {
                    return redirect()->back()->with('error', 'Gateway pembayaran Midtrans belum dikonfigurasi oleh admin.');
                }

                \Midtrans\Config::$serverKey = $setting->midtrans_server_key;
                \Midtrans\Config::$isProduction = $setting->midtrans_is_production;
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $orderId = 'PPDB-' . $registrant->id . '-' . time();
                
                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $totalAmount,
                    ],
                    'customer_details' => [
                        'first_name' => $registrant->nama_lengkap,
                        'email' => $user->email,
                        'phone' => $registrant->no_hp_ortu,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $registrant->update([
                    'payment_method' => 'midtrans',
                    'payment_amount' => $totalAmount,
                    'midtrans_order_id' => $orderId,
                    'midtrans_snap_token' => $snapToken,
                    'payment_status' => 'unpaid',
                    // status doesn't change to daftar_ulang until paid or pending through midtrans
                ]);

                return redirect()->route('ppdb.dashboard')->with('success', 'Silakan selesaikan pembayaran melalui popup Midtrans.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Cetak Bukti Daftar Ulang.
     */
    public function printReRegistration()
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant()->with(['admissionPhase', 'admissionType'])->firstOrFail();

        // Cek apakah sudah diverifikasi daftar ulangnya
        if ($registrant->status !== 'daftar_ulang_terverifikasi' && $registrant->status !== 'sudah_masuk_siswa') {
            abort(403, 'Bukti daftar ulang belum tersedia. Pastikan pembayaran Anda sudah diverifikasi oleh panitia.');
        }

        $source = \App\Models\MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ppdb.pdf.re_registration_proof', compact('registrant', 'source', 'admission'));
        $pdf->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->download('Bukti_Daftar_Ulang_' . $registrant->registration_number . '.pdf');
    }

    /**
     * Cetak Kwitansi Pembayaran / Bukti Bayar.
     */
    public function printPayment()
    {
        $user = Auth::user();
        $registrant = $user->ppdbRegistrant()->with(['admissionPhase', 'admissionType'])->firstOrFail();

        // Cek apakah sudah upload bukti bayar
        if (!$registrant->payment_proof) {
            abort(403, 'Anda belum mengunggah bukti pembayaran.');
        }

        $source = \App\Models\MailSetting::first();
        $admission = StudentAdmission::find($registrant->student_admission_id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ppdb.pdf.payment_receipt', compact('registrant', 'source', 'admission'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Kwitansi_PPDB_' . $registrant->registration_number . '.pdf');
    }
}
