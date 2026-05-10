<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi Prestasi Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 9pt; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; }
        .signature { float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAPITULASI PRESTASI SISWA</h2>
        <h3>{{ $setting->school_name ?? 'MTs BUSTANUL HUDA' }}</h3>
        <p>{{ $setting->school_address ?? 'Dawuhan, Pilangkenceng, Madiun' }}</p>
    </div>

    <p>Filter Status: <strong>{{ strtoupper($request->status ?: 'SEMUA') }}</strong></p>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="30">NO</th>
                <th>SISWA</th>
                <th>KELAS</th>
                <th>PRESTASI</th>
                <th>EVENT / KEGIATAN</th>
                <th>TINGKAT</th>
                <th>KATEGORI</th>
                <th>TAHUN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($achievements as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->student->nama_lengkap ?? ($row->student_name ?: '-') }}</td>
                    <td class="text-center">{{ $row->student->classGroup->group_name ?? '-' }}</td>
                    <td>{{ $row->title }} ({{ $row->rank }})</td>
                    <td>{{ $row->event_name }}</td>
                    <td>{{ $row->level }}</td>
                    <td>{{ $row->category }}</td>
                    <td class="text-center">{{ $row->year }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Madiun, {{ date('d F Y') }}</p>
            <p>Kepala Madrasah,</p>
            <br><br><br>
            <p><strong>( ____________________ )</strong></p>
        </div>
    </div>
</body>
</html>
