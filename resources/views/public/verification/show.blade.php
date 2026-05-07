<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen - {{ $setting->company_name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --dark: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .verification-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .card-header-premium {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        .logo-circle {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .verified-badge {
            display: inline-flex;
            align-items: center;
            background: var(--success);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }
        .info-row {
            padding: 15px 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .info-label {
            color: #64748b;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-value {
            color: var(--dark);
            font-weight: 700;
            text-align: right;
        }
        .footer-premium {
            padding: 20px 30px;
            text-align: center;
            background: rgba(255, 255, 255, 0.5);
        }
        .btn-premium {
            background: var(--dark);
            color: white;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 700;
            border: none;
            transition: all 0.3s;
        }
        .btn-premium:hover {
            background: #1e293b;
            transform: translateY(-2px);
            color: white;
        }
    </style>
</head>
<body>
    <div class="verification-card animate__animated animate__zoomIn">
        <div class="card-header-premium">
            <div class="logo-circle">
                <img src="{{ Storage::url($setting->path_image) }}" alt="Logo" style="height: 50px;">
            </div>
            <h5 class="mb-1 font-weight-bold">{{ $setting->company_name }}</h5>
            <p class="mb-0 opacity-70 small">Sistem Verifikasi Dokumen Digital</p>
        </div>
        
        <div class="p-4 text-center">
            <div class="verified-badge animate__animated animate__bounceIn animate__delay-1s">
                <i class="fas fa-check-circle mr-2"></i> DOKUMEN TERVERIFIKASI
            </div>
            <p class="text-muted small px-3">Dokumen ini telah divalidasi oleh sistem administrasi digital Madrasah dan dinyatakan asli.</p>
        </div>

        <div class="verification-details">
            <div class="info-row">
                <span class="info-label">Jenis Dokumen</span>
                <span class="info-value text-primary">{{ $verification->document_type }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nomor Surat</span>
                <span class="info-value">{{ $verification->document_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Pemilik</span>
                <span class="info-value">{{ $verification->student->nama_lengkap }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NIS / NISN</span>
                <span class="info-value">{{ $verification->student->nis }} / {{ $verification->student->nisn }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Terbit</span>
                <span class="info-value">{{ $verification->created_at->translatedFormat('d F Y') }}</span>
            </div>
            @if($verification->signed_by)
            <div class="info-row">
                <span class="info-label">Ditandatangani Oleh</span>
                <span class="info-value">{{ $verification->signed_by }}</span>
            </div>
            @endif
            <div class="info-row border-0">
                <span class="info-label">Kode Verifikasi</span>
                <span class="info-value"><code class="text-dark">{{ $verification->verification_code }}</code></span>
            </div>
        </div>

        <div class="footer-premium">
            <a href="{{ route('front.index') }}" class="btn-premium btn-block">
                <i class="fas fa-home mr-2"></i> KEMBALI KE BERANDA
            </a>
            <p class="mt-3 mb-0 text-muted" style="font-size: 0.7rem;">&copy; {{ date('Y') }} {{ $setting->company_name }} - Smart Madrasah Digital System</p>
        </div>
    </div>
</body>
</html>
