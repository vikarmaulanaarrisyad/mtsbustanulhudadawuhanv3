<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta Ujian - {{ $exam->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; margin: 0; padding: 0; }
        .page-break { page-break-after: always; }
        .card-container { width: 100%; display: table; border-collapse: separate; border-spacing: 10px; }
        .card-wrapper { width: 48%; display: table-cell; vertical-align: top; }
        .card {
            border: 2px solid #0f172a;
            border-radius: 10px;
            padding: 10px;
            position: relative;
            background: #fff;
            min-height: 250px;
        }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 5px; margin-bottom: 10px; }
        .header h3 { margin: 0; color: #0f172a; font-size: 12pt; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 8pt; font-weight: bold; }
        .content { display: table; width: 100%; }
        .student-info { display: table-cell; width: 65%; vertical-align: top; }
        .qr-code { display: table-cell; width: 35%; text-align: right; vertical-align: top; }
        .info-row { margin-bottom: 5px; }
        .info-label { font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase; }
        .info-value { font-size: 9pt; font-weight: bold; color: #1e293b; }
        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px dashed #cbd5e1;
            font-size: 7pt;
            text-align: center;
            color: #64748b;
        }
        .schedule-box {
            background: #f1f5f9;
            padding: 5px;
            border-radius: 5px;
            margin-top: 5px;
        }
        .logo { position: absolute; top: 10px; left: 10px; width: 30px; }
    </style>
</head>
<body>
    @php $count = 0; @endphp
    @foreach($students->chunk(2) as $row)
        @if($count > 0 && $count % 8 == 0)
            <div class="page-break"></div>
        @endif
        
        <div class="card-container">
            @foreach($row as $student)
                <div class="card-wrapper">
                    <div class="card">
                        <div class="header">
                            <h3>KARTU PESERTA UJIAN</h3>
                            <p>CBT MADRASAH DIGITAL - {{ strtoupper($exam->name) }}</p>
                        </div>
                        
                        <div class="content">
                            <div class="student-info">
                                <div class="info-row">
                                    <div class="info-label">Nama Lengkap</div>
                                    <div class="info-value">{{ $student->name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">NISN / No. Peserta</div>
                                    <div class="info-value">{{ $student->nisn }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Kelas / Jenjang</div>
                                    <div class="info-value">{{ $student->classGroup->class_group ?? '-' }}</div>
                                </div>
                                
                                <div class="schedule-box">
                                    <div style="font-size: 7pt; font-weight: bold; margin-bottom: 2px;">JADWAL UJIAN:</div>
                                    <div style="font-size: 8pt;">
                                        {{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }} <br>
                                        {{ substr($exam->start_time, 0, 5) }} - {{ substr($exam->end_time, 0, 5) }} WIB
                                    </div>
                                </div>
                            </div>
                            
                            <div class="qr-code">
                                <div style="margin-bottom: 5px;">
                                    {!! QrCode::size(100)->generate(route('student.cbt.login-qr', $student->qr_token)) !!}
                                </div>
                                <div style="font-size: 6pt; font-weight: bold; color: #0f172a; text-align: center;">SCAN UNTUK LOGIN</div>
                            </div>
                        </div>
                        
                        <div class="footer">
                            Simpan kartu ini dengan baik. Scan QR Code di atas untuk masuk ke sistem tanpa mengetik password.
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @php $count += 2; @endphp
    @endforeach
</body>
</html>
