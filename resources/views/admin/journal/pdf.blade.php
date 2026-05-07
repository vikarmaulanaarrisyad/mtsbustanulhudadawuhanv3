<!DOCTYPE html>
<html>
<head>
    <title>Jurnal Kegiatan Pembelajaran</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; position: relative; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 18px; }
        .header h3 { margin: 5px 0; font-size: 14px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        
        .title-section { text-align: center; margin-bottom: 15px; }
        .title-section h4 { margin: 0; text-transform: uppercase; font-size: 14px; text-decoration: underline; }
        
        .info-table { width: 100%; margin-bottom: 15px; border: none; }
        .info-table td { border: none; padding: 2px; }
        
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 8px 5px; }
        table.main-table th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; text-align: center; }
        
        .footer { margin-top: 30px; width: 100%; }
        .footer td { border: none; vertical-align: top; }
        .footer-box { text-align: center; width: 250px; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        @page { margin: 1cm; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $setting->company_name ?? 'MTs. BUSTANUL HUDA' }}</h2>
        <h3>{{ $setting->company_sub_name ?? 'MADRASAH TSANAWIYAH' }}</h3>
        <p>{{ $setting->company_address ?? 'Dawuhan, Situbondo, Jawa Timur' }}</p>
        <p>Email: {{ $setting->company_email ?? '-' }} | Telp: {{ $setting->company_phone ?? '-' }}</p>
    </div>

    <div class="title-section">
        <h4>JURNAL KEGIATAN BELAJAR MENGAJAR (KBM)</h4>
    </div>
    
    <table class="info-table">
        <tr>
            <td width="15%">Nama Guru</td>
            <td width="2%">:</td>
            <td width="35%" class="font-bold">{{ $teacher->name ?? 'SEMUA GURU' }}</td>
            <td width="15%">Periode</td>
            <td width="2%">:</td>
            <td>{{ $request->start_date }} s.d {{ $request->end_date }}</td>
        </tr>
        <tr>
            <td>NUPTK</td>
            <td>:</td>
            <td class="font-bold">{{ $teacher->nuptk ?? '-' }}</td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ date('Y') }}/{{ date('Y')+1 }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Hari / Tgl</th>
                <th width="8%">Jam Ke</th>
                <th width="10%">Kelas</th>
                <th width="15%">Mata Pelajaran</th>
                <th width="35%">Ringkasan Materi / Kompetensi Dasar</th>
                <th width="19%">Siswa Tidak Hadir / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journals as $index => $j)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $j->date->translatedFormat('D, d/m/Y') }}</td>
                <td class="text-center">{{ $j->studyPeriod->period_name ?? '-' }}</td>
                <td class="text-center">{{ $j->classGroup->kelas_lengkap ?? '-' }}</td>
                <td>{{ $j->subject->name ?? '-' }}</td>
                <td>{{ $j->material_summary }}</td>
                <td>
                    @if($j->absent_students)
                        <span style="color: red;">Absen:</span> {{ $j->absent_students }}
                    @endif
                    @if($j->student_notes)
                        <br><em>Ket: {{ $j->student_notes }}</em>
                    @endif
                    @if(!$j->absent_students && !$j->student_notes)
                        <span style="color: green;">Nihil</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td width="50%">
                <div class="footer-box" style="text-align: left; margin-left: 50px;">
                    <p>&nbsp;</p>
                    <p>Mengetahui,</p>
                    <p>Kepala Madrasah</p>
                    <div style="height: 60px;"></div>
                    <p class="font-bold">{{ \App\Models\MailSetting::first()->default_signer_name ?? '...........................................' }}</p>
                    <p>NIP. {{ \App\Models\MailSetting::first()->default_signer_nip ?? '...........................................' }}</p>
                </div>
            </td>
            <td width="50%">
                <div class="footer-box" style="float: right;">
                    <p>Dawuhan, {{ date('d F Y') }}</p>
                    <p>&nbsp;</p>
                    <p>Guru Mata Pelajaran,</p>
                    <div style="height: 60px;"></div>
                    <p class="font-bold">{{ $teacher->name ?? '...........................................' }}</p>
                    <p>NUPTK. {{ $teacher->nuptk ?? '...........................................' }}</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
