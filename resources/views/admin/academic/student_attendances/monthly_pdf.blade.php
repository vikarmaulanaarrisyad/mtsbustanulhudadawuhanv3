@extends('admin.mail.pdf.layout', ['title' => 'Rekap Absensi Bulanan - ' . $classGroup->kelas_lengkap])

@section('main-content')
<style>
    body { font-family: 'Times New Roman', Times, serif; font-size: 10px; color: #000; line-height: 1.2; }
    .container { padding: 0 5px !important; width: 100% !important; max-width: 100% !important; }
    
    .report-title { text-align: center; margin-bottom: 15px; margin-top: -15px; }
    .report-title h2 { margin: 0; font-size: 14px; text-decoration: underline; font-weight: bold; }
    
    .info-table { width: 100%; margin-bottom: 8px; font-size: 10pt; }
    .info-table td { padding: 1px 0; }
    
    .main-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .main-table th, .main-table td { border: 1px solid #000; text-align: center; padding: 3px 0; font-size: 7.5px; }
    .main-table th { background-color: #f2f2f2; font-weight: bold; font-size: 8px; }
    
    .name-col { 
        text-align: left !important; 
        padding-left: 4px !important; 
        font-weight: bold; 
        text-transform: uppercase; 
        font-size: 8.5px !important; 
        word-wrap: break-word;
        white-space: normal !important;
        line-height: 1.1;
    }
    
    .status-s { font-weight: bold; } 
    .status-i { font-weight: bold; } 
    .status-a { font-weight: bold; } 
    .status-t { font-weight: bold; } 
    
    .footer { margin-top: 15px; width: 100%; }
    .signature-box { width: 250px; float: right; text-align: left; margin-right: 10px; }
    .signature-space { height: 50px; }
    
    .clear { clear: both; }
    
    /* Warna Kolom Libur */
    .bg-sunday { background-color: #fca5a5 !important; }
    .bg-holiday { background-color: #fde047 !important; }
    .font-bold { font-weight: bold; }

    /* Legenda Warna */
    .legend-box { display: inline-block; width: 10px; height: 10px; border: 1px solid #000; vertical-align: middle; margin-right: 3px; }
    .legend-item { margin-bottom: 3px; font-size: 8px; }

    .kop-surat { margin-bottom: 10px !important; }
</style>

<div class="report-title">
    <h2>REKAPITULASI ABSENSI SISWA BULANAN</h2>
</div>

<table class="info-table">
    <tr>
        <td width="8%">Kelas</td>
        <td width="2%">:</td>
        <td width="40%"><strong>{{ $classGroup->kelas_lengkap }}</strong></td>
        <td width="15%">Bulan / Tahun</td>
        <td width="2%">:</td>
        <td><strong>{{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F') }} / {{ $year }}</strong></td>
    </tr>
</table>

<table class="main-table">
    <thead>
        <tr>
            <th rowspan="2" width="22px">NO</th>
            <th rowspan="2" width="300px">NAMA LENGKAP SISWA</th>
            <th colspan="{{ $daysInMonth }}">TANGGAL</th>
            <th colspan="5" width="85px">REKAP</th>
        </tr>
        <tr>
            @for($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $dateObj = \Carbon\Carbon::createFromDate($year, $month, $i);
                    $isSunday = $dateObj->isSunday();
                    $isHoliday = isset($holidays[$i]);
                @endphp
                <th width="18px" class="{{ $isSunday ? 'bg-sunday' : ($isHoliday ? 'bg-holiday' : '') }}">
                    {{ $i }}
                </th>
            @endfor
            <th width="17px">H</th>
            <th width="17px">S</th>
            <th width="17px">I</th>
            <th width="17px">A</th>
            <th width="17px">T</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $index => $student)
            @php
                $rekap = ['present' => 0, 'sick' => 0, 'permit' => 0, 'absent' => 0, 'late' => 0];
                $studentAttendances = $attendances->get($student->id, collect());
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="name-col">{{ $student->nama_lengkap }}</td>
                @for($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $att = $studentAttendances->get($i)?->first();
                        $symbol = '';
                        $class = '';
                        if ($att) {
                            if ($att->status == 'present') { $symbol = 'H'; $rekap['present']++; }
                            elseif ($att->status == 'sick') { $symbol = 'S'; $rekap['sick']++; }
                            elseif ($att->status == 'permit') { $symbol = 'I'; $rekap['permit']++; }
                            elseif ($att->status == 'absent') { $symbol = 'A'; $rekap['absent']++; }
                            elseif ($att->status == 'late') { $symbol = 'T'; $rekap['late']++; }
                        }
                        
                        $dateObj = \Carbon\Carbon::createFromDate($year, $month, $i);
                        $isSunday = $dateObj->isSunday();
                        $isHoliday = isset($holidays[$i]);
                    @endphp
                    <td class="{{ $isSunday ? 'bg-sunday' : ($isHoliday ? 'bg-holiday' : '') }}">
                        {{ (!$isSunday && !$isHoliday) ? $symbol : '' }}
                    </td>
                @endfor
                <td class="font-bold">{{ $rekap['present'] }}</td>
                <td class="font-bold">{{ $rekap['sick'] }}</td>
                <td class="font-bold">{{ $rekap['permit'] }}</td>
                <td class="font-bold">{{ $rekap['absent'] }}</td>
                <td class="font-bold">{{ $rekap['late'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <div style="float: left; width: 60%;">
        <table style="width: 100%; border: none;">
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <p style="margin-bottom: 3px;"><strong>Keterangan Absensi:</strong></p>
                    <table style="font-size: 8px; border: none;">
                        <tr>
                            <td width="55px">H : Hadir</td>
                            <td width="55px">S : Sakit</td>
                            <td width="55px">I : Izin</td>
                        </tr>
                        <tr>
                            <td>A : Alpa</td>
                            <td>T : Terlambat</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <p style="margin-bottom: 3px;"><strong>Keterangan Warna:</strong></p>
                    <div class="legend-item">
                        <div class="legend-box bg-sunday"></div> Hari Minggu
                    </div>
                    <div class="legend-item">
                        <div class="legend-box bg-holiday"></div> Hari Libur Nasional
                    </div>
                </td>
            </tr>
        </table>
        <p style="font-style: italic; font-size: 7px; margin-top: 8px; color: #666;">
            Dicetak otomatis oleh Sistem Madrasah Digital pada {{ date('d/m/Y H:i') }}
        </p>
    </div>
    <div class="signature-box">
        @php
            $general = \App\Models\Setting::first();
        @endphp
        <p>{{ $general->city ?? 'Tegal' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Wali Kelas,</p>
        <div class="signature-space"></div>
        <p><strong><u>{{ $classGroup->teacher->name ?? '____________________' }}</u></strong></p>
        <p>NIP. {{ $classGroup->teacher->nip ?? '-' }}</p>
    </div>
    <div class="clear"></div>
</div>
@endsection
