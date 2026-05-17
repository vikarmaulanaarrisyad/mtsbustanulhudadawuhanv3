<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta Ujian - {{ $exam->name }}</title>
    <style>
        @page { margin: 15px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; margin: 0; padding: 0; color: #334155; background: #fff; }
        
        .page-break { page-break-after: always; }
        
        .grid-container { width: 100%; border-collapse: separate; border-spacing: 12px; }
        .grid-cell { width: 50%; vertical-align: top; }
        
        .card {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            position: relative;
            min-height: 310px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: #fff;
            padding: 18px 15px;
            text-align: left;
            position: relative;
            border-bottom: 3px solid #f59e0b;
        }
        
        .card-header h3 { margin: 0; font-size: 10.5pt; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 900; }
        .card-header p { margin: 4px 0 0; font-size: 7.5pt; opacity: 0.9; font-weight: 500; letter-spacing: 0.5px; }
        
        .card-body { padding: 15px; position: relative; z-index: 1; }
        
        .main-info { width: 100%; display: table; margin-bottom: 10px; }
        .info-col { display: table-cell; width: 65%; vertical-align: top; }
        .qr-col { display: table-cell; width: 35%; vertical-align: top; text-align: right; }
        
        .label { font-size: 6.5pt; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 1px; }
        .value { font-size: 9.5pt; font-weight: 800; color: #0f172a; margin-bottom: 12px; line-height: 1.2; }
        
        .schedule-grid { display: table; width: 100%; background: #f8fafc; border-radius: 12px; padding: 10px; border: 1px solid #f1f5f9; }
        .schedule-col { display: table-cell; width: 50%; vertical-align: top; }
        
        .schedule-label { font-size: 6pt; color: #64748b; font-weight: bold; text-transform: uppercase; margin-bottom: 1px; }
        .schedule-value { font-size: 8pt; font-weight: bold; color: #1e293b; }
        
        .qr-wrapper {
            background: #fff;
            padding: 6px;
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        .qr-image { width: 90px; height: 90px; }
        .qr-help { font-size: 6pt; color: #64748b; font-weight: 800; margin-top: 6px; text-transform: uppercase; text-align: center; letter-spacing: 1px; }
        
        .footer-table { width: 100%; margin-top: 15px; border-top: 1px dashed #e2e8f0; padding-top: 10px; }
        .footer-note { font-size: 6.5pt; color: #64748b; font-style: italic; vertical-align: middle; line-height: 1.4; }
        .signature-col { text-align: center; width: 140px; vertical-align: top; }
        
        .sig-label { font-size: 6pt; font-weight: bold; margin-bottom: 25px; color: #475569; }
        .sig-name { font-size: 7.5pt; font-weight: 900; color: #0f172a; border-bottom: 1px solid #0f172a; display: inline-block; padding-bottom: 1px; }
        .sig-nip { font-size: 6pt; color: #64748b; margin-top: 2px; }
        
        .watermark {
            position: absolute;
            bottom: 60px;
            right: 20px;
            font-size: 50pt;
            color: rgba(0,0,0,0.02);
            font-weight: 900;
            z-index: 0;
            transform: rotate(-15deg);
        }
        
        .status-badge {
            position: absolute;
            top: 18px;
            right: 15px;
            background: rgba(255,255,255,0.15);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 6pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(255,255,255,0.2);
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
                            <div class="watermark">CBT</div>
                            
                            <div class="card-header">
                                <div class="status-badge">PESERTA UJIAN</div>
                                <div style="display: table; width: 100%;">
                                    <div style="display: table-cell; width: 45px; vertical-align: middle;">
                                        @php
                                            $logoPath = public_path('images/setting/' . ($setting->path_image ?? 'default.jpg'));
                                            if (!file_exists($logoPath)) $logoPath = public_path('images/logo-default.png');
                                            $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : "";
                                        @endphp
                                        @if($logoData)
                                            <img src="data:image/png;base64,{{ $logoData }}" style="width: 40px; height: 40px; border-radius: 8px; border: 2px solid rgba(255,255,255,0.2);">
                                        @endif
                                    </div>
                                    <div style="display: table-cell; vertical-align: middle; padding-left: 12px;">
                                        <h3>{{ $setting->company_name ?? 'MADRASAH DIGITAL' }}</h3>
                                        <p>{{ $exam->name }} • TP {{ $student->academicYear->academic_year ?? '2025/2026' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="main-info">
                                    <div class="info-col">
                                        <div class="label">Nama Lengkap</div>
                                        <div class="value">{{ $student->nama_lengkap }}</div>
                                        
                                        <div class="label">No. Induk / NISN</div>
                                        <div class="value">{{ $student->nisn ?? '-' }}</div>
                                        
                                        <div class="label">Kelas / Rombel</div>
                                        <div class="value">{{ $student->classGroup->class_group ?? '-' }} {{ $student->classGroup->sub_class_group ?? '' }}</div>
                                    </div>
                                    
                                    <div class="qr-col">
                                        <div class="qr-wrapper">
                                            @if($student->qr_token)
                                                <img class="qr-image" src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->margin(1)->generate(route('student.cbt.login-qr', $student->qr_token))) }}">
                                            @else
                                                <div style="width: 90px; height: 90px; border: 1px dashed #cbd5e1;"></div>
                                            @endif
                                        </div>
                                        <div class="qr-help">SCAN UNTUK LOGIN</div>
                                    </div>
                                </div>
                                
                                <div class="schedule-grid">
                                    <div class="schedule-col">
                                        <div class="schedule-label">Sesi & Gelombang</div>
                                        <div class="schedule-value">Gel {{ $student->cbt_wave ?? 1 }} • Sesi {{ $student->cbt_session ?? 1 }}</div>
                                    </div>
                                    <div class="schedule-col" style="padding-left: 15px; border-left: 1px solid #e2e8f0;">
                                        <div class="schedule-label">Ruang & Waktu</div>
                                        @if(isset($sessionTimes[$student->cbt_session]))
                                            <div class="schedule-value">{{ $student->cbt_room ?? '-' }} • {{ substr($sessionTimes[$student->cbt_session]->start_time, 0, 5) }}-{{ substr($sessionTimes[$student->cbt_session]->end_time, 0, 5) }}</div>
                                        @else
                                            <div class="schedule-value">{{ $student->cbt_room ?? '-' }} • {{ substr($exam->start_time, 0, 5) }}-{{ substr($exam->end_time, 0, 5) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <table class="footer-table">
                                    <tr>
                                        <td class="footer-note">
                                            * Simpan kartu ini baik-baik.<br>
                                            * Login otomatis menggunakan QR Code.<br>
                                            * Dilarang membawa catatan ke ruang ujian.
                                        </td>
                                        <td class="signature-col">
                                            <div class="sig-label">Kepala Madrasah,</div>
                                            @php
                                                $sigPath = public_path('images/setting/' . ($setting->path_signature ?? 'signature.png'));
                                                $sigData = file_exists($sigPath) ? base64_encode(file_get_contents($sigPath)) : "";
                                            @endphp
                                            <div style="height: 30px; position: relative; margin-bottom: 5px;">
                                                @if($sigData)
                                                    <img src="data:image/png;base64,{{ $sigData }}" style="height: 45px; position: absolute; left: 50%; transform: translateX(-50%); top: -15px;">
                                                @endif
                                            </div>
                                            <div class="sig-name">{{ $setting->headmaster_name ?? $setting->owner_name }}</div>
                                            <div class="sig-nip">NIP: {{ $setting->headmaster_nip ?? '-' }}</div>
                                        </td>
                                    </tr>
                                </table>
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
