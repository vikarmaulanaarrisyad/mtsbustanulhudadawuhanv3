<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen PPDB - {{ $source->school_name ?? 'MTs. Bustanul Huda' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: var(--bg); 
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 450px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 40px 20px;
            text-align: center;
            color: white;
            position: relative;
        }

        .header .verified-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .header h1 { font-size: 1.5rem; margin-bottom: 8px; }
        .header p { font-size: 0.9rem; opacity: 0.9; }

        .profile-section {
            padding: 30px;
            text-align: center;
            margin-top: -50px;
        }

        .photo-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 20px;
            background: white;
            padding: 5px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin: 0 auto 20px;
            overflow: hidden;
        }

        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 2rem;
            border-radius: 15px;
        }

        .status-badge {
            background: #ecfdf5;
            color: #059669;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 25px;
            display: inline-block;
        }

        .data-grid {
            text-align: left;
            display: grid;
            gap: 16px;
        }

        .data-item {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
        }

        .data-item:last-child { border: none; }

        .data-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
            display: block;
        }

        .data-value {
            font-weight: 600;
            color: var(--text-main);
            font-size: 1rem;
        }

        .footer {
            padding: 20px;
            background: #f8fafc;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid #f1f5f9;
        }

        .btn-home {
            display: inline-block;
            margin-top: 20px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-home:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="verified-badge">
                <i class="fas fa-check-circle"></i> DATA TERVERIFIKASI SISTEM
            </div>
            <h1>HASIL VALIDASI DOKUMEN</h1>
            <p>{{ $source->school_name ?? 'Madrasah' }}</p>
        </div>

        <div class="profile-section">
            <div class="photo-wrapper">
                @if($registrant->foto)
                    <img src="{{ Storage::url($registrant->foto) }}" alt="Foto">
                @else
                    <div class="photo-placeholder"><i class="fas fa-user"></i></div>
                @endif
            </div>

            <div class="status-badge">{{ $registrant->status_label }}</div>

            <div class="data-grid">
                <div class="data-item">
                    <span class="data-label">Nomor Registrasi</span>
                    <span class="data-value">{{ $registrant->registration_number }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Nama Lengkap</span>
                    <span class="data-value">{{ $registrant->nama_lengkap }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">NISN</span>
                    <span class="data-value">{{ $registrant->nisn ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Asal Sekolah</span>
                    <span class="data-value">{{ $registrant->asal_sekolah ?? '-' }}</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Gelombang / Jalur</span>
                    <span class="data-value">{{ $registrant->admissionPhase->phase_name ?? '-' }} ({{ $registrant->admissionType->admission_type_name ?? '-' }})</span>
                </div>
                <div class="data-item">
                    <span class="data-label">Tanggal Daftar</span>
                    <span class="data-value">{{ $registrant->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>

            <a href="/" class="btn-home">Kembali ke Halaman Utama</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ $source->school_name ?? 'Sistem PPDB Online' }}<br>
            Jl. Raya Dawuhan, Kec. Pilangkenceng, Kab. Madiun
        </div>
    </div>
</body>
</html>
