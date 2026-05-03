<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi PPDB - {{ $registrant->nama_lengkap }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            padding: 16px;
        }

        .verify-container {
            max-width: 480px;
            margin: 0 auto;
        }

        /* Header Card */
        .header-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            border-radius: 20px;
            padding: 28px 24px;
            color: white;
            text-align: center;
            margin-bottom: 12px;
            position: relative;
            overflow: hidden;
        }
        .header-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(16,185,129,0.1);
            border-radius: 50%;
        }
        .header-card .school-name {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            opacity: 0.6;
            margin-bottom: 8px;
        }
        .header-card h1 {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .header-card .reg-number {
            font-size: 0.85rem;
            opacity: 0.7;
            font-weight: 500;
        }

        /* Profile Row */
        .profile-row {
            display: flex;
            align-items: center;
            gap: 16px;
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .photo-box {
            width: 72px;
            height: 90px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 1.5rem;
        }
        .profile-info h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .profile-info .sub {
            font-size: 0.78rem;
            color: #64748b;
        }

        /* Status Badge */
        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .status-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        .status-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            color: white;
        }

        /* Data Card */
        .data-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .data-card .section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .data-card .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }
        .data-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .data-row:last-child { border: none; }
        .data-key {
            font-size: 0.8rem;
            color: #64748b;
            flex-shrink: 0;
        }
        .data-val {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }

        /* Document Grid */
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }
        .doc-chip {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            color: #334155;
            font-size: 0.65rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-chip i {
            font-size: 1.2rem;
            margin-bottom: 6px;
            color: #3b82f6;
        }
        .doc-chip:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(59,130,246,0.1);
        }
        .doc-empty {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        /* Verifier Panel */
        .verifier-panel {
            background: linear-gradient(135deg, #fefce8 0%, #fef9c3 100%);
            border: 2px solid #fbbf24;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 12px;
        }
        .verifier-panel .panel-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        .verifier-panel .panel-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f59e0b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .verifier-panel .panel-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #92400e;
        }
        .verifier-panel .panel-sub {
            font-size: 0.75rem;
            color: #a16207;
        }

        /* Catatan */
        .catatan-box {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px;
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            resize: vertical;
            background: white;
            transition: border-color 0.2s;
            margin-bottom: 14px;
        }
        .catatan-box:focus {
            outline: none;
            border-color: #f59e0b;
        }
        .catatan-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 6px;
            display: block;
        }

        /* Action Buttons */
        .btn-action {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            transition: all 0.2s;
            margin-bottom: 8px;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }
        .btn-action:active { transform: translateY(0); }
        .btn-success-custom { background: linear-gradient(135deg, #10b981, #059669); }
        .btn-warning-custom { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .btn-danger-custom  { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .btn-info-custom    { background: linear-gradient(135deg, #3b82f6, #2563eb); }

        .divider {
            border: none;
            border-top: 1px dashed #e5e7eb;
            margin: 10px 0;
        }

        /* Footer */
        .footer-card {
            text-align: center;
            padding: 20px;
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .footer-card a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 24px;
            background: white;
            color: #334155;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 14px;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .footer-card a:hover { background: #f8fafc; }

        /* Verified Info */
        .verified-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 12px;
            border-left: 4px solid #10b981;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .verified-info i { color: #10b981; }
        .verified-info .vi-text { font-size: 0.8rem; color: #334155; }
        .verified-info .vi-text strong { color: #0f172a; }

        /* Animation */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeIn 0.4s ease-out both; }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.1s; }
        .delay-3 { animation-delay: 0.15s; }
        .delay-4 { animation-delay: 0.2s; }
        .delay-5 { animation-delay: 0.25s; }
        .delay-6 { animation-delay: 0.3s; }
    </style>
</head>
<body>
    @php
        $statusColors = [
            'pending' => '#f59e0b', 'berkas_lengkap' => '#10b981', 'berkas_tidak_lengkap' => '#ef4444',
            'diterima' => '#10b981', 'ditolak' => '#ef4444', 'daftar_ulang' => '#3b82f6',
            'daftar_ulang_terverifikasi' => '#10b981', 'cadangan' => '#f59e0b',
        ];
        $statusLabels = [
            'pending' => 'Menunggu Verifikasi', 'berkas_lengkap' => 'Berkas Lengkap', 'berkas_tidak_lengkap' => 'Berkas Tidak Lengkap',
            'diterima' => 'Diterima', 'ditolak' => 'Ditolak', 'daftar_ulang' => 'Daftar Ulang',
            'daftar_ulang_terverifikasi' => 'Daftar Ulang Verified', 'cadangan' => 'Cadangan',
        ];
        $sColor = $statusColors[$registrant->status] ?? '#64748b';
        $sLabel = $statusLabels[$registrant->status] ?? ucfirst($registrant->status);
    @endphp

    <div class="verify-container">

        {{-- HEADER --}}
        <div class="header-card animate-in">
            <div class="school-name">{{ $source->school_name ?? 'Madrasah' }}</div>
            <h1>Validasi Dokumen PPDB</h1>
            <div class="reg-number"><i class="fas fa-hashtag"></i> {{ $registrant->registration_number ?? '-' }}</div>
        </div>

        {{-- PROFILE ROW --}}
        <div class="profile-row animate-in delay-1">
            <div class="photo-box">
                @if($registrant->foto)
                    <img src="{{ Storage::url($registrant->foto) }}" alt="Foto">
                @else
                    <div class="photo-placeholder"><i class="fas fa-user"></i></div>
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ $registrant->nama_lengkap }}</h2>
                <div class="sub"><i class="fas fa-school"></i> {{ $registrant->asal_sekolah ?? '-' }}</div>
                <div class="sub"><i class="fas fa-id-card"></i> NISN: {{ $registrant->nisn ?? '-' }}</div>
            </div>
        </div>

        {{-- STATUS ROW --}}
        <div class="status-row animate-in delay-2">
            <span class="status-label">Status</span>
            <span class="status-badge" style="background:{{ $sColor }};">
                {{ $sLabel }}
            </span>
        </div>

        {{-- DATA PENDAFTAR --}}
        <div class="data-card animate-in delay-3">
            <div class="section-title"><i class="fas fa-user-circle"></i> Data Pendaftar</div>
            <div class="data-row">
                <span class="data-key">NIK</span>
                <span class="data-val">{{ $registrant->nik ?? '-' }}</span>
            </div>
            <div class="data-row">
                <span class="data-key">TTL</span>
                <span class="data-val">{{ $registrant->tempat_lahir ?? '-' }}, {{ $registrant->tanggal_lahir ? $registrant->tanggal_lahir->format('d/m/Y') : '-' }}</span>
            </div>
            <div class="data-row">
                <span class="data-key">Gelombang</span>
                <span class="data-val">{{ $registrant->admissionPhase->phase_name ?? '-' }}</span>
            </div>
            <div class="data-row">
                <span class="data-key">Jalur</span>
                <span class="data-val">{{ $registrant->admissionType->admission_type_name ?? '-' }}</span>
            </div>
            @if($registrant->verifier)
            <div class="data-row">
                <span class="data-key">Verifikator</span>
                <span class="data-val" style="color:#10b981;"><i class="fas fa-user-check"></i> {{ $registrant->verifier->name }}</span>
            </div>
            @endif
        </div>

        {{-- BERKAS UPLOAD --}}
        <div class="data-card animate-in delay-4">
            <div class="section-title"><i class="fas fa-folder-open"></i> Berkas Terunggah ({{ $registrant->documents->count() }})</div>
            @if($registrant->documents->isEmpty())
                <div class="doc-empty">
                    <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    Belum ada berkas yang diunggah
                </div>
            @else
                <div class="doc-grid">
                    @php
                        $docIcons = [
                            'akta_kelahiran' => 'fa-baby', 'kartu_keluarga' => 'fa-users',
                            'ijazah' => 'fa-graduation-cap', 'skhun' => 'fa-certificate',
                            'rapor' => 'fa-book', 'foto' => 'fa-camera',
                            'kip' => 'fa-id-badge', 'surat_keterangan' => 'fa-file-alt',
                        ];
                    @endphp
                    @foreach($registrant->documents as $doc)
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="doc-chip">
                            <i class="fas {{ $docIcons[$doc->document_type] ?? 'fa-file' }}"></i>
                            {{ strtoupper(str_replace('_', ' ', $doc->document_type)) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- VERIFIED INFO --}}
        @if($registrant->verified_at)
            <div class="verified-info animate-in delay-4">
                <i class="fas fa-shield-alt fa-lg"></i>
                <div class="vi-text">
                    Diverifikasi oleh <strong>{{ $registrant->verifier->name ?? 'Sistem' }}</strong>
                    <br><span style="font-size:0.7rem;color:#64748b;">{{ $registrant->verified_at->format('d M Y, H:i') }} WIB</span>
                </div>
            </div>
        @endif

        {{-- PANEL VERIFIKATOR --}}
        @auth
            @if(auth()->user()->can('ppdb.verify'))
                <div class="verifier-panel animate-in delay-5">
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-user-shield"></i></div>
                        <div>
                            <div class="panel-title">Panel Verifikator</div>
                            <div class="panel-sub">Login: {{ auth()->user()->name }}</div>
                        </div>
                    </div>

                    {{-- TOMBOL KONTEKSTUAL BERDASARKAN STATUS --}}
                    @if(in_array($registrant->status, ['pending', 'berkas_tidak_lengkap']))
                        {{-- STATUS: PERLU VERIFIKASI BERKAS --}}
                        <label class="catatan-label"><i class="fas fa-pen-nib mr-1"></i> Catatan untuk Pendaftar:</label>
                        <textarea id="catatanVerifikasi" class="catatan-box" rows="2" placeholder="Contoh: KK tidak jelas, foto buram, dll..."></textarea>

                        <button onclick="doVerify('verify_doc')" class="btn-action btn-success-custom">
                            <i class="fas fa-check-double"></i> Berkas Lengkap & Valid
                        </button>
                        <button onclick="doVerify('incomplete')" class="btn-action btn-warning-custom">
                            <i class="fas fa-exclamation-triangle"></i> Berkas Tidak Lengkap
                        </button>
                        <button onclick="doVerify('reject')" class="btn-action btn-danger-custom">
                            <i class="fas fa-ban"></i> Tolak Pendaftaran
                        </button>

                    @elseif($registrant->status === 'daftar_ulang')
                        {{-- STATUS: PERLU VERIFIKASI PEMBAYARAN --}}
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:14px;margin-bottom:12px;text-align:center;">
                            <i class="fas fa-receipt" style="font-size:1.5rem;color:#3b82f6;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:0.85rem;color:#1e40af;font-weight:600;">Bukti Pembayaran Telah Diunggah</div>
                            <div style="font-size:0.75rem;color:#64748b;margin-top:4px;">Periksa bukti pembayaran, lalu klik tombol di bawah</div>
                            @if($registrant->payment_proof)
                                <a href="{{ Storage::url($registrant->payment_proof) }}" target="_blank" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;color:#3b82f6;font-size:0.8rem;font-weight:600;text-decoration:none;">
                                    <i class="fas fa-image"></i> Lihat Bukti Pembayaran
                                </a>
                            @endif
                        </div>

                        <label class="catatan-label"><i class="fas fa-pen-nib mr-1"></i> Catatan (opsional):</label>
                        <textarea id="catatanVerifikasi" class="catatan-box" rows="2" placeholder="Catatan tambahan..."></textarea>

                        <button onclick="doVerify('verify_payment')" class="btn-action btn-info-custom" style="margin-bottom:0;">
                            <i class="fas fa-money-check-alt"></i> Verifikasi Pembayaran Daftar Ulang
                        </button>

                    @elseif($registrant->status === 'berkas_lengkap')
                        {{-- STATUS: SUDAH DIVERIFIKASI, MENUNGGU SELEKSI --}}
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;text-align:center;">
                            <i class="fas fa-check-circle" style="font-size:2rem;color:#10b981;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:0.9rem;color:#166534;font-weight:700;">Berkas Sudah Diverifikasi</div>
                            <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">Pendaftar menunggu hasil seleksi dari panitia.</div>
                        </div>

                    @elseif($registrant->status === 'daftar_ulang_terverifikasi')
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;text-align:center;">
                            <i class="fas fa-user-check" style="font-size:2rem;color:#10b981;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:0.9rem;color:#166534;font-weight:700;">Daftar Ulang Sudah Terverifikasi</div>
                            <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">Siswa resmi diterima dan terdaftar.</div>
                        </div>

                    @elseif($registrant->status === 'diterima')
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:16px;text-align:center;">
                            <i class="fas fa-hourglass-half" style="font-size:2rem;color:#3b82f6;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:0.9rem;color:#1e40af;font-weight:700;">Menunggu Daftar Ulang</div>
                            <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">Siswa sudah diterima, menunggu upload bukti pembayaran daftar ulang.</div>
                        </div>

                    @elseif($registrant->status === 'ditolak')
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:16px;text-align:center;">
                            <i class="fas fa-times-circle" style="font-size:2rem;color:#ef4444;display:block;margin-bottom:8px;"></i>
                            <div style="font-size:0.9rem;color:#991b1b;font-weight:700;">Pendaftaran Ditolak</div>
                            @if($registrant->catatan_verifikasi)
                                <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">{{ $registrant->catatan_verifikasi }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        @endauth

        {{-- FOOTER --}}
        <div class="footer-card animate-in delay-6">
            <a href="/"><i class="fas fa-arrow-left"></i> Kembali</a>
            <div>&copy; {{ date('Y') }} {{ $source->school_name ?? 'Sistem PPDB Online' }}</div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function doVerify(action) {
            const config = {
                'verify_doc':     { title: 'Verifikasi Berkas Lengkap?', text: 'Berkas fisik sudah diperiksa dan sesuai.', color: '#10b981', icon: 'question' },
                'incomplete':     { title: 'Tandai Tidak Lengkap?', text: 'Catatan akan dikirim ke pendaftar untuk perbaikan.', color: '#f59e0b', icon: 'warning' },
                'reject':         { title: 'Tolak Pendaftaran?', text: 'Tindakan ini akan menolak pendaftaran. Yakin?', color: '#ef4444', icon: 'warning' },
                'verify_payment': { title: 'Verifikasi Pembayaran?', text: 'Bukti pembayaran daftar ulang sudah valid.', color: '#3b82f6', icon: 'question' },
            };
            const c = config[action];
            const catatan = document.getElementById('catatanVerifikasi')?.value || '';

            Swal.fire({
                title: c.title,
                text: c.text,
                icon: c.icon,
                showCancelButton: true,
                confirmButtonColor: c.color,
                cancelButtonColor: '#94a3b8',
                confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: { popup: 'swal-rounded' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    $.post('{{ route("ppdb.process_verify_scan") }}', {
                        _token: '{{ csrf_token() }}',
                        id: '{{ $registrant->id }}',
                        action: action,
                        catatan: catatan
                    })
                    .done(res => {
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false })
                            .then(() => window.location.href = '{{ route("ppdb.scanner") }}');
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan server.' });
                    });
                }
            });
        }
    </script>
</body>
</html>
