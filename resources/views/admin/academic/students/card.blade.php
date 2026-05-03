<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Siswa - {{ $student->nama_lengkap }}</title>
    <style>
        @page {
            size: 85.6mm 54mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', 'Segoe UI', sans-serif;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card-container {
            width: 85.6mm;
            height: 54mm;
            background: #0f172a;
            position: relative;
            overflow: hidden;
            border-radius: 3mm;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            color: white;
            display: flex;
        }
        
        /* Decorative Elements */
        .card-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0);
            background-size: 8px 8px;
            z-index: 0;
        }
        .glow {
            position: absolute;
            top: -25mm;
            left: -25mm;
            width: 70mm;
            height: 70mm;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%);
            z-index: 0;
        }
        .glow-2 {
            position: absolute;
            bottom: -20mm;
            right: -20mm;
            width: 60mm;
            height: 60mm;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            z-index: 0;
        }

        .side-bar {
            width: 5mm;
            height: 100%;
            background: linear-gradient(to bottom, #10b981, #3b82f6);
            z-index: 2;
        }

        .content-area {
            flex: 1;
            padding: 4mm 5mm;
            display: flex;
            z-index: 1;
        }

        .photo-section {
            width: 24mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 5mm;
        }
        .photo-frame {
            width: 22mm;
            height: 28mm;
            background: rgba(255,255,255,0.1);
            border: 0.5mm solid rgba(255,255,255,0.2);
            border-radius: 2mm;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2mm;
            border-bottom: 0.2mm solid rgba(255,255,255,0.1);
            padding-bottom: 1.5mm;
        }
        .school-info h2 {
            font-size: 3.2mm;
            margin: 0;
            color: #10b981;
            letter-spacing: 0.5mm;
        }
        .school-info p {
            font-size: 1.8mm;
            margin: 0;
            opacity: 0.7;
            text-transform: uppercase;
        }

        .student-details {
            display: grid;
            gap: 1.5mm;
        }
        .detail-item .label {
            font-size: 1.6mm;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.2mm;
        }
        .detail-item .value {
            font-size: 2.8mm;
            font-weight: 600;
            color: #f8fafc;
        }

        .footer-card {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .qr-box {
            background: white;
            padding: 0.8mm;
            border-radius: 1.2mm;
            width: 12mm;
            height: 12mm;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
        }
        .valid-label {
            font-size: 1.5mm;
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            padding: 0.5mm 1.5mm;
            border-radius: 1mm;
            font-weight: 700;
        }
        
        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 100;
        }
        @media print {
            .print-btn { display: none; }
            body { background: white; }
            .card-container { box-shadow: none; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-pattern"></div>
        <div class="glow"></div>
        <div class="glow-2"></div>
        <div class="side-bar"></div>
        
        <div class="content-area">
            <div class="photo-section">
                <div class="photo-frame">
                    @if($student->profile && $student->profile->foto)
                        <img src="{{ asset('storage/' . $student->profile->foto) }}" alt="Foto">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama_lengkap) }}&background=1e293b&color=fff" alt="Placeholder">
                    @endif
                </div>
            </div>
            
            <div class="info-section">
                <div class="card-header">
                    <div class="school-info">
                        <h2>KARTU SISWA</h2>
                        <p>{{ $setting->company_name ?? 'MTs. BUSTANUL HUDA' }}</p>
                    </div>
                    <div class="valid-label">SISWA AKTIF</div>
                </div>
                
                <div class="student-details">
                    <div class="detail-item">
                        <div class="label">Nama Lengkap</div>
                        <div class="value">{{ strtoupper($student->nama_lengkap) }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">NISN / NIS</div>
                        <div class="value">{{ $student->nisn ?? '-' }} / {{ $student->nis ?? '-' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">Kelas</div>
                        <div class="value">{{ $student->kelas_lengkap }}</div>
                    </div>
                </div>
                
                <div class="footer-card">
                    <div class="detail-item">
                        <div class="label">Tempat, Tgl Lahir</div>
                        <div class="value" style="font-size: 2.2mm;">{{ $student->tempat_lahir }}, {{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') }}</div>
                    </div>
                    <div class="qr-box">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button onclick="window.print()" class="print-btn">Cetak Kartu</button>
</body>
</html>
