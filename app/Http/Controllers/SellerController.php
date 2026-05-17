<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\LicenseTransaction;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Coupon;

class SellerController extends Controller
{
    private function checkAuth()
    {
        return session()->has('seller_logged_in');
    }

    public function loginForm()
    {
        if ($this->checkAuth()) {
            return redirect()->route('seller.dashboard');
        }
        return view('seller.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'passcode' => 'required'
        ]);

        // Secret Developer / Seller Credentials
        if ($request->email === 'developer@madrasah.digital' && $request->passcode === 'dev12345') {
            session(['seller_logged_in' => true]);
            return redirect()->route('seller.dashboard')->with('success', 'Selamat datang di Panel Developer Madrasah Digital!');
        }

        return back()->withErrors(['message' => 'Email atau Master Passcode Developer salah!'])->withInput();
    }

    public function logout()
    {
        session()->forget('seller_logged_in');
        return redirect()->route('seller.login')->with('success', 'Berhasil keluar dari Panel Developer.');
    }

    public function dashboard()
    {
        if (!$this->checkAuth()) {
            return redirect()->route('seller.login');
        }

        $setting = Setting::first();
        $transactions = LicenseTransaction::latest()->get();
        
        // Dynamic client statistics
        $stats = [
            'total_teachers' => Teacher::count(),
            'total_students' => Student::count(),
            'school_name' => $setting->company_name ?? 'MTs Bustanul Huda',
            'city' => $setting->city ?? 'Tegal'
        ];

        // Modules configuration map
        $modulesMap = [
            'workflow' => ['key' => 'is_workflow_pro_active', 'name' => 'Peta Jalan Admin (Workflow)'],
            'announcements' => ['key' => 'is_announcements_pro_active', 'name' => 'Pengumuman Madrasah'],
            'teachers' => ['key' => 'is_teachers_pro_active', 'name' => 'Guru & Kepegawaian (PKG)'],
            'students' => ['key' => 'is_students_pro_active', 'name' => 'Manajemen Siswa'],
            'curriculum' => ['key' => 'is_curriculum_pro_active', 'name' => 'Kurikulum & Jadwal Kelas'],
            'achievements' => ['key' => 'is_achievements_pro_active', 'name' => 'Pembiasaan & Prestasi Siswa'],
            'cbt' => ['key' => 'is_cbt_pro_active', 'name' => 'Ujian & Penilaian (CBT)'],
            'grades' => ['key' => 'is_grades_pro_active', 'name' => 'Pengolahan Nilai & Rapor'],
            'attendance' => ['key' => 'is_attendance_pro_active', 'name' => 'Absensi & Presensi Wajah AI'],
            'mail' => ['key' => 'is_mail_pro_active', 'name' => 'Layanan Surat & SPPD'],
            'savings' => ['key' => 'is_savings_pro_active', 'name' => 'Keuangan & Tabungan Siswa'],
            'bos' => ['key' => 'is_bos_pro_active', 'name' => 'Dana BOS & Payroll Guru'],
            'ppdb' => ['key' => 'is_ppdb_pro_active', 'name' => 'PPDB Online'],
            'website' => ['key' => 'is_website_pro_active', 'name' => 'Website Madrasah'],
            'wa_gateway' => ['key' => 'is_wa_gateway_pro_active', 'name' => 'WhatsApp Gateway Broadcast'],
            'users' => ['key' => 'is_users_pro_active', 'name' => 'Hak Akses & User Management'],
            'system' => ['key' => 'is_system_pro_active', 'name' => 'Analisis, EMIS & Pengaturan'],
        ];

        // Expiring soon modules
        $expiringSoon = [];
        if ($setting) {
            foreach ($modulesMap as $key => $mod) {
                $expiresColumn = $key . '_expires_at';
                $rawActive = $setting->getRawOriginal($mod['key']) ?? false;
                $expiry = $setting->$expiresColumn;
                
                if ($rawActive && !is_null($expiry)) {
                    $expiresDate = \Carbon\Carbon::parse($expiry);
                    $days = now()->diffInDays($expiresDate, false);
                    if ($days <= 30) {
                        $expiringSoon[] = [
                            'key' => $key,
                            'name' => $mod['name'],
                            'expires_at' => $expiresDate->format('d M Y'),
                            'days_left' => $days
                        ];
                    }
                }
            }
        }

        $coupons = Coupon::latest()->get();
        $pendingTransactions = LicenseTransaction::where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('seller.dashboard', compact('setting', 'transactions', 'stats', 'expiringSoon', 'coupons', 'pendingTransactions'));
    }

    public function toggleLicense(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $setting = Setting::first();
        $modules = [
            'workflow' => ['key' => 'is_workflow_pro_active', 'name' => 'Peta Jalan Admin (Workflow)', 'price' => $setting->workflow_price ?? 99000],
            'announcements' => ['key' => 'is_announcements_pro_active', 'name' => 'Pengumuman Madrasah', 'price' => $setting->announcements_price ?? 49000],
            'teachers' => ['key' => 'is_teachers_pro_active', 'name' => 'Guru & Kepegawaian (PKG)', 'price' => $setting->teachers_price ?? 99000],
            'students' => ['key' => 'is_students_pro_active', 'name' => 'Manajemen Siswa', 'price' => $setting->students_price ?? 99000],
            'curriculum' => ['key' => 'is_curriculum_pro_active', 'name' => 'Kurikulum & Jadwal Kelas', 'price' => $setting->curriculum_price ?? 119000],
            'achievements' => ['key' => 'is_achievements_pro_active', 'name' => 'Pembiasaan & Prestasi Siswa', 'price' => $setting->achievements_price ?? 79000],
            'cbt' => ['key' => 'is_cbt_pro_active', 'name' => 'Ujian & Penilaian (CBT)', 'price' => $setting->cbt_price ?? 149000],
            'grades' => ['key' => 'is_grades_pro_active', 'name' => 'Pengolahan Nilai & Rapor', 'price' => $setting->grades_price ?? 129000],
            'attendance' => ['key' => 'is_attendance_pro_active', 'name' => 'Absensi & Monitoring Wajah AI', 'price' => $setting->attendance_price ?? 149000],
            'mail' => ['key' => 'is_mail_pro_active', 'name' => 'Layanan Surat & SPPD', 'price' => $setting->mail_price ?? 89000],
            'savings' => ['key' => 'is_savings_pro_active', 'name' => 'Keuangan & Tabungan Siswa', 'price' => $setting->savings_price ?? 129000],
            'bos' => ['key' => 'is_bos_pro_active', 'name' => 'Dana BOS & Payroll Guru', 'price' => $setting->bos_price ?? 139000],
            'ppdb' => ['key' => 'is_ppdb_pro_active', 'name' => 'PPDB Online', 'price' => $setting->ppdb_price ?? 99000],
            'website' => ['key' => 'is_website_pro_active', 'name' => 'Website Madrasah', 'price' => $setting->website_price ?? 79000],
            'wa_gateway' => ['key' => 'is_wa_gateway_pro_active', 'name' => 'WhatsApp Gateway Broadcast', 'price' => $setting->wa_gateway_price ?? 199000],
            'users' => ['key' => 'is_users_pro_active', 'name' => 'Hak Akses & User Management', 'price' => $setting->users_price ?? 69000],
            'system' => ['key' => 'is_system_pro_active', 'name' => 'Analisis, EMIS & Pengaturan', 'price' => $setting->system_price ?? 149000],
        ];

        $moduleKey = $request->input('module', 'workflow');
        if (!array_key_exists($moduleKey, $modules)) {
            return response()->json(['success' => false, 'message' => 'Modul tidak ditemukan.'], 404);
        }

        $module = $modules[$moduleKey];
        if ($setting) {
            $column = $module['key'];
            $expiresColumn = $moduleKey . '_expires_at';
            $current = $setting->$column;
            
            $setting->update([
                $column => !$current,
                $expiresColumn => null // Quick toggles reset expiration to null (Lifetime)
            ]);

            return response()->json([
                'success' => true,
                'is_active' => !$current,
                'message' => 'Lisensi ' . $module['name'] . ' berhasil ' . (!$current ? 'Diaktifkan (Lifetime)' : 'Dinonaktifkan') . '!'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Setting not found'], 404);
    }

    public function simulatePayment(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $setting = Setting::first();
        $modules = [
            'workflow' => ['key' => 'is_workflow_pro_active', 'name' => 'Peta Jalan Admin (Workflow)', 'price' => $setting->workflow_price ?? 99000],
            'announcements' => ['key' => 'is_announcements_pro_active', 'name' => 'Pengumuman Madrasah', 'price' => $setting->announcements_price ?? 49000],
            'teachers' => ['key' => 'is_teachers_pro_active', 'name' => 'Guru & Kepegawaian (PKG)', 'price' => $setting->teachers_price ?? 99000],
            'students' => ['key' => 'is_students_pro_active', 'name' => 'Manajemen Siswa', 'price' => $setting->students_price ?? 99000],
            'curriculum' => ['key' => 'is_curriculum_pro_active', 'name' => 'Kurikulum & Jadwal Kelas', 'price' => $setting->curriculum_price ?? 119000],
            'achievements' => ['key' => 'is_achievements_pro_active', 'name' => 'Pembiasaan & Prestasi Siswa', 'price' => $setting->achievements_price ?? 79000],
            'cbt' => ['key' => 'is_cbt_pro_active', 'name' => 'Ujian & Penilaian (CBT)', 'price' => $setting->cbt_price ?? 149000],
            'grades' => ['key' => 'is_grades_pro_active', 'name' => 'Pengolahan Nilai & Rapor', 'price' => $setting->grades_price ?? 129000],
            'attendance' => ['key' => 'is_attendance_pro_active', 'name' => 'Absensi & Monitoring Wajah AI', 'price' => $setting->attendance_price ?? 149000],
            'mail' => ['key' => 'is_mail_pro_active', 'name' => 'Layanan Surat & SPPD', 'price' => $setting->mail_price ?? 89000],
            'savings' => ['key' => 'is_savings_pro_active', 'name' => 'Keuangan & Tabungan Siswa', 'price' => $setting->savings_price ?? 129000],
            'bos' => ['key' => 'is_bos_pro_active', 'name' => 'Dana BOS & Payroll Guru', 'price' => $setting->bos_price ?? 139000],
            'ppdb' => ['key' => 'is_ppdb_pro_active', 'name' => 'PPDB Online', 'price' => $setting->ppdb_price ?? 99000],
            'website' => ['key' => 'is_website_pro_active', 'name' => 'Website Madrasah', 'price' => $setting->website_price ?? 79000],
            'wa_gateway' => ['key' => 'is_wa_gateway_pro_active', 'name' => 'WhatsApp Gateway Broadcast', 'price' => $setting->wa_gateway_price ?? 199000],
            'users' => ['key' => 'is_users_pro_active', 'name' => 'Hak Akses & User Management', 'price' => $setting->users_price ?? 69000],
            'system' => ['key' => 'is_system_pro_active', 'name' => 'Analisis, EMIS & Pengaturan', 'price' => $setting->system_price ?? 149000],
        ];

        $moduleKey = $request->input('module', 'workflow');
        if (!array_key_exists($moduleKey, $modules)) {
            return response()->json(['success' => false, 'message' => 'Modul tidak ditemukan.'], 404);
        }

        $module = $modules[$moduleKey];
        if ($setting) {
            $column = $module['key'];
            $expiresColumn = $moduleKey . '_expires_at';
            
            $duration = $request->input('duration', 'lifetime');
            $expiresAt = null;
            $durationLabel = 'Lifetime';
            
            if ($duration === '30') {
                $expiresAt = now()->addDays(30);
                $durationLabel = 'Bulanan (30 Hari)';
            } elseif ($duration === '365') {
                $expiresAt = now()->addDays(365);
                $durationLabel = 'Tahunan (365 Hari)';
            }
            
            $couponCode = $request->input('coupon_code');
            $discountAmount = 0;
            $finalPrice = $module['price'];

            if ($couponCode) {
                $coupon = Coupon::where('code', strtoupper(trim($couponCode)))->first();
                if ($coupon && $coupon->isValid()) {
                    $discountAmount = $coupon->calculateDiscount($finalPrice);
                    $finalPrice = max(0, $finalPrice - $discountAmount);
                    
                    // Increment coupon usage
                    $coupon->increment('used_count');
                }
            }

            $setting->update([
                $column => true,
                $expiresColumn => $expiresAt
            ]);

            // Record transaction
            $invoice = 'INV/' . date('Ymd') . '/PRO/' . rand(10000, 99999);
            LicenseTransaction::create([
                'invoice_no' => $invoice,
                'module_name' => $module['name'] . ' (' . $durationLabel . ')' . ($couponCode ? ' [Kupon: ' . strtoupper($couponCode) . ']' : ''),
                'amount' => $finalPrice,
                'coupon_code' => $couponCode ? strtoupper($couponCode) : null,
                'discount_amount' => $discountAmount,
                'status' => 'SUCCESS'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Simulasi pembelian ' . $module['name'] . ' (' . $durationLabel . ') berhasil dicatat dan lisensi diaktifkan!' . ($discountAmount > 0 ? ' Diskon kupon diterapkan: Rp ' . number_format($discountAmount, 0, ',', '.') : '')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Setting not found'], 404);
    }

    public function updatePrices(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'workflow_price' => 'required|numeric|min:0',
            'announcements_price' => 'required|numeric|min:0',
            'teachers_price' => 'required|numeric|min:0',
            'students_price' => 'required|numeric|min:0',
            'curriculum_price' => 'required|numeric|min:0',
            'achievements_price' => 'required|numeric|min:0',
            'cbt_price' => 'required|numeric|min:0',
            'grades_price' => 'required|numeric|min:0',
            'attendance_price' => 'required|numeric|min:0',
            'mail_price' => 'required|numeric|min:0',
            'savings_price' => 'required|numeric|min:0',
            'bos_price' => 'required|numeric|min:0',
            'ppdb_price' => 'required|numeric|min:0',
            'website_price' => 'required|numeric|min:0',
            'wa_gateway_price' => 'required|numeric|min:0',
            'users_price' => 'required|numeric|min:0',
            'system_price' => 'required|numeric|min:0',
        ]);

        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'workflow_price' => $request->workflow_price,
                'announcements_price' => $request->announcements_price,
                'teachers_price' => $request->teachers_price,
                'students_price' => $request->students_price,
                'curriculum_price' => $request->curriculum_price,
                'achievements_price' => $request->achievements_price,
                'cbt_price' => $request->cbt_price,
                'grades_price' => $request->grades_price,
                'attendance_price' => $request->attendance_price,
                'mail_price' => $request->mail_price,
                'savings_price' => $request->savings_price,
                'bos_price' => $request->bos_price,
                'ppdb_price' => $request->ppdb_price,
                'website_price' => $request->website_price,
                'wa_gateway_price' => $request->wa_gateway_price,
                'users_price' => $request->users_price,
                'system_price' => $request->system_price,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tarif harga lisensi premium berhasil diperbarui!'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Setting tidak ditemukan.'], 404);
    }

    public function quickAction(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $action = $request->input('action');
        $setting = Setting::first();

        if ($action === 'unlock_all') {
            if ($setting) {
                $setting->update([
                    'is_workflow_pro_active' => true,
                    'workflow_expires_at' => null,
                    'is_announcements_pro_active' => true,
                    'announcements_expires_at' => null,
                    'is_teachers_pro_active' => true,
                    'teachers_expires_at' => null,
                    'is_students_pro_active' => true,
                    'students_expires_at' => null,
                    'is_curriculum_pro_active' => true,
                    'curriculum_expires_at' => null,
                    'is_achievements_pro_active' => true,
                    'achievements_expires_at' => null,
                    'is_cbt_pro_active' => true,
                    'cbt_expires_at' => null,
                    'is_grades_pro_active' => true,
                    'grades_expires_at' => null,
                    'is_attendance_pro_active' => true,
                    'attendance_expires_at' => null,
                    'is_mail_pro_active' => true,
                    'mail_expires_at' => null,
                    'is_savings_pro_active' => true,
                    'savings_expires_at' => null,
                    'is_bos_pro_active' => true,
                    'bos_expires_at' => null,
                    'is_ppdb_pro_active' => true,
                    'ppdb_expires_at' => null,
                    'is_website_pro_active' => true,
                    'website_expires_at' => null,
                    'is_wa_gateway_pro_active' => true,
                    'wa_gateway_expires_at' => null,
                    'is_users_pro_active' => true,
                    'users_expires_at' => null,
                    'is_system_pro_active' => true,
                    'system_expires_at' => null,
                ]);

                // Record transaction
                LicenseTransaction::create([
                    'invoice_no' => 'INV/' . date('Ymd') . '/ALL/' . rand(10000, 99999),
                    'module_name' => 'Bundle Akses Semua Modul Premium',
                    'amount' => 0,
                    'status' => 'SUCCESS'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Semua modul premium berhasil diaktifkan seketika!'
                ]);
            }
        } elseif ($action === 'lock_all') {
            if ($setting) {
                $setting->update([
                    'is_workflow_pro_active' => false,
                    'workflow_expires_at' => null,
                    'is_announcements_pro_active' => false,
                    'announcements_expires_at' => null,
                    'is_teachers_pro_active' => false,
                    'teachers_expires_at' => null,
                    'is_students_pro_active' => false,
                    'students_expires_at' => null,
                    'is_curriculum_pro_active' => false,
                    'curriculum_expires_at' => null,
                    'is_achievements_pro_active' => false,
                    'achievements_expires_at' => null,
                    'is_cbt_pro_active' => false,
                    'cbt_expires_at' => null,
                    'is_grades_pro_active' => false,
                    'grades_expires_at' => null,
                    'is_attendance_pro_active' => false,
                    'attendance_expires_at' => null,
                    'is_mail_pro_active' => false,
                    'mail_expires_at' => null,
                    'is_savings_pro_active' => false,
                    'savings_expires_at' => null,
                    'is_bos_pro_active' => false,
                    'bos_expires_at' => null,
                    'is_ppdb_pro_active' => false,
                    'ppdb_expires_at' => null,
                    'is_website_pro_active' => false,
                    'website_expires_at' => null,
                    'is_wa_gateway_pro_active' => false,
                    'wa_gateway_expires_at' => null,
                    'is_users_pro_active' => false,
                    'users_expires_at' => null,
                    'is_system_pro_active' => false,
                    'system_expires_at' => null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Semua modul premium berhasil dikunci kembali!'
                ]);
            }
        } elseif ($action === 'clear_logs') {
            LicenseTransaction::query()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Seluruh riwayat transaksi lisensi berhasil dibersihkan!'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
    }

    public function recordManualPayment(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'module' => 'required',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'duration' => 'required|string',
            'coupon_code' => 'nullable|string'
        ]);

        $modules = [
            'workflow' => ['key' => 'is_workflow_pro_active', 'name' => 'Peta Jalan Admin (Workflow)'],
            'announcements' => ['key' => 'is_announcements_pro_active', 'name' => 'Pengumuman Madrasah'],
            'teachers' => ['key' => 'is_teachers_pro_active', 'name' => 'Guru & Kepegawaian (PKG)'],
            'students' => ['key' => 'is_students_pro_active', 'name' => 'Manajemen Siswa'],
            'curriculum' => ['key' => 'is_curriculum_pro_active', 'name' => 'Kurikulum & Jadwal Kelas'],
            'achievements' => ['key' => 'is_achievements_pro_active', 'name' => 'Pembiasaan & Prestasi Siswa'],
            'cbt' => ['key' => 'is_cbt_pro_active', 'name' => 'Ujian & Penilaian (CBT)'],
            'grades' => ['key' => 'is_grades_pro_active', 'name' => 'Pengolahan Nilai & Rapor'],
            'attendance' => ['key' => 'is_attendance_pro_active', 'name' => 'Absensi & Presensi Wajah AI'],
            'mail' => ['key' => 'is_mail_pro_active', 'name' => 'Layanan Surat & SPPD'],
            'savings' => ['key' => 'is_savings_pro_active', 'name' => 'Keuangan & Tabungan Siswa'],
            'bos' => ['key' => 'is_bos_pro_active', 'name' => 'Dana BOS & Payroll Guru'],
            'ppdb' => ['key' => 'is_ppdb_pro_active', 'name' => 'PPDB Online'],
            'website' => ['key' => 'is_website_pro_active', 'name' => 'Website Madrasah'],
            'wa_gateway' => ['key' => 'is_wa_gateway_pro_active', 'name' => 'WhatsApp Gateway Broadcast'],
            'users' => ['key' => 'is_users_pro_active', 'name' => 'Hak Akses & User Management'],
            'system' => ['key' => 'is_system_pro_active', 'name' => 'Analisis, EMIS & Pengaturan'],
        ];

        $moduleKey = $request->module;
        if (!array_key_exists($moduleKey, $modules)) {
            return response()->json(['success' => false, 'message' => 'Modul tidak ditemukan.'], 404);
        }

        $module = $modules[$moduleKey];
        $setting = Setting::first();

        if ($setting) {
            $column = $module['key'];
            $expiresColumn = $moduleKey . '_expires_at';
            
            $duration = $request->input('duration', 'lifetime');
            $expiresAt = null;
            $durationLabel = 'Lifetime';
            
            if ($duration === '30') {
                $expiresAt = now()->addDays(30);
                $durationLabel = 'Bulanan (30 Hari)';
            } elseif ($duration === '365') {
                $expiresAt = now()->addDays(365);
                $durationLabel = 'Tahunan (365 Hari)';
            }

            $couponCode = $request->input('coupon_code');
            $discountAmount = 0;
            $finalPrice = $request->amount;

            if ($couponCode) {
                $coupon = Coupon::where('code', strtoupper(trim($couponCode)))->first();
                if ($coupon && $coupon->isValid()) {
                    $discountAmount = $coupon->calculateDiscount($finalPrice);
                    $finalPrice = max(0, $finalPrice - $discountAmount);
                    
                    // Increment coupon usage
                    $coupon->increment('used_count');
                }
            }
            
            $setting->update([
                $column => true,
                $expiresColumn => $expiresAt
            ]);

            // Create offline transaction record
            $invoice = 'INV/' . date('Ymd') . '/MANUAL/' . rand(10000, 99999);
            LicenseTransaction::create([
                'invoice_no' => $invoice,
                'module_name' => $module['name'] . ' (' . $durationLabel . ' - Manual: ' . $request->payment_method . ')' . ($couponCode ? ' [Kupon: ' . strtoupper($couponCode) . ']' : ''),
                'amount' => $finalPrice,
                'coupon_code' => $couponCode ? strtoupper($couponCode) : null,
                'discount_amount' => $discountAmount,
                'status' => 'SUCCESS'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran manual untuk ' . $module['name'] . ' (' . $durationLabel . ') berhasil dicatat dan lisensi diaktifkan!' . ($discountAmount > 0 ? ' Diskon kupon diterapkan: Rp ' . number_format($discountAmount, 0, ',', '.') : '')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Setting tidak ditemukan.'], 404);
    }

    public function remoteDiagnostics()
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // 1. PHP Version & Laravel Version
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $osPlatform = PHP_OS_FAMILY . ' (' . PHP_OS . ')';

        // 2. Database Status
        $dbStatus = 'DISCONNECTED';
        $dbVersion = 'Unknown';
        try {
            $pdo = \DB::connection()->getPdo();
            $dbStatus = 'ACTIVE';
            $dbVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        } catch (\Exception $e) {
            $dbStatus = 'ERROR';
        }

        // 3. Disk Space Calculations
        $diskTotal = @disk_total_space(base_path()) ?: (100 * 1024 * 1024 * 1024);
        $diskFree = @disk_free_space(base_path()) ?: (60 * 1024 * 1024 * 1024);
        $diskUsed = $diskTotal - $diskFree;
        $diskPercent = ($diskTotal > 0) ? round(($diskUsed / $diskTotal) * 100, 1) : 0;

        // 4. RAM Calculations (OS specific)
        $ramTotal = 16 * 1024 * 1024 * 1024; // fallback 16GB
        $ramFree = 8 * 1024 * 1024 * 1024;  // fallback 8GB
        $ramUsed = $ramTotal - $ramFree;

        if (stristr(PHP_OS, 'WIN')) {
            // Windows RAM calculation via wmic
            @exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value', $output);
            if ($output) {
                $freeKb = 0;
                $totalKb = 0;
                foreach ($output as $line) {
                    if (strpos($line, 'FreePhysicalMemory') !== false) {
                        $freeKb = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT);
                    }
                    if (strpos($line, 'TotalVisibleMemorySize') !== false) {
                        $totalKb = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT);
                    }
                }
                if ($totalKb > 0 && $freeKb > 0) {
                    $ramTotal = $totalKb * 1024;
                    $ramFree = $freeKb * 1024;
                    $ramUsed = $ramTotal - $ramFree;
                }
            }
        } else {
            // Linux / Unix RAM calculation
            if (@is_readable('/proc/meminfo')) {
                $meminfo = file('/proc/meminfo');
                $totalKb = 0;
                $freeKb = 0;
                foreach ($meminfo as $line) {
                    if (preg_match('/^MemTotal:\s+(\d+)\s+kB$/', $line, $matches)) {
                        $totalKb = (int)$matches[1];
                    }
                    if (preg_match('/^MemAvailable:\s+(\d+)\s+kB$/', $line, $matches) || preg_match('/^MemFree:\s+(\d+)\s+kB$/', $line, $matches)) {
                        if ($freeKb == 0) {
                            $freeKb = (int)$matches[1];
                        }
                    }
                }
                if ($totalKb > 0 && $freeKb > 0) {
                    $ramTotal = $totalKb * 1024;
                    $ramFree = $freeKb * 1024;
                    $ramUsed = $ramTotal - $ramFree;
                }
            }
        }
        $ramPercent = ($ramTotal > 0) ? round(($ramUsed / $ramTotal) * 100, 1) : 0;

        // Human-readable formatting helper
        $formatBytes = function($bytes, $precision = 1) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        };

        return response()->json([
            'success' => true,
            'data' => [
                'php_version' => $phpVersion,
                'laravel_version' => $laravelVersion,
                'os_platform' => $osPlatform,
                'db_status' => $dbStatus,
                'db_version' => $dbVersion,
                'disk_total' => $formatBytes($diskTotal),
                'disk_free' => $formatBytes($diskFree),
                'disk_used' => $formatBytes($diskUsed),
                'disk_percent' => $diskPercent,
                'ram_total' => $formatBytes($ramTotal),
                'ram_free' => $formatBytes($ramFree),
                'ram_used' => $formatBytes($ramUsed),
                'ram_percent' => $ramPercent,
            ]
        ]);
    }

    public function remoteBackup()
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $dbName = env('DB_DATABASE');
            $result = \DB::select('SHOW TABLES');
            $keyName = "Tables_in_" . $dbName;
            
            $sqlDump = "-- Madrasah Digital Remote Secure Backup Dump\n";
            $sqlDump .= "-- Generated on: " . now()->toDateTimeString() . "\n";
            $sqlDump .= "-- Target School: " . (Setting::first()->company_name ?? 'Madrasah Digital') . "\n";
            $sqlDump .= "-- OS Platform: " . PHP_OS_FAMILY . "\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($result as $row) {
                $tableName = $row->$keyName ?? array_values((array)$row)[0];
                
                // Get CREATE TABLE
                $createTableResult = \DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (empty($createTableResult)) continue;
                
                $createTable = $createTableResult[0];
                $createKey = 'Create Table';
                $sqlDump .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlDump .= $createTable->$createKey . ";\n\n";
                
                // Get Rows using memory-efficient cursor (no orderBy required!)
                $rows = \DB::table($tableName)->cursor();
                foreach ($rows as $item) {
                    $itemArray = (array)$item;
                    $columns = array_keys($itemArray);
                    $values = array_values($itemArray);
                    
                    $escapedValues = array_map(function($val) {
                        if (is_null($val)) return 'NULL';
                        return \DB::getPdo()->quote($val);
                    }, $values);
                    
                    $sqlDump .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
                }
                $sqlDump .= "\n\n";
            }
            
            $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Ensure backup directory exists
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = 'backup_' . date('Ymd_His') . '_' . uniqid() . '.sql';
            $filepath = $backupDir . '/' . $filename;
            
            file_put_contents($filepath, $sqlDump);
            
            return response()->json([
                'success' => true,
                'message' => 'Dump database berhasil digenerate!',
                'download_url' => route('seller.download_backup', $filename),
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadBackup($filename)
    {
        if (!$this->checkAuth()) {
            abort(403, 'Unauthorized');
        }

        // Security check: prevent directory traversal attacks
        $filename = basename($filename);
        $filepath = storage_path('app/backups/' . $filename);

        if (!file_exists($filepath)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        // Stream and delete after send
        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    public function createCoupon(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|string|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'expires_at' => 'nullable|date'
        ]);

        $code = strtoupper(trim($request->code));

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return response()->json(['success' => false, 'message' => 'Diskon persentase tidak boleh lebih dari 100%.'], 422);
        }

        $coupon = Coupon::create([
            'code' => $code,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'expires_at' => $request->expires_at,
            'is_active' => true,
            'used_count' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kupon diskon ' . $coupon->code . ' berhasil dibuat!',
            'coupon' => $coupon
        ]);
    }

    public function toggleCouponStatus(Request $request, $id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $coupon->is_active,
            'message' => 'Status kupon ' . $coupon->code . ' berhasil diubah menjadi ' . ($coupon->is_active ? 'AKTIF' : 'NONAKTIF') . '!'
        ]);
    }

    public function deleteCoupon($id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $coupon = Coupon::findOrFail($id);
        $code = $coupon->code;
        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kupon ' . $code . ' berhasil dihapus!'
        ]);
    }

    public function validateCoupon(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'code' => 'required|string',
            'price' => 'required|numeric|min:0'
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kupon tidak valid atau tidak ditemukan.'
            ], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Kupon ini sudah dinonaktifkan atau telah kadaluarsa.'
            ], 422);
        }

        $discount = $coupon->calculateDiscount($request->price);
        $finalPrice = max(0, $request->price - $discount);

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'discount_amount' => $discount,
            'final_price' => $finalPrice,
            'message' => 'Kupon berhasil diterapkan! Potongan: ' . ($coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : 'Rp ' . number_format($discount, 0, ',', '.'))
        ]);
    }

    public function approveActivation(Request $request, $id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $transaction = LicenseTransaction::findOrFail($id);
        if ($transaction->status !== 'PENDING') {
            return response()->json(['success' => false, 'message' => 'Transaksi ini sudah diproses sebelumnya.'], 422);
        }

        $setting = Setting::first();
        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Konfigurasi tidak ditemukan.'], 404);
        }

        $module = $transaction->module_key;
        
        $modulesMap = [
            'workflow' => 'is_workflow_pro_active',
            'announcements' => 'is_announcements_pro_active',
            'teachers' => 'is_teachers_pro_active',
            'students' => 'is_students_pro_active',
            'curriculum' => 'is_curriculum_pro_active',
            'achievements' => 'is_achievements_pro_active',
            'cbt' => 'is_cbt_pro_active',
            'grades' => 'is_grades_pro_active',
            'attendance' => 'is_attendance_pro_active',
            'mail' => 'is_mail_pro_active',
            'savings' => 'is_savings_pro_active',
            'bos' => 'is_bos_pro_active',
            'ppdb' => 'is_ppdb_pro_active',
            'website' => 'is_website_pro_active',
            'wa_gateway' => 'is_wa_gateway_pro_active',
            'users' => 'is_users_pro_active',
            'system' => 'is_system_pro_active',
        ];

        if (!array_key_exists($module, $modulesMap)) {
            return response()->json(['success' => false, 'message' => 'Key modul tidak dikenali.'], 422);
        }

        $statusKey = $modulesMap[$module];
        
        // Calculate expiration date
        $expiresAt = null;
        if ($transaction->duration === '30') {
            $expiresAt = now()->addDays(30);
        } elseif ($transaction->duration === '365') {
            $expiresAt = now()->addDays(365);
        }

        // Update settings
        $setting->update([
            $statusKey => true,
            $module . '_expires_at' => $expiresAt
        ]);

        // Update transaction status
        $transaction->update([
            'status' => 'SUCCESS'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan aktivasi disetujui! Modul ' . $transaction->module_name . ' telah diaktifkan.'
        ]);
    }

    public function rejectActivation(Request $request, $id)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $transaction = LicenseTransaction::findOrFail($id);
        if ($transaction->status !== 'PENDING') {
            return response()->json(['success' => false, 'message' => 'Transaksi ini sudah diproses sebelumnya.'], 422);
        }

        // Just update status to REJECTED (client can re-upload fresh proof)
        $transaction->update([
            'status' => 'REJECTED'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan aktivasi ditolak.'
        ]);
    }

    public function updateBankSettings(Request $request)
    {
        if (!$this->checkAuth()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'owner_bank_name' => 'required|string|max:100',
            'owner_bank_account' => 'required|string|max:100',
            'owner_bank_holder' => 'required|string|max:150',
            'owner_qris_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $setting = \App\Models\Setting::first();
        if (!$setting) {
            return response()->json(['success' => false, 'message' => 'Sistem setting tidak ditemukan.'], 404);
        }

        $updateData = [
            'owner_bank_name' => $request->owner_bank_name,
            'owner_bank_account' => $request->owner_bank_account,
            'owner_bank_holder' => $request->owner_bank_holder,
        ];

        // Process QRIS Upload if present
        if ($request->hasFile('owner_qris_file')) {
            $file = $request->file('owner_qris_file');
            $filename = 'owner_qris_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/transfer_proofs'), $filename);
            $updateData['owner_qris_path'] = '/uploads/transfer_proofs/' . $filename;
        }

        $setting->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Rekening & QRIS pembayaran berhasil disesuaikan!'
        ]);
    }
}
