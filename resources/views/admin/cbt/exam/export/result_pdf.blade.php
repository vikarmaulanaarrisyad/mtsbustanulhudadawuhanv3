<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Ujian - {{ $exam->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 9px; color: white; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; }
        .bg-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN HASIL UJIAN CBT</h2>
        <p>MTS BUSTANUL HUDA DAWUHAN</p>
        <p style="font-weight: bold;">MATA PELAJARAN: {{ $exam->bank->subject->name ?? '-' }} | JADWAL: {{ $exam->name }}</p>
        <p>Tanggal: {{ $exam->exam_date->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">NO</th>
                <th>NAMA SISWA</th>
                <th>NISN</th>
                <th>KELAS</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">PELANGGARAN</th>
                <th width="15%" class="text-center">NILAI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exam->studentExams as $index => $se)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $se->student->nama_lengkap }}</td>
                    <td>{{ $se->student->nisn }}</td>
                    <td>{{ $se->student->classGroup->group_name ?? '-' }}</td>
                    <td class="text-center">
                        {{ strtoupper($se->status) }}
                    </td>
                    <td class="text-center">{{ $se->violation_count }}</td>
                    <td class="text-center" style="font-weight: bold; font-size: 14px;">
                        {{ number_format($se->final_score, 1) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dawuhan, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><b>Administrator CBT</b></p>
    </div>
</body>
</html>
