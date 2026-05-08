<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kinerja Guru</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-center { text-align: center; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-primary { background-color: #dbeafe; color: #1e40af; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .footer { margin-top: 50px; text-align: right; }
        .footer .signature { margin-top: 80px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Peringkat Kinerja Guru (PKG)</h2>
        <p>Tahun Pelajaran: {{ $currentAY->academic_year ?? '-' }} ({{ $currentAY->semester->semester_name ?? 'Semester' }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30" class="text-center">#</th>
                <th>Nama Guru</th>
                <th>NIP</th>
                <th width="80" class="text-center">Skor (%)</th>
                <th width="100" class="text-center">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankings as $index => $rank)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $rank->teacher->name ?? '-' }}</strong></td>
                <td>{{ $rank->teacher->nip ?? '-' }}</td>
                <td class="text-center">{{ number_format($rank->final_score, 1) }}%</td>
                <td class="text-center">
                    @php
                        $predikat = 'Kurang';
                        $class = 'danger';
                        if($rank->final_score >= 90) { $predikat = 'Amat Baik'; $class = 'success'; }
                        elseif($rank->final_score >= 75) { $predikat = 'Baik'; $class = 'primary'; }
                        elseif($rank->final_score >= 60) { $predikat = 'Cukup'; $class = 'warning'; }
                    @endphp
                    <span class="badge badge-{{ $class }}">{{ $predikat }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
        <div style="width: 200px; float: right; text-align: center; margin-top: 20px;">
            <p>Kepala Madrasah,</p>
            <div class="signature">__________________________</div>
        </div>
    </div>
</body>
</html>
