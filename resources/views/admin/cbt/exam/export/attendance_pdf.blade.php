<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hadir - {{ $exam->name }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 2px 0; }
        .content-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 8px 5px; }
        .content-table th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; }
        .footer-table { width: 100%; }
        .footer-table td { width: 50%; text-align: center; }
        .signature-box { height: 60px; }
    </style>
</head>
<body>
@php
    $groupedStudents = $students->groupBy(function($item) {
        return "Wave:{$item->cbt_wave}|Session:{$item->cbt_session}|Room:{$item->cbt_room}";
    });
@endphp

@foreach($groupedStudents as $groupKey => $studentGroup)
    @php
        $parts = explode('|', $groupKey);
        $wVal = str_replace('Wave:', '', $parts[0]);
        $sVal = str_replace('Session:', '', $parts[1]);
        $rVal = str_replace('Room:', '', $parts[2]);
    @endphp

    @php
        $logo = $mailSetting->logo ?? null;
        $logoPath = null;
        if ($logo) {
            $logoPath = storage_path('app/public/' . $logo);
            if (!file_exists($logoPath)) {
                $logoPath = public_path('storage/' . $logo);
            }
        }
    @endphp

    <div style="border-bottom: 2.5px solid #000; padding-bottom: 8px; margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 80px; vertical-align: middle; text-align: center;">
                    @if ($logoPath && file_exists($logoPath))
                        <img src="{{ $logoPath }}" style="width: 70px;">
                    @else
                        <div style="width: 70px; height: 70px; background: #eee; border: 1px dashed #ccc; text-align: center; line-height: 70px; font-size: 8pt;">LOG0</div>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: middle; padding-right: 80px;">
                    <h2 style="margin: 0; font-size: 12pt; font-weight: normal; text-transform: uppercase; line-height: 1.2;">{{ $mailSetting->sub_header ?? 'KEMENTERIAN AGAMA' }}</h2>
                    <h1 style="margin: 2px 0; font-size: 16pt; text-transform: uppercase; font-weight: bold; line-height: 1.2;">{{ $mailSetting->school_name ?? $setting->name }}</h1>
                    <p style="margin: 1px 0; font-size: 9pt; line-height: 1.2;">{{ $mailSetting->address ?? $setting->address }}</p>
                    <p style="margin: 1px 0; font-size: 9pt; line-height: 1.2;">Telp: {{ $mailSetting->phone ?? $setting->phone }} | Email: {{ $mailSetting->email ?? $setting->email }}</p>
                </td>
            </tr>
        </table>
        <div style="border-top: 1px solid #000; margin-top: 2px;"></div>
    </div>

    <h3 style="text-align: center; text-decoration: underline; margin-bottom: 15px;">DAFTAR HADIR PESERTA UJIAN</h3>

    <table class="info-table">
        <tr>
            <td width="15%">Mata Pelajaran</td><td width="2%">:</td><td width="33%"><strong>{{ $exam->bank->subject->name ?? $exam->name }}</strong></td>
            <td width="15%">Ruang</td><td width="2%">:</td><td width="33%"><strong>{{ $rVal ?: '-' }}</strong></td>
        </tr>
        <tr>
            <td>Tanggal</td><td>:</td><td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d F Y') }}</td>
            <td>Sesi</td><td>:</td><td><strong>{{ $sVal ?: '-' }}</strong></td>
        </tr>
        <tr>
            <td>Waktu</td><td>:</td><td>
                @if(isset($sessionTimes[$sVal]))
                    {{ \Carbon\Carbon::parse($sessionTimes[$sVal]->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sessionTimes[$sVal]->end_time)->format('H:i') }} WIB
                @else
                    {{ $exam->start_time }} - {{ $exam->end_time }}
                @endif
            </td>
            <td>Gelombang</td><td>:</td><td><strong>{{ $wVal ?: '-' }}</strong></td>
        </tr>
        <tr>
            <td>Tingkat</td><td>:</td><td>{{ $exam->bank->class_level }}</td>
            <td></td><td></td><td></td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No Peserta</th>
                <th width="35%">Nama Lengkap</th>
                <th width="15%">Kelas</th>
                <th width="25%" colspan="2">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentGroup as $index => $student)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td align="center">{{ $student->nisn }}</td>
                <td>{{ strtoupper($student->nama_lengkap) }}</td>
                <td align="center">{{ $student->classGroup->class_group ?? '-' }}</td>
                <td width="12%" style="border-right: none; padding-top: 15px; vertical-align: top;">
                    @if(($index + 1) % 2 != 0)
                        {{ $index + 1 }}. .....................
                    @endif
                </td>
                <td width="12%" style="border-left: none; padding-top: 15px; vertical-align: top;">
                    @if(($index + 1) % 2 == 0)
                        {{ $index + 1 }}. .....................
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table" style="width: 100%; border: none;">
            <tr>
                <td style="width: 33%; text-align: center; border: none; vertical-align: top;">
                    Pengawas
                    <div class="signature-box" style="height: 60px;"></div>
                    <strong>( {{ $duty->supervisor->name ?? '................................' }} )</strong><br>
                    NIP. {{ $duty->supervisor->nip ?? '................................' }}
                </td>
                <td style="width: 33%; text-align: center; border: none; vertical-align: top;">
                    Proktor
                    <div class="signature-box" style="height: 60px;"></div>
                    <strong>( {{ $duty->proctor->name ?? '................................' }} )</strong><br>
                    NIP. {{ $duty->proctor->nip ?? '................................' }}
                </td>
                <td style="width: 33%; text-align: center; border: none; vertical-align: top;">
                    {{ $setting->city ?? 'Jember' }}, {{ date('d F Y') }}<br>
                    Mengetahui, Kepala Madrasah
                    <div class="signature-box" style="height: 60px;"></div>
                    <strong><u>{{ $headmaster->name ?? ($setting->headmaster_name ?? '................................') }}</u></strong><br>
                    NIP. {{ $headmaster->nip ?? ($setting->headmaster_nip ?? '-') }}
                </td>
            </tr>
        </table>
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
