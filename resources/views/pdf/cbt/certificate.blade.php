<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Penghargaan - {{ $student->nama_lengkap }}</title>
    <style>
        @page {
            margin: 0;
            size: a4 landscape;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .certificate-container {
            width: 100%;
            height: 100%;
            position: relative;
            background: #fff;
            overflow: hidden;
            border: 20px solid #2c3e50;
            box-sizing: border-box;
        }
        .border-inner {
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 2px solid #e67e22;
            box-sizing: border-box;
        }
        .content {
            text-align: center;
            padding: 50px 100px;
            position: relative;
            z-index: 2;
        }
        .header {
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .school-address {
            font-size: 12px;
            color: #7f8c8d;
            margin: 5px 0 0 0;
        }
        .title {
            font-size: 50px;
            font-weight: bold;
            color: #e67e22;
            margin: 40px 0 10px 0;
            text-transform: uppercase;
            font-style: italic;
        }
        .subtitle {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 40px;
            letter-spacing: 3px;
        }
        .given-to {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #2c3e50;
            display: inline-block;
            padding: 0 50px 5px 50px;
            margin-bottom: 20px;
        }
        .description {
            font-size: 16px;
            line-height: 1.6;
            color: #34495e;
            max-width: 800px;
            margin: 0 auto 50px auto;
        }
        .score-box {
            display: inline-block;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px 25px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .score-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
        }
        .score-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            margin-top: 50px;
            position: relative;
        }
        .signature-wrapper {
            float: right;
            width: 250px;
            text-align: center;
        }
        .date {
            font-size: 14px;
            margin-bottom: 50px;
        }
        .sign-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
            text-decoration: underline;
        }
        .sign-nip {
            font-size: 14px;
            color: #7f8c8d;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: 1;
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="border-inner"></div>
        
        <div class="content">
            <div class="header">
                @if($setting && $setting->logo)
                    <img src="{{ public_path('storage/' . $setting->logo) }}" class="logo">
                @endif
                <h1 class="school-name">{{ $setting->app_name ?? 'MTS BUSTANUL HUDA' }}</h1>
                <p class="school-address">{{ $setting->address ?? 'Dawuhan, Kec. Pilangkenceng, Kab. Madiun' }}</p>
            </div>

            <div class="title">SERTIFIKAT</div>
            <div class="subtitle">PENGHARGAAN ATAS PRESTASI</div>

            <p class="given-to">Diberikan Kepada :</p>
            <div class="student-name">{{ strtoupper($student->nama_lengkap) }}</div>

            <div class="description">
                Atas keberhasilannya dalam menyelesaikan ujian <strong>{{ $exam->name }}</strong> 
                pada mata pelajaran <strong>{{ $exam->bank->subject->name }}</strong> dengan hasil yang sangat memuaskan 
                serta telah melampaui batas Kriteria Ketuntasan Minimal (KKM).
                <br>
                <div class="score-box">
                    <span class="score-label">Skor Akhir :</span><br>
                    <span class="score-value">{{ number_format($studentExam->final_score, 1) }}</span>
                </div>
            </div>

            <div class="footer">
                <div class="signature-wrapper">
                    <div class="date">Dawuhan, {{ \Carbon\Carbon::parse($studentExam->end_time)->translatedFormat('d F Y') }}</div>
                    <div style="height: 80px;"></div> <!-- Space for signature -->
                    <div class="sign-name">{{ $setting->headmaster_name ?? 'Kepala Madrasah' }}</div>
                    <div class="sign-nip">NIP. {{ $setting->headmaster_nip ?? '-' }}</div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</body>
</html>
