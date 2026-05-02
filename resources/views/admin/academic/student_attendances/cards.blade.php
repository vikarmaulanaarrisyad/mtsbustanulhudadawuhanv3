<!DOCTYPE html>
<html>
<head>
    <title>Cetak Kartu QR Siswa</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background: #f0f0f0; }
        .card-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .card { 
            background: white; border: 2px solid #333; border-radius: 10px; padding: 15px; 
            display: flex; align-items: center; position: relative; height: 160px;
            overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card-header { position: absolute; top: 0; left: 0; right: 0; background: #6755a5; color: white; padding: 5px; text-align: center; font-size: 10px; font-weight: bold; }
        .qr-section { flex: 0 0 100px; margin-right: 15px; }
        .info-section { flex: 1; margin-top: 15px; }
        .info-section h4 { margin: 0 0 5px 0; font-size: 14px; color: #333; }
        .info-section p { margin: 0; font-size: 12px; color: #666; }
        .nisn { font-family: monospace; background: #eee; padding: 2px 5px; border-radius: 3px; font-size: 11px; margin-top: 5px; display: inline-block; }
        
        @media print {
            body { background: white; padding: 0; }
            .card-grid { gap: 10px; }
            .card { border: 1px solid #ccc; box-shadow: none; break-inside: avoid; }
            .no-print { display: none; }
        }
        
        .no-print { margin-bottom: 20px; background: #fff; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 5px;">
            CETAK KARTU SEKARANG
        </button>
        <p style="margin-top: 10px; color: #666; font-size: 13px;">Format ini dirancang untuk kertas A4. Disarankan menggunakan kertas tebal (Art Paper/Brief Card).</p>
    </div>

    <div class="card-grid">
        @foreach($students as $student)
        <div class="card">
            <div class="card-header">KARTU PRESENSI - MTs. BUSTANUL HUDA</div>
            <div class="qr-section">
                {!! QrCode::size(100)->generate($student->nisn ?? $student->nis) !!}
            </div>
            <div class="info-section">
                <h4>{{ $student->nama_lengkap }}</h4>
                <p>{{ $student->kelas_lengkap }}</p>
                <div class="nisn">NISN: {{ $student->nisn ?? '-' }}</div>
                <div style="margin-top: 10px; font-size: 9px; color: #999;">Tahun Pelajaran: {{ $student->academicYear->academic_year ?? '-' }}</div>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
