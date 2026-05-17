<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index()
    {
        $semester1 = [
            [
                'title' => 'Konfigurasi Tahun Ajaran',
                'description' => 'Pastikan Tahun Akademik dan Semester aktif sudah diatur dengan benar.',
                'route' => 'academic-years.index',
                'icon' => 'fas fa-calendar-alt',
                'color' => 'primary'
            ],
            [
                'title' => 'Manajemen SDM (Guru & Staf)',
                'description' => 'Update data guru, staf, dan penempatan jabatan struktural.',
                'route' => 'teachers.index',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'success'
            ],
            [
                'title' => 'Pengaturan Rombongan Belajar',
                'description' => 'Buat rombel baru dan tentukan wali kelas untuk setiap kelas.',
                'route' => 'class-groups.index',
                'icon' => 'fas fa-users-class',
                'color' => 'info'
            ],
            [
                'title' => 'Proses PPDB & Penempatan',
                'description' => 'Selesaikan verifikasi pendaftar baru dan tempatkan ke rombel kelas 1.',
                'route' => 'ppdb.index',
                'icon' => 'fas fa-user-plus',
                'color' => 'warning'
            ],
            [
                'title' => 'Kurikulum & Jadwal',
                'description' => 'Atur mata pelajaran, jam pelajaran, dan susun jadwal mingguan.',
                'route' => 'class-schedules.index',
                'icon' => 'fas fa-clock',
                'color' => 'danger'
            ],
            [
                'title' => 'Input Penilaian Semester 1',
                'description' => 'Input nilai harian, PTS, dan PAS Ganjil untuk pengisian rapor.',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-file-invoice',
                'color' => 'secondary'
            ],
            [
                'title' => 'Cetak Rapor Semester 1',
                'description' => 'Finalisasi nilai dan cetak buku rapor semester ganjil.',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-print',
                'color' => 'dark'
            ],
        ];

        $semester2 = [
            [
                'title' => 'Aktivasi Semester Genap',
                'description' => 'Ganti status semester aktif menjadi Semester Genap.',
                'route' => 'academic-years.index',
                'icon' => 'fas fa-toggle-on',
                'color' => 'primary'
            ],
            [
                'title' => 'Penyesuaian Jadwal (Opsional)',
                'description' => 'Lakukan update jadwal pelajaran jika ada perubahan di semester 2.',
                'route' => 'class-schedules.index',
                'icon' => 'fas fa-sync-alt',
                'color' => 'info'
            ],
            [
                'title' => 'Administrasi Harian Genap',
                'description' => 'Lanjutkan monitoring absensi dan jurnal mengajar guru.',
                'route' => 'admin.teaching-journals.index',
                'icon' => 'fas fa-book-open',
                'color' => 'success'
            ],
            [
                'title' => 'Persiapan Ujian Madrasah',
                'description' => 'Input nilai Ujian Madrasah untuk siswa kelas akhir (Kelas 6).',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-graduation-cap',
                'color' => 'danger'
            ],
            [
                'title' => 'Kenaikan Kelas & Kelulusan',
                'description' => 'Proses kenaikan kelas untuk kelas 1-5 dan kelulusan kelas 6.',
                'route' => 'promotions.index',
                'icon' => 'fas fa-user-graduate',
                'color' => 'warning'
            ],
            [
                'title' => 'Cetak Rapor Semester 2',
                'description' => 'Finalisasi nilai akhir tahun dan cetak rapor semester genap.',
                'route' => 'student-grades.raport',
                'icon' => 'fas fa-file-pdf',
                'color' => 'secondary'
            ],
        ];

        return view('admin.workflow.index', compact('semester1', 'semester2'));
    }

    public function activate(Request $request)
    {
        $setting = \App\Models\Setting::first();
        if ($setting) {
            $setting->update([
                'is_workflow_pro_active' => true
            ]);

            // Record client payment simulation
            $invoice = 'INV/' . date('Ymd') . '/PRO/' . rand(10000, 99999);
            \App\Models\LicenseTransaction::create([
                'invoice_no' => $invoice,
                'module_name' => 'Peta Jalan Admin (Workflow)',
                'amount' => $setting->workflow_price ?? 99000,
                'status' => 'SUCCESS'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Modul Peta Jalan Admin Premium Berhasil Diaktifkan!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengaktifkan modul.'
        ], 400);
    }

    public function reset(Request $request)
    {
        $setting = \App\Models\Setting::first();
        if ($setting) {
            $setting->update([
                'is_workflow_pro_active' => false
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Modul Peta Jalan Admin Berhasil Dikunci Kembali untuk Simulasi!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunci modul.'
        ], 400);
    }

    public function upgradePage($module)
    {
        $setting = \App\Models\Setting::first();
        $modules = [
            'workflow' => [
                'key' => 'is_workflow_pro_active',
                'name' => 'Peta Jalan Admin (Workflow)',
                'icon' => 'fas fa-map-marked-alt',
                'description' => 'Akses fitur pemetaan langkah kerja awal admin madrasah, panduan konfigurasi tahun pelajaran, rombel, dan kelulusan siswa secara terstruktur.',
                'redirect_route' => 'admin.workflow'
            ],
            'announcements' => [
                'key' => 'is_announcements_pro_active',
                'name' => 'Pengumuman Madrasah',
                'icon' => 'fas fa-bullhorn',
                'description' => 'Akses fitur pembuatan, penayangan, dan broadcast informasi penting madrasah untuk seluruh guru, siswa, dan wali murid.',
                'redirect_route' => 'announcements.admin'
            ],
            'teachers' => [
                'key' => 'is_teachers_pro_active',
                'name' => 'Guru & Kepegawaian (PKG)',
                'icon' => 'fas fa-users',
                'description' => 'Akses fitur manajemen kinerja guru, penilaian PKG (Penilaian Kinerja Guru), pembuatan indikator performa staf, serta monitoring evaluasi terintegrasi.',
                'redirect_route' => 'teachers.index'
            ],
            'students' => [
                'key' => 'is_students_pro_active',
                'name' => 'Manajemen Siswa',
                'icon' => 'fas fa-user-graduate',
                'description' => 'Akses pengelolaan berkas siswa aktif, status kesiswaan, plotting pembagian rombongan belajar, pengelolaan riwayat mutasi/pindah sekolah, dan daftar arsip alumni madrasah.',
                'redirect_route' => 'students.index'
            ],
            'curriculum' => [
                'key' => 'is_curriculum_pro_active',
                'name' => 'Kurikulum & Jadwal Kelas',
                'icon' => 'fas fa-school',
                'description' => 'Akses pembuatan kurikulum terstruktur, alokasi jam pelajaran, pengaturan jadwal mingguan guru & kelas, serta kelulusan & kenaikan kelas otomatis.',
                'redirect_route' => 'class-groups.index'
            ],
            'achievements' => [
                'key' => 'is_achievements_pro_active',
                'name' => 'Pembiasaan & Prestasi Siswa',
                'icon' => 'fas fa-star',
                'description' => 'Akses monitoring mutabaah harian siswa, hafalan tahfidz quran, pencatatan log perilaku, serta pengelolaan arsip prestasi kejuaraan siswa.',
                'redirect_route' => 'mutabaah-tahfidz.index'
            ],
            'cbt' => [
                'key' => 'is_cbt_pro_active',
                'name' => 'Ujian & Penilaian (CBT)',
                'icon' => 'fas fa-laptop-code',
                'description' => 'Akses pembuatan bank soal digital, penjadwalan ujian CBT daring, sinkronisasi sesi & gelombang ujian, koreksi esai otomatis, serta perolehan peringkat hasil ujian siswa.',
                'redirect_route' => 'admin.cbt.bank.index'
            ],
            'grades' => [
                'key' => 'is_grades_pro_active',
                'name' => 'Pengolahan Nilai & Rapor',
                'icon' => 'fas fa-file-invoice',
                'description' => 'Akses fitur pembagian bobot mapel kurikulum, rekapitulasi ujian manual, koreksi nilai essay CBT, dan pencetakan file PDF rapor semester digital.',
                'redirect_route' => 'grade-settings.index'
            ],
            'attendance' => [
                'key' => 'is_attendance_pro_active',
                'name' => 'Absensi & Presensi Wajah AI',
                'icon' => 'fas fa-clipboard-list',
                'description' => 'Akses presensi berbasis peta lokasi (geolocation), pencocokan wajah berbasis kecerdasan buatan (Face Recognition AI), pengajuan cuti & izin online, dan rekap mutlak bulanan.',
                'redirect_route' => 'teacher.attendance.dashboard'
            ],
            'mail' => [
                'key' => 'is_mail_pro_active',
                'name' => 'Layanan Surat & SPPD',
                'icon' => 'fas fa-file-alt',
                'description' => 'Akses penerbitan surat keterangan aktif siswa, surat penugasan dinas guru & SPPD, surat undangan rapat dinas, pencetakan amplop, dan kustomisasi kop surat madrasah.',
                'redirect_route' => 'duty-letters.index'
            ],
            'savings' => [
                'key' => 'is_savings_pro_active',
                'name' => 'Keuangan & Tabungan Siswa',
                'icon' => 'fas fa-wallet',
                'description' => 'Akses pengelolaan iuran bulanan SPP madrasah secara otomatis, rekapitulasi pembayaran siswa, serta buku tabungan nasabah digital terintegrasi.',
                'redirect_route' => 'admin.savings.index'
            ],
            'bos' => [
                'key' => 'is_bos_pro_active',
                'name' => 'Dana BOS & Payroll Guru',
                'icon' => 'fas fa-money-check-alt',
                'description' => 'Akses pembukuan Buku Kas Umum (BKU) BOS, perencanaan anggaran madrasah (RKAM), rincian komponen pengeluaran, serta penggajian slip guru honorer & staf bulanan.',
                'redirect_route' => 'admin.bos.index'
            ],
            'ppdb' => [
                'key' => 'is_ppdb_pro_active',
                'name' => 'PPDB Online',
                'icon' => 'fas fa-user-plus',
                'description' => 'Akses portal penerimaan peserta didik baru madrasah secara mandiri, unggah & verifikasi berkas digital, pemindai barcode, live chat pendaftar, dan pengaturan jalur masuk.',
                'redirect_route' => 'ppdb.admin_dashboard'
            ],
            'website' => [
                'key' => 'is_website_pro_active',
                'name' => 'Website Madrasah',
                'icon' => 'fas fa-globe',
                'description' => 'Akses pengelolaan portal media & branding madrasah: posting artikel/berita, halaman statis profil, galeri foto prestasi, manajemen struktur menu, FAQ dinamis, dan sambutan kepala.',
                'redirect_route' => 'posts.index'
            ],
            'wa_gateway' => [
                'key' => 'is_wa_gateway_pro_active',
                'name' => 'WhatsApp Gateway Broadcast',
                'icon' => 'fab fa-whatsapp',
                'description' => 'Akses integrasi WhatsApp API untuk mengirimkan notifikasi nilai, presensi real-time siswa, tagihan iuran bulanan sekolah secara otomatis langsung ke ponsel wali murid.',
                'redirect_route' => 'admin.wa-gateway.index'
            ],
            'users' => [
                'key' => 'is_users_pro_active',
                'name' => 'Hak Akses & User Management',
                'icon' => 'fas fa-user-shield',
                'description' => 'Akses kontrol pembagian akun, pengaturan role multi-tingkat (Admin, Kamad, Guru, Staff, Keuangan), serta pembagian hak akses fitur terperinci.',
                'redirect_route' => 'users.index'
            ],
            'system' => [
                'key' => 'is_system_pro_active',
                'name' => 'Analisis, EMIS & Pengaturan',
                'icon' => 'fas fa-cogs',
                'description' => 'Akses panel visual statistik performa madrasah, sinkronisasi terpadu EMIS Kemenag, pengelolaan file cadangan database (Backup & Restore), serta reset data massal.',
                'redirect_route' => 'setting.index'
            ],
        ];

        if (!array_key_exists($module, $modules)) {
            abort(404);
        }

        $defaultPrices = [
            'workflow' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'announcements' => ['lifetime' => 49000, 'monthly' => 19000, 'yearly' => 39000],
            'teachers' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'students' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'curriculum' => ['lifetime' => 119000, 'monthly' => 39000, 'yearly' => 99000],
            'achievements' => ['lifetime' => 79000, 'monthly' => 25000, 'yearly' => 59000],
            'cbt' => ['lifetime' => 149000, 'monthly' => 49000, 'yearly' => 119000],
            'grades' => ['lifetime' => 129000, 'monthly' => 39000, 'yearly' => 99000],
            'attendance' => ['lifetime' => 149000, 'monthly' => 49000, 'yearly' => 119000],
            'mail' => ['lifetime' => 89000, 'monthly' => 29000, 'yearly' => 69000],
            'savings' => ['lifetime' => 129000, 'monthly' => 39000, 'yearly' => 99000],
            'bos' => ['lifetime' => 139000, 'monthly' => 39000, 'yearly' => 109000],
            'ppdb' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'website' => ['lifetime' => 79000, 'monthly' => 25000, 'yearly' => 59000],
            'wa_gateway' => ['lifetime' => 199000, 'monthly' => 59000, 'yearly' => 149000],
            'users' => ['lifetime' => 69000, 'monthly' => 19000, 'yearly' => 49000],
            'system' => ['lifetime' => 149000, 'monthly' => 49000, 'yearly' => 119000],
        ];

        $moduleData = $modules[$module];

        // Check if the module is already active, if so redirect to its direct dashboard/route
        $moduleKey = $moduleData['key'];
        if ($setting && $setting->{$moduleKey}) {
            if (\Route::has($moduleData['redirect_route'])) {
                return redirect()->route($moduleData['redirect_route'])
                    ->with('success', 'Modul ' . $moduleData['name'] . ' sudah aktif!');
            }
            return redirect()->route('admin.dashboard')
                ->with('success', 'Modul ' . $moduleData['name'] . ' sudah aktif!');
        }

        $priceDefaults = $defaultPrices[$module] ?? ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000];

        $moduleData['price'] = $setting->{$module . '_price'} ?? $priceDefaults['lifetime'];
        $moduleData['price_monthly'] = $setting->{$module . '_price_monthly'} ?? $priceDefaults['monthly'];
        $moduleData['price_yearly'] = $setting->{$module . '_price_yearly'} ?? $priceDefaults['yearly'];

        // Get PENDING or REJECTED manual checkout transaction for this module
        $pendingTransaction = \App\Models\LicenseTransaction::where('module_key', $module)
            ->where('status', 'PENDING')
            ->first();

        $rejectedTransaction = \App\Models\LicenseTransaction::where('module_key', $module)
            ->where('status', 'REJECTED')
            ->first();

        return view('admin.workflow.upgrade', compact('module', 'moduleData', 'pendingTransaction', 'rejectedTransaction', 'setting'));
    }

    public function submitCheckout(Request $request, $module)
    {
        $request->validate([
            'duration' => 'required|in:30,365,lifetime',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'required|string',
            'transfer_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        $setting = \App\Models\Setting::first();
        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Konfigurasi sistem tidak ditemukan.'], 404);
        }

        // Resolve pricing based on duration
        $defaultPrices = [
            'workflow' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'announcements' => ['lifetime' => 49000, 'monthly' => 19000, 'yearly' => 39000],
            'teachers' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'students' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'curriculum' => ['lifetime' => 119000, 'monthly' => 39000, 'yearly' => 99000],
            'achievements' => ['lifetime' => 79000, 'monthly' => 25000, 'yearly' => 59000],
            'cbt' => ['lifetime' => 149000, 'monthly' => 49000, 'yearly' => 119000],
            'grades' => ['lifetime' => 129000, 'monthly' => 39000, 'yearly' => 99000],
            'attendance' => ['lifetime' => 149000, 'monthly' => 49000, 'yearly' => 119000],
            'mail' => ['lifetime' => 89000, 'monthly' => 29000, 'yearly' => 69000],
            'savings' => ['lifetime' => 129000, 'monthly' => 39000, 'yearly' => 99000],
            'bos' => ['lifetime' => 139000, 'monthly' => 39000, 'yearly' => 109000],
            'ppdb' => ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000],
            'website' => ['lifetime' => 79000, 'monthly' => 25000, 'yearly' => 59000],
            'wa_gateway' => ['lifetime' => 199000, 'monthly' => 59000, 'yearly' => 149000],
            'users' => ['lifetime' => 69000, 'monthly' => 19000, 'yearly' => 49000],
            'system' => ['lifetime' => 149000, 'monthly' => 49000, 'yearly' => 119000],
        ];

        $priceDefaults = $defaultPrices[$module] ?? ['lifetime' => 99000, 'monthly' => 29000, 'yearly' => 79000];

        if ($request->duration === '30') {
            $originalPrice = $setting->{$module . '_price_monthly'} ?? $priceDefaults['monthly'];
        } elseif ($request->duration === '365') {
            $originalPrice = $setting->{$module . '_price_yearly'} ?? $priceDefaults['yearly'];
        } else {
            $originalPrice = $setting->{$module . '_price'} ?? $priceDefaults['lifetime'];
        }

        $discountAmount = 0;
        $couponCodeUsed = null;

        // Apply coupon if given
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid()) {
                $discountAmount = $coupon->calculateDiscount($originalPrice);
                $couponCodeUsed = $coupon->code;
                
                // Track coupon usage
                $coupon->increment('used_count');
            }
        }

        $finalAmount = max(0, $originalPrice - $discountAmount);

        // Upload Transfer Proof securely
        if ($request->hasFile('transfer_proof')) {
            $file = $request->file('transfer_proof');
            $filename = 'proof_' . $module . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Create target folder if not exists
            if (!file_exists(public_path('uploads/transfer_proofs'))) {
                mkdir(public_path('uploads/transfer_proofs'), 0777, true);
            }
            
            $file->move(public_path('uploads/transfer_proofs'), $filename);
            $transferProofPath = 'uploads/transfer_proofs/' . $filename;
        } else {
            return response()->json(['success' => false, 'message' => 'Bukti transfer wajib diunggah.'], 400);
        }

        // Delete previous pending or rejected attempts to avoid clutter
        \App\Models\LicenseTransaction::where('module_key', $module)
            ->whereIn('status', ['PENDING', 'REJECTED'])
            ->delete();

        // Create transaction
        $invoice = 'INV/' . date('Ymd') . '/PRO/' . rand(10000, 99999);
        
        $modulesList = [
            'workflow' => 'Peta Jalan Admin (Workflow)',
            'announcements' => 'Pengumuman Madrasah',
            'teachers' => 'Guru & Kepegawaian (PKG)',
            'students' => 'Manajemen Siswa',
            'curriculum' => 'Kurikulum & Jadwal Kelas',
            'achievements' => 'Pembiasaan & Prestasi Siswa',
            'cbt' => 'Ujian & Penilaian (CBT)',
            'grades' => 'Pengolahan Nilai & Rapor',
            'attendance' => 'Absensi & Presensi Wajah AI',
            'mail' => 'Layanan Surat & SPPD',
            'savings' => 'Keuangan & Tabungan Siswa',
            'bos' => 'Dana BOS & Payroll Guru',
            'ppdb' => 'PPDB Online',
            'website' => 'Website Madrasah',
            'wa_gateway' => 'WhatsApp Gateway Broadcast',
            'users' => 'Hak Akses & User Management',
            'system' => 'Analisis, EMIS & Pengaturan',
        ];
        $moduleName = $modulesList[$module] ?? ucfirst($module);

        \App\Models\LicenseTransaction::create([
            'invoice_no' => $invoice,
            'module_name' => $moduleName,
            'module_key' => $module,
            'amount' => $finalAmount,
            'coupon_code' => $couponCodeUsed,
            'discount_amount' => $discountAmount,
            'duration' => $request->duration,
            'transfer_proof' => $transferProofPath,
            'payment_method' => $request->payment_method,
            'status' => 'PENDING',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti transfer berhasil diunggah! Permintaan aktivasi modul sedang diproses oleh Pemilik.'
        ]);
    }

    public function activateModule($module)
    {
        $setting = \App\Models\Setting::first();
        $modules = [
            'workflow' => ['key' => 'is_workflow_pro_active', 'name' => 'Peta Jalan Admin (Workflow)', 'price' => $setting->workflow_price ?? 99000],
            'announcements' => ['key' => 'is_announcements_pro_active', 'name' => 'Pengumuman Madrasah', 'price' => $setting->announcements_price ?? 49000],
            'teachers' => ['key' => 'is_teachers_pro_active', 'name' => 'Guru & Kepegawaian (PKG)', 'price' => $setting->teachers_price ?? 99000],
            'students' => ['key' => 'is_students_pro_active', 'name' => 'Manajemen Siswa', 'price' => $setting->students_price ?? 99000],
            'curriculum' => ['key' => 'is_curriculum_pro_active', 'name' => 'Kurikulum & Jadwal Kelas', 'price' => $setting->curriculum_price ?? 119000],
            'achievements' => ['key' => 'is_achievements_pro_active', 'name' => 'Pembiasaan & Prestasi Siswa', 'price' => $setting->achievements_price ?? 79000],
            'cbt' => ['key' => 'is_cbt_pro_active', 'name' => 'Ujian & Penilaian (CBT)', 'price' => $setting->cbt_price ?? 149000],
            'grades' => ['key' => 'is_grades_pro_active', 'name' => 'Pengolahan Nilai & Rapor', 'price' => $setting->grades_price ?? 129000],
            'attendance' => ['key' => 'is_attendance_pro_active', 'name' => 'Absensi & Presensi Wajah AI', 'price' => $setting->attendance_price ?? 149000],
            'mail' => ['key' => 'is_mail_pro_active', 'name' => 'Layanan Surat & SPPD', 'price' => $setting->mail_price ?? 89000],
            'savings' => ['key' => 'is_savings_pro_active', 'name' => 'Keuangan & Tabungan Siswa', 'price' => $setting->savings_price ?? 129000],
            'bos' => ['key' => 'is_bos_pro_active', 'name' => 'Dana BOS & Payroll Guru', 'price' => $setting->bos_price ?? 139000],
            'ppdb' => ['key' => 'is_ppdb_pro_active', 'name' => 'PPDB Online', 'price' => $setting->ppdb_price ?? 99000],
            'website' => ['key' => 'is_website_pro_active', 'name' => 'Website Madrasah', 'price' => $setting->website_price ?? 79000],
            'wa_gateway' => ['key' => 'is_wa_gateway_pro_active', 'name' => 'WhatsApp Gateway Broadcast', 'price' => $setting->wa_gateway_price ?? 199000],
            'users' => ['key' => 'is_users_pro_active', 'name' => 'Hak Akses & User Management', 'price' => $setting->users_price ?? 69000],
            'system' => ['key' => 'is_system_pro_active', 'name' => 'Analisis, EMIS & Pengaturan', 'price' => $setting->system_price ?? 149000],
        ];

        if (!array_key_exists($module, $modules)) {
            return response()->json(['success' => false, 'message' => 'Modul tidak ditemukan.'], 404);
        }

        if ($setting) {
            $data = $modules[$module];
            
            // For developers instant simulation: default to Lifetime active
            $setting->update([
                $data['key'] => true,
                $module . '_expires_at' => null // lifetime
            ]);

            // Clear any pending/rejected checkout
            \App\Models\LicenseTransaction::where('module_key', $module)
                ->whereIn('status', ['PENDING', 'REJECTED'])
                ->delete();

            // Record transaction
            $invoice = 'INV/' . date('Ymd') . '/PRO/' . rand(10000, 99999);
            \App\Models\LicenseTransaction::create([
                'invoice_no' => $invoice,
                'module_name' => $data['name'],
                'module_key' => $module,
                'amount' => $data['price'],
                'duration' => 'lifetime',
                'status' => 'SUCCESS'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Modul ' . $data['name'] . ' Berhasil Diaktifkan secara Instan (Simulasi Lifetime)!'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Setting tidak ditemukan.'], 404);
    }
}
