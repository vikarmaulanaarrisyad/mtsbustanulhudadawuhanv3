<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan PKG - {{ $teacher->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #000; line-height: 1.2; }
        .page-break { page-break-after: always; }
        .header-title { text-align: center; font-weight: bold; font-size: 12pt; text-decoration: underline; margin-bottom: 20px; text-transform: uppercase; }
        .section-title { background-color: #f0f0f0; padding: 8px; font-weight: bold; border: 1.5px solid #000; text-align: center; margin-bottom: 10px; font-size: 11pt; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-border th, .table-border td { border: 1px solid #000; padding: 5px 8px; vertical-align: top; }
        .bg-gray { background-color: #f5f5f5; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        
        .info-table td { padding: 2px 4px; vertical-align: top; }
        .info-table td:first-child { width: 25px; }
        .info-table td:nth-child(2) { width: 200px; }
        .info-table td:nth-child(3) { width: 10px; }

        .signature-table { margin-top: 30px; border: none; }
        .signature-table td { width: 50%; text-align: center; vertical-align: bottom; height: 100px; border: none !important; }
        .signature-name { text-decoration: underline; font-weight: bold; margin-top: 60px; display: block; }
        
        .box-border { border: 1.5px solid #000; padding: 10px; }
        @page { margin: 1.5cm; }
    </style>
</head>
<body>

    <!-- PAGE 1: IDENTITAS -->
    <div class="header-title">I. IDENTITAS GURU DAN PENILAI</div>
    
    <div class="box-border">
        <p class="font-bold">A. IDENTITAS GURU YANG DINILAI</p>
        <table class="info-table">
            <tr><td>1.</td><td>Nama dan Gelar</td><td>:</td><td>{{ $teacher->name }}</td></tr>
            <tr><td>2.</td><td>NIP</td><td>:</td><td>{{ $teacher->nip ?? '-' }}</td></tr>
            <tr><td>3.</td><td>Pangkat/Golongan/Ruang</td><td>:</td><td>{{ $teacher->rank ?? '-' }}</td></tr>
            <tr><td>4.</td><td>Jabatan Fungsional</td><td>:</td><td>{{ $teacher->position ?? '-' }}</td></tr>
            <tr><td>5.</td><td>NUPTK</td><td>:</td><td>{{ $teacher->nuptk ?? '-' }}</td></tr>
            <tr><td>6.</td><td>Mata Pelajaran yang diampu</td><td>:</td><td>{{ $teacher->subject ?? '-' }}</td></tr>
            <tr><td>7.</td><td>Periode Penilaian</td><td>:</td><td>{{ $currentAY->academic_year }}</td></tr>
        </table>

        <p class="font-bold" style="margin-top: 20px;">B. IDENTITAS MADRASAH</p>
        <table class="info-table">
            <tr><td>1.</td><td>Nama Madrasah</td><td>:</td><td>{{ $setting->name ?? 'MTS BUSTANUL HUDA' }}</td></tr>
            <tr><td>2.</td><td>NSS/NSM</td><td>:</td><td>{{ $setting->nsm ?? '-' }}</td></tr>
            <tr><td>3.</td><td>Status</td><td>:</td><td>{{ $setting->status ?? 'Swasta' }}</td></tr>
            <tr><td>4.</td><td>Alamat Madrasah</td><td>:</td><td>{{ $setting->address ?? '-' }}</td></tr>
            <tr><td></td><td>Kecamatan</td><td>:</td><td>{{ $setting->district ?? '-' }}</td></tr>
            <tr><td></td><td>Kabupaten/Kota</td><td>:</td><td>{{ $setting->city ?? '-' }}</td></tr>
            <tr><td></td><td>Provinsi</td><td>:</td><td>{{ $setting->province ?? '-' }}</td></tr>
        </table>

        <p class="font-bold" style="margin-top: 20px;">C. DATA PENILAIAN</p>
        <table class="info-table">
            <tr><td>1.</td><td>Tanggal Penilaian</td><td>:</td><td>{{ date('d F Y') }}</td></tr>
            <tr><td>2.</td><td>Nama Penilai</td><td>:</td><td>{{ $assessor->name ?? 'Kepala Madrasah' }}</td></tr>
            <tr><td>3.</td><td>Jabatan</td><td>:</td><td>Kepala Madrasah</td></tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- PAGE 2: HASIL PENILAIAN -->
    <div class="header-title">VII. HASIL PENILAIAN KINERJA</div>
    
    <table class="table-border">
        <thead>
            <tr class="bg-gray">
                <th width="40">NO</th>
                <th>DIMENSI TUGAS UTAMA / INDIKATOR KINERJA GURU</th>
                <th width="100">NILAI KINERJA</th>
            </tr>
        </thead>
        <tbody>
            @php $globalIndex = 1; @endphp
            @foreach($groupedDetails as $category => $items)
                <tr class="bg-gray">
                    <td class="font-bold text-center">{{ chr(64 + $loop->iteration) }}</td>
                    <td class="font-bold text-uppercase">{{ $category }}</td>
                    <td></td>
                </tr>
                @php $catSubtotal = 0; @endphp
                @foreach($items as $item)
                    <tr>
                        <td class="text-center">{{ $globalIndex++ }}</td>
                        <td>{{ $item->indicator_text }}</td>
                        <td class="text-center font-bold">{{ number_format($item->avg_score, 0) }}</td>
                    </tr>
                    @php $catSubtotal += $item->avg_score; @endphp
                @endforeach
                <tr style="background-color: #fef3c7;">
                    <td></td>
                    <td class="font-bold">Sub Total Nilai Kinerja {{ $category }}</td>
                    <td class="text-center font-bold">{{ number_format($catSubtotal, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table-border">
        <tr>
            <td class="font-bold text-uppercase">Total Nilai Kinerja Guru</td>
            <td width="100" class="text-center font-bold" style="background-color: #f3f4f6; border-left: none;">{{ number_format($totalScore, 0) }}</td>
        </tr>
        <tr>
            <td class="font-bold text-uppercase">Konversi Total Nilai Kinerja Guru ke Skala 100</td>
            <td class="text-center font-bold" style="background-color: #f3f4f6;">{{ number_format($finalPercentage, 1) }}</td>
        </tr>
        <tr>
            <td class="font-bold text-uppercase">Kategori Nilai Kinerja Guru</td>
            <td class="text-center font-bold text-uppercase" style="background-color: #f3f4f6;">
                @php
                    $predikat = 'Kurang';
                    if($finalPercentage >= 90) $predikat = 'Amat Baik';
                    elseif($finalPercentage >= 75) $predikat = 'Baik';
                    elseif($finalPercentage >= 60) $predikat = 'Cukup';
                    elseif($finalPercentage >= 50) $predikat = 'Sedang';
                @endphp
                {{ $predikat }}
            </td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Guru yang Dinilai,<br><br><br>
                <span class="signature-name">{{ $teacher->name }}</span>
                NIP. {{ $teacher->nip ?? '-' }}
            </td>
            <td>
                {{ $setting->city ?? 'Jember' }}, {{ date('d F Y') }}<br>
                Penilai,<br><br><br>
                <span class="signature-name">{{ $assessor->name ?? '..........................' }}</span>
                NIP. {{ $assessor->teacher->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
