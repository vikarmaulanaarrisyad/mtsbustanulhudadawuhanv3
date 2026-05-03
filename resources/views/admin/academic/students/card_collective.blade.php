<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kolektif Kartu Siswa</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 85.6mm);
            gap: 10mm;
            justify-content: center;
        }
        .card-container {
            width: 85.6mm;
            height: 54mm;
            background: white;
            position: relative;
            overflow: hidden;
            border-radius: 4mm;
            border: 1px solid #e2e8f0;
            display: flex;
            page-break-inside: avoid;
        }
        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            opacity: 0.1;
        }
        .left-side {
            width: 30mm;
            height: 100%;
            background: #10b981;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
        }
        .school-logo {
            width: 12mm;
            height: 12mm;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4mm;
        }
        .school-logo img { width: 80%; }
        .student-photo {
            width: 20mm;
            height: 25mm;
            background: #ddd;
            border: 0.8mm solid white;
            border-radius: 1.5mm;
            overflow: hidden;
        }
        .student-photo img { width: 100%; height: 100%; object-fit: cover; }
        
        .right-side {
            flex: 1;
            padding: 3mm 5mm;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .school-name { font-size: 3mm; font-weight: 800; color: #064e3b; margin: 0; }
        .info-grid { margin-top: 2mm; }
        .info-label { font-size: 1.5mm; color: #64748b; text-transform: uppercase; }
        .info-value { font-size: 2.8mm; font-weight: 700; color: #1e293b; margin-bottom: 1mm; }
        
        .qr-wrapper {
            position: absolute;
            bottom: 3mm;
            right: 3mm;
            width: 12mm;
            height: 12mm;
            background: white;
            padding: 0.5mm;
        }
        .qr-wrapper img { width: 100%; }

        .print-btn {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 25px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            z-index: 100;
        }
        @media print {
            .print-btn { display: none; }
            body { background: white; padding: 0; }
            .card-container { border: 1px solid #ddd; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">CETAK SEMUA KARTU (A4)</button>
    
    <div class="grid-container">
        @foreach($cards as $data)
            <div class="card-container">
                <div class="card-bg"></div>
                <div class="left-side">
                    <div class="school-logo">
                        <img src="{{ asset('storage/' . ($setting->path_image ?? 'default.png')) }}" alt="Logo">
                    </div>
                    <div class="student-photo">
                        @if($data['student']->profile && $data['student']->profile->foto)
                            <img src="{{ asset('storage/' . $data['student']->profile->foto) }}" alt="Foto">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($data['student']->nama_lengkap) }}&background=EBF4FF&color=7F9CF5" alt="Placeholder">
                        @endif
                    </div>
                </div>
                <div class="right-side">
                    <h1 class="school-name">KARTU SISWA</h1>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama</span>
                            <div class="info-value">{{ strtoupper($data['student']->nama_lengkap) }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NISN</span>
                            <div class="info-value">{{ $data['student']->nisn ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas</span>
                            <div class="info-value">{{ $data['student']->kelas_lengkap }}</div>
                        </div>
                    </div>
                    <div class="qr-wrapper">
                        <img src="data:image/svg+xml;base64,{{ $data['qrCode'] }}" alt="QR">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
