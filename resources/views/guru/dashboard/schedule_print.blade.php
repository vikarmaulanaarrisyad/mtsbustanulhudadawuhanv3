<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jadwal Mengajar - {{ $teacher->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; color: #1e293b; margin: 0; padding: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #334155; padding-bottom: 15px; position: relative; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 900; text-transform: uppercase; color: #0f172a; }
        .header p { margin: 5px 0 0; color: #64748b; font-weight: 600; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 4px 0; font-weight: 700; }
        .schedule-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .schedule-table th, .schedule-table td { border: 1px solid #cbd5e1; padding: 8px; vertical-align: top; }
        .schedule-table th { background-color: #f1f5f9; font-weight: 900; text-transform: uppercase; font-size: 10px; }
        .day-column { width: 16.66%; }
        .item { margin-bottom: 8px; padding: 6px; background: #f8fafc; border-radius: 4px; border-left: 3px solid #10b981; }
        .item-time { font-size: 9px; font-weight: 900; color: #059669; display: block; margin-bottom: 2px; }
        .item-subject { font-weight: 900; font-size: 11px; color: #1e293b; display: block; }
        .item-class { font-weight: 700; font-size: 9px; color: #64748b; }
        .footer { margin-top: 40px; text-align: right; }
        .signature-box { display: inline-block; text-align: center; width: 200px; }
        .signature-box p { margin: 0; }
        .signature-space { height: 60px; }
        @media print {
            @page { size: landscape; margin: 1cm; }
            .no-print { display: none; }
            body { padding: 0; }
        }
        .btn-print { position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 900; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4); }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">CETAK SEKARANG</button>

    <div class="header">
        <h1>JADWAL MENGAJAR GURU</h1>
        <p>{{ $setting->nama_madrasah ?? 'MADRASAH DIGITAL' }}</p>
        <p>Tahun Akademik: {{ $activeYear->academic_year ?? '-' }} ({{ $activeYear->semester->name ?? '-' }})</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="100">NAMA GURU</td>
            <td width="20">:</td>
            <td>{{ $teacher->name }}</td>
            <td width="100">NIP / NUPTK</td>
            <td width="20">:</td>
            <td>{{ $teacher->nip ?? '-' }} / {{ $teacher->nuptk ?? '-' }}</td>
        </tr>
    </table>

    <table class="schedule-table">
        <thead>
            <tr>
                @php
                    $days = [1 => 'SENIN', 2 => 'SELASA', 3 => 'RABU', 4 => 'KAMIS', 5 => 'JUMAT', 6 => 'SABTU'];
                @endphp
                @foreach($days as $day)
                    <th class="day-column">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($days as $num => $name)
                    <td>
                        @if(isset($schedules[$num]))
                            @foreach($schedules[$num]->sortBy('studyPeriod.period_number') as $item)
                                <div class="item">
                                    <span class="item-time">Jam Ke-{{ $item->studyPeriod->period_number }} ({{ $item->studyPeriod->start_time }} - {{ $item->studyPeriod->end_time }})</span>
                                    <span class="item-subject">{{ $item->subject->name }}</span>
                                    <span class="item-class">Kelas {{ $item->classGroup->kelas_lengkap }}</span>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; color: #cbd5e1; padding-top: 20px;">-</div>
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Madrasah,</p>
            <div class="signature-space"></div>
            <p><strong>__________________________</strong></p>
            <p>NIP. .........................</p>
        </div>
    </div>
</body>
</html>
