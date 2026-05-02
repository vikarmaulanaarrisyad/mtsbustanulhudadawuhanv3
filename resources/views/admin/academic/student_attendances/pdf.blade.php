<!DOCTYPE html>
<html>
<head>
    <title>Laporan Presensi Siswa - {{ $date }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10px; }
        .info { margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .status-hadir { color: green; font-weight: bold; }
        .status-terlambat { color: orange; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; }
        .signature { margin-top: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $setting->company_name ?? 'SMART MADRASAH' }}</h2>
        <p>{{ $setting->address ?? 'Alamat Madrasah Belum Diatur' }}</p>
        <p>Email: {{ $setting->email ?? '-' }} | Telp: {{ $setting->phone ?? '-' }}</p>
    </div>

    <div class="info">
        <h3 style="text-align: center; text-decoration: underline;">LAPORAN PRESENSI SISWA</h3>
        <table width="100%">
            <tr>
                <td width="15%">Tanggal</td>
                <td width="2%">:</td>
                <td>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $classGroup->kelas_lengkap ?? 'Semua Kelas' }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="15%">NIS</th>
                <th>NAMA LENGKAP</th>
                <th width="15%">KELAS</th>
                <th width="10%">JAM</th>
                <th width="15%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $item->student->nis ?? '-' }}</td>
                    <td>{{ $item->student->nama_lengkap ?? '-' }}</td>
                    <td style="text-align: center;">{{ $item->classGroup->kelas_lengkap ?? '-' }}</td>
                    <td style="text-align: center;">{{ $item->time }}</td>
                    <td style="text-align: center;">
                        @if($item->status == 'present')
                            HADIR
                        @elseif($item->status == 'late')
                            TERLAMBAT
                        @else
                            {{ strtoupper($item->status) }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data presensi pada tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        <div class="signature">
            <p>Kepala Madrasah,</p>
            <br><br><br>
            <p><strong>( __________________________ )</strong></p>
        </div>
    </div>
</body>
</html>
