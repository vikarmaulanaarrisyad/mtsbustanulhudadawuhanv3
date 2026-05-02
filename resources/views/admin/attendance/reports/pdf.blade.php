<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi Guru</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .footer { margin-top: 30px; text-align: right; }
        .footer-box { display: inline-block; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $setting->company_name ?? 'MTs. BUSTANUL HUDA' }}</h2>
        <p>{{ $setting->company_address ?? 'Dawuhan, Situbondo' }}</p>
        <p>Email: {{ $setting->company_email ?? '-' }} | Telp: {{ $setting->company_phone ?? '-' }}</p>
    </div>

    <h3 style="text-align: center;">LAPORAN PRESENSI GURU & STAF</h3>
    
    <table style="border: none; margin-bottom: 15px;">
        <tr style="border: none;">
            <td style="border: none; width: 15%;">Nama Guru</td>
            <td style="border: none; width: 2%;">:</td>
            <td style="border: none;">{{ $teacher->name ?? 'Semua Guru' }}</td>
            <td style="border: none; width: 15%;">Periode</td>
            <td style="border: none; width: 2%;">:</td>
            <td style="border: none;">{{ $request->start_date }} s.d {{ $request->end_date }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>Nama Guru</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $a)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td align="center">{{ $a->date->format('d/m/Y') }}</td>
                <td>{{ $a->teacher->name }}</td>
                <td align="center">{{ $a->check_in ?? '-' }}</td>
                <td align="center">{{ $a->check_out ?? '-' }}</td>
                <td align="center">{{ $a->status_label }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-box">
            <p>Dawuhan, {{ date('d F Y') }}</p>
            <p>Kepala Madrasah,</p>
            <div style="height: 60px;"></div>
            <p><strong>{{ \App\Models\MailSetting::first()->default_signer_name ?? 'KEPALA MADRASAH' }}</strong></p>
        </div>
    </div>
</body>
</html>
