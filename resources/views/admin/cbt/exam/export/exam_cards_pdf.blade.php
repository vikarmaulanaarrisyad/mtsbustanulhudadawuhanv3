<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta Ujian - {{ $exam->name }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; margin: 0; padding: 0; color: #1e293b; background: #fff; }
        
        .page-break { page-break-after: always; }
        
        .grid-container { width: 100%; border-collapse: separate; border-spacing: 15px; }
        .grid-cell { width: 50%; vertical-align: top; }
        
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            position: relative;
            min-height: 280px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: #0f172a;
            color: #fff;
            padding: 15px;
            text-align: center;
            position: relative;
        }
        
        .card-header h3 { margin: 0; font-size: 11pt; letter-spacing: 1px; text-transform: uppercase; }
        .card-header p { margin: 3px 0 0; font-size: 7pt; opacity: 0.8; font-weight: normal; }
        
        .card-body { padding: 15px; display: table; width: 100%; }
        
        .info-col { display: table-cell; width: 68%; vertical-align: top; }
        .qr-col { display: table-cell; width: 32%; vertical-align: top; text-align: center; }
        
        .label { font-size: 7pt; color: #64748b; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .value { font-size: 9pt; font-weight: bold; color: #1e293b; margin-bottom: 8px; }
        
        .schedule-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            padding: 8px;
            margin-top: 10px;
        }
        
        .schedule-item { font-size: 8pt; margin-bottom: 3px; }
        .schedule-item i { color: #0f172a; margin-right: 5px; }
        
        .qr-wrapper {
            background: #fff;
            padding: 5px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: inline-block;
            margin-top: 5px;
        }
        
        .qr-image { width: 85px; height: 85px; }
        
        .qr-help { font-size: 6pt; color: #64748b; font-weight: bold; margin-top: 5px; text-transform: uppercase; }
        
        .card-footer {
            background: #f8fafc;
            padding: 8px 15px;
            font-size: 6.5pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-style: italic;
        }
        
        .accent-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #f59e0b;
        }

        .watermark {
            position: absolute;
            bottom: 40px;
            right: 15px;
            font-size: 40pt;
            color: rgba(0,0,0,0.03);
            font-weight: bold;
            z-index: 0;
            transform: rotate(-15deg);
        }
    </style>
</head>
<body>
    @php $count = 0; @endphp
    @foreach($students->chunk(2) as $row)
        @if($count > 0 && $count % 8 == 0)
            <div class="page-break"></div>
        @endif
        
        <table class="grid-container">
            <tr>
                @foreach($row as $student)
                    <td class="grid-cell">
                        <div class="card">
                            <div class="accent-line"></div>
                            <div class="watermark">CBT</div>
                            
                            <div class="card-header">
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-cell; width: 50px; vertical-align: middle;">
                                        @php
                                            $logoPath = public_path('images/setting/' . ($setting->path_image ?? 'default.jpg'));
                                            if (!file_exists($logoPath)) {
                                                $logoPath = public_path('images/logo-default.png'); // Fallback
                                            }
                                            $logoData = "";
                                            if (file_exists($logoPath)) {
                                                $logoData = base64_encode(file_get_contents($logoPath));
                                            }
                                        @endphp
                                        @if($logoData)
                                            <img src="data:image/png;base64,{{ $logoData }}" style="width: 35px; height: 35px; border-radius: 5px;">
                                        @endif
                                    </div>
                                    <div style="display: table-cell; vertical-align: middle; padding-left: 10px; text-align: left;">
                                        <h3>KARTU PESERTA UJIAN</h3>
                                        <p>{{ $setting->company_name ?? 'MADRASAH DIGITAL' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="info-col">
                                    <div class="label">Nama Lengkap</div>
                                    <div class="value">{{ $student->name }}</div>
                                    
                                    <div class="label">NISN / No. Peserta</div>
                                    <div class="value">{{ $student->nisn }}</div>
                                    
                                    <div class="label">Kelas / Rombel</div>
                                    <div class="value">{{ $student->classGroup->class_group ?? '-' }}</div>
                                    
                                    <div class="schedule-box">
                                        <div class="schedule-item">
                                            <strong>Tgl:</strong> {{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }}
                                        </div>
                                        <div class="schedule-item">
                                            <strong>Sesi:</strong> {{ substr($exam->start_time, 0, 5) }} - {{ substr($exam->end_time, 0, 5) }} WIB
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="qr-col">
                                    <div class="qr-wrapper">
                                        @if($student->qr_token)
                                            <img class="qr-image" src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->margin(1)->generate(route('student.cbt.login-qr', $student->qr_token))) }}">
                                        @else
                                            <div style="width: 85px; height: 85px; display: flex; align-items: center; justify-content: center; font-size: 6pt; color: #ccc;">Token Kosong</div>
                                        @endif
                                    </div>
                                    <div class="qr-help">Scan to Login</div>

                                    <div style="margin-top: 15px; text-align: center;">
                                        <div style="font-size: 6pt; color: #64748b;">Dikeluarkan di: {{ $setting->city ?? 'Madrasah' }}</div>
                                        <div style="font-size: 6pt; font-weight: bold; margin-bottom: 5px;">Kepala Madrasah,</div>
                                        
                                        @php
                                            $sigPath = public_path('images/setting/' . ($setting->path_signature ?? 'signature.png'));
                                            $sigData = "";
                                            if (file_exists($sigPath)) {
                                                $sigData = base64_encode(file_get_contents($sigPath));
                                            }
                                        @endphp
                                        
                                        <div style="height: 35px; position: relative;">
                                            @if($sigData)
                                                <img src="data:image/png;base64,{{ $sigData }}" style="height: 40px; position: absolute; left: 50%; transform: translateX(-50%); top: -10px;">
                                            @else
                                                <div style="height: 30px;"></div>
                                            @endif
                                        </div>
                                        
                                        <div style="font-size: 7pt; font-weight: bold; border-bottom: 1px solid #1e293b; display: inline-block; padding-bottom: 1px;">
                                            {{ $setting->headmaster_name ?? $setting->owner_name }}
                                        </div>
                                        <div style="font-size: 6pt; color: #64748b; margin-top: 2px;">NIP: {{ $setting->headmaster_nip ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                Simpan kartu ini. Gunakan QR Code untuk login otomatis tanpa password.
                            </div>
                        </div>
                    </td>
                @endforeach
                @if($row->count() == 1)
                    <td class="grid-cell"></td>
                @endif
            </tr>
        </table>
        
        @php $count += 2; @endphp
    @endforeach
</body>
</html>
