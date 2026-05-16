<!DOCTYPE html>
<html>
<head>
    <title>Berita Acara - {{ $exam->name }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; }
        .content { margin: 0 40px; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 14px; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .signature-section { margin-top: 40px; }
        .sig-table { width: 100%; }
        .sig-table td { width: 50%; vertical-align: top; text-align: center; }
        .sig-box { height: 80px; }
        table.stats { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table.stats td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
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
                    <h2 style="margin: 0; font-size: 11pt; font-weight: normal; text-transform: uppercase; line-height: 1.2;">{{ $mailSetting->sub_header ?? 'KEMENTERIAN AGAMA' }}</h2>
                    <h1 style="margin: 2px 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; line-height: 1.2;">{{ $mailSetting->school_name ?? $setting->name }}</h1>
                    <p style="margin: 1px 0; font-size: 8pt; line-height: 1.2;">{{ $mailSetting->address ?? $setting->address }}</p>
                    <p style="margin: 1px 0; font-size: 8pt; line-height: 1.2;">Telp: {{ $mailSetting->phone ?? $setting->phone }} | Email: {{ $mailSetting->email ?? $setting->email }}</p>
                </td>
            </tr>
        </table>
        <div style="border-top: 1px solid #000; margin-top: 2px;"></div>
    </div>

    <div class="content">
        <div class="title">BERITA ACARA PELAKSANAAN UJIAN</div>

        <div class="section">
            Pada hari ini <strong>{{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('l') }}</strong> 
            tanggal <strong>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d') }}</strong> 
            bulan <strong>{{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('F') }}</strong> 
            tahun <strong>{{ \Carbon\Carbon::parse($exam->exam_date)->format('Y') }}</strong>, 
            telah diselenggarakan Ujian Berbasis Komputer (CBT) untuk:
        </div>

        <table style="width: 100%; margin-bottom: 20px;">
            <tr><td width="30%">Mata Pelajaran</td><td>: {{ $exam->bank->subject->name ?? $exam->name }}</td></tr>
            <tr><td>Tingkat / Kelas</td><td>: {{ $exam->bank->class_level }} / {{ $exam->classes->pluck('class_group')->implode(', ') }}</td></tr>
            <tr><td>Waktu Pelaksanaan</td><td>: {{ \Carbon\Carbon::parse($stats['start_time'])->format('H:i') }} s/d {{ \Carbon\Carbon::parse($stats['end_time'])->format('H:i') }} WIB</td></tr>
            <tr><td>Gelombang / Sesi</td><td>: {{ $stats['wave'] ?: '-' }} / {{ $stats['session'] ?: '-' }}</td></tr>
            <tr><td>Ruang</td><td>: {{ $stats['room'] ?: 'Semua Ruang' }}</td></tr>
        </table>

        <div class="section">
            <strong>I. DATA PESERTA:</strong>
            <table class="stats">
                <tr><td>Jumlah Peserta Seharusnya</td><td>: {{ $stats['total'] }} Orang</td></tr>
                <tr><td>Jumlah Peserta Hadir</td><td>: {{ $stats['present'] }} Orang</td></tr>
                <tr><td>Jumlah Peserta Tidak Hadir</td><td>: {{ $stats['absent'] }} Orang</td></tr>
            </table>
        </div>

        <div class="section">
            <strong>II. DAFTAR PESERTA TIDAK HADIR:</strong>
            <div style="border: 1px solid #ccc; min-height: 40px; padding: 10px; margin-top: 5px; font-style: italic;">
                @if($stats['absent_manual'])
                    {{ $stats['absent_manual'] }}
                @elseif($stats['absent_list_auto'])
                    {{ $stats['absent_list_auto'] }}
                @else
                    - Nihil -
                @endif
            </div>
        </div>

        <div class="section">
            <strong>III. CATATAN SELAMA UJIAN:</strong>
            <div style="border: 1px solid #ccc; min-height: 80px; padding: 10px; margin-top: 5px;">
                {{ $stats['notes'] }}
            </div>
        </div>

        <div class="section">
            Berita acara ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya.
        </div>

        <div class="signature-section">
            <table class="sig-table">
                <tr>
                    <td>
                        Saksi I (Pengawas)<br>
                        <div class="sig-box"></div>
                        ( <strong>{{ $stats['supervisor'] }}</strong> )<br>
                        NIP. ........................................
                    </td>
                    <td>
                        Proktor<br>
                        <div class="sig-box"></div>
                        ( <strong>{{ $stats['proctor'] }}</strong> )<br>
                        NIP. ........................................
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 30px;">
                        Mengetahui,<br>
                        Kepala Madrasah
                        <div class="sig-box"></div>
                        <strong><u>{{ $setting->headmaster_name ?? '................................' }}</u></strong><br>
                        NIP. {{ $setting->headmaster_nip ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
