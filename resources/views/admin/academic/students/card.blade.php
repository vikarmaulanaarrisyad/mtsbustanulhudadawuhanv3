<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu NISN - {{ $student->nama_lengkap }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 20mm 0;
            font-family: 'Helvetica', Arial, sans-serif;
            background: #f1f5f9;
        }

        .card {
            width: 85.6mm;
            height: 54mm;
            margin: 0 auto 15mm auto;
            position: relative;
            background: #0f172a;
            color: white;
            border-radius: 4mm;
            overflow: hidden;
            border: 0.2mm solid #1e293b;
            box-sizing: border-box;
        }

        /* Background Design */
        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            z-index: 0;
        }

        .side-accent {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 1.5mm;
            background: #10b981;
            z-index: 5;
        }

        /* Content Container */
        .content {
            position: relative;
            z-index: 10;
            padding: 3mm 4mm;
            height: 100%;
            box-sizing: border-box;
        }

        /* Table Layout for Stability */
        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        td {
            vertical-align: top;
            padding: 0;
        }

        /* Header */
        .header-table {
            border-bottom: 0.15mm solid rgba(255,255,255,0.2);
            padding-bottom: 1.5mm;
            margin-bottom: 2mm;
        }

        .school-name {
            font-size: 3.5mm;
            font-weight: bold;
            color: #10b981;
            text-transform: uppercase;
            margin: 0;
        }

        .school-sub {
            font-size: 1.8mm;
            color: rgba(255,255,255,0.7);
            margin: 0;
            text-transform: uppercase;
        }

        .school-addr {
            font-size: 1.4mm;
            color: rgba(255,255,255,0.5);
            margin: 0;
            text-transform: capitalize;
            line-height: 1.2;
        }

        /* Photo */
        .photo-td {
            width: 20mm;
        }

        .photo-box {
            width: 18mm;
            height: 24mm;
            border: 0.3mm solid rgba(255,255,255,0.3);
            border-radius: 1mm;
            overflow: hidden;
            background: #1e293b;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Data */
        .data-td {
            padding-left: 3mm;
        }

        .label {
            font-size: 1.4mm;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 0.3mm;
        }

        .value {
            font-size: 2.6mm;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 1.2mm;
        }

        .value-name {
            font-size: 3mm;
            color: #10b981;
        }

        /* QR */
        .qr-box {
            background: white;
            padding: 0.8mm;
            border-radius: 1mm;
            width: 11mm;
            height: 11mm;
            text-align: center;
        }

        .qr-box svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Signature */
        .signature-area {
            text-align: center;
            width: 38mm;
            line-height: 1.1;
        }

        .sig-city {
            font-size: 1.6mm;
            margin-bottom: 0.5mm;
        }

        .sig-role {
            font-size: 1.6mm;
            font-weight: bold;
            margin-bottom: 4mm;
        }

        .sig-name {
            font-size: 2.1mm;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0.2mm;
        }

        .sig-nip {
            font-size: 1.6mm;
            margin: 0;
            padding: 0;
        }

        /* Back Card */
        .back-title {
            font-size: 3mm;
            font-weight: bold;
            color: #10b981;
            text-align: center;
            border-bottom: 0.2mm solid rgba(255,255,255,0.2);
            padding-bottom: 2mm;
            margin-bottom: 4mm;
            text-transform: uppercase;
        }

        .rules {
            font-size: 2.2mm;
            line-height: 1.6;
            color: #cbd5e1;
            padding-left: 5mm;
        }

        .footer-info {
            position: absolute;
            bottom: 4mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 1.8mm;
            color: #94a3b8;
            border-top: 0.1mm solid rgba(255,255,255,0.1);
            padding-top: 2mm;
        }

        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 100;
        }

        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    @php
        $kepala = get_kepala_madrasah();
        $mailSetting = \App\Models\MailSetting::first();
        $general = \App\Models\Setting::first();
    @endphp

    <!-- DEPAN -->
    <div class="card">
        <div class="card-bg"></div>
        <div class="side-accent"></div>
        <div class="content">
            <!-- Header -->
            <table class="header-table">
                <tr>
                    <td style="width: 10mm;">
                        @php
                            $logo = $mailSetting->logo ?? null;
                            $logoPath = null;
                            if ($logo) {
                                $path = storage_path('app/public/' . $logo);
                                if (file_exists($path)) {
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $logoPath = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                }
                            }
                        @endphp
                        @if($logoPath)
                            <img src="{{ $logoPath }}" style="width: 8.5mm; height: 8.5mm; object-fit: contain;">
                        @else
                            <div style="width: 8.5mm; height: 8.5mm; background: #10b981; border-radius: 1mm;"></div>
                        @endif
                    </td>
                    <td>
                        <div class="school-sub">{{ $mailSetting->sub_header ?? 'YAYASAN BUSTANUL HUDA DAWUHAN' }}</div>
                        <div class="school-name">{{ $mailSetting->school_name ?? 'MTs BUSTANUL HUDA' }}</div>
                        <div class="school-addr">{{ $mailSetting->address ?? 'Alamat Madrasah' }}</div>
                    </td>
                </tr>
            </table>

            <!-- Body -->
            <table>
                <tr>
                    <td class="photo-td">
                        <div class="photo-box">
                            @if($studentPhotoBase64)
                                <img src="{{ $studentPhotoBase64 }}">
                            @else
                                <div style="text-align: center; padding-top: 10mm; font-size: 2mm; color: #64748b;">No Photo</div>
                            @endif
                        </div>
                    </td>
                    <td class="data-td">
                        <div class="label">Nama Lengkap</div>
                        <div class="value value-name">{{ strtoupper($student->nama_lengkap) }}</div>

                        <div class="label">NISN</div>
                        <div class="value">{{ $student->nisn ?? '-' }}</div>

                        <div class="label">Tempat, Tgl Lahir</div>
                        <div class="value">{{ $student->tempat_lahir }}, {{ tanggal_indonesia($student->tanggal_lahir) }}</div>

                        <div class="label">Kelas / Rombel</div>
                        <div class="value">{{ $student->kelas_lengkap }}</div>
                    </td>
                    <td style="width: 13mm; text-align: right;">
                        <div class="qr-box">
                            @if($qrCodeBase64)
                                <img src="{{ $qrCodeBase64 }}" style="width: 100%; height: 100%; display: block;">
                            @else
                                <div style="font-size: 1.5mm; color: #000; padding-top: 4mm;">!</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Footer Signature -->
            <div style="position: absolute; bottom: 7mm; right: 4mm;">
                <div class="signature-area">
                    <div class="sig-city">{{ $general->city ?? 'Tegal' }}, {{ tanggal_indonesia(now()) }}</div>
                    <div class="sig-role">Kepala Madrasah,</div>
                    <div class="sig-name">{{ $kepala->name ?? 'KEPALA MADRASAH' }}</div>
                    <div class="sig-nip">NIP. {{ $kepala->nip ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BELAKANG -->
    <div class="card">
        <div class="card-bg"></div>
        <div class="content">
            <div class="back-title">Tata Tertib Kartu</div>
            <ul class="rules">
                <li>Kartu ini adalah identitas resmi siswa <strong>{{ $mailSetting->school_name ?? 'Madrasah' }}</strong>.</li>
                <li>Wajib dibawa setiap hari dan digunakan untuk presensi digital.</li>
                <li>Dilarang mencoret, merusak, atau meminjamkan kartu.</li>
                <li>Jika hilang/rusak, segera lapor ke bagian Tata Usaha.</li>
                <li>Kartu harus dikembalikan jika siswa sudah lulus atau pindah.</li>
                <li>Temuan kartu harap dikembalikan ke alamat di bawah ini.</li>
            </ul>

            <div class="footer-info">
                {{ $mailSetting->address ?? 'Alamat Madrasah' }}<br>
                Telp: {{ $mailSetting->phone ?? '-' }} | Web: {{ $mailSetting->website ?? '-' }}
            </div>
        </div>
    </div>

    @if(!isset($isPdf) || !$isPdf)
        <button onclick="window.print()" class="print-btn">Cetak Kartu</button>
    @endif
</body>
</html>
