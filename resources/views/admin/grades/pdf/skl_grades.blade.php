@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Lulus'])

@section('main-content')
    <style>
        .header-wrapper {
            position: relative;
            margin-bottom: 10px;
        }

        .header-logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: auto;
        }

        .skl-header {
            text-align: center;
        }

        .skl-header h3 {
            margin: 0;
            text-decoration: underline;
            font-size: 14pt;
            font-weight: bold;
        }

        .skl-header p {
            margin: 0;
            font-size: 11pt;
        }

        .content {
            font-size: 9.5pt;
            line-height: 1.15;
        }

        .info-table {
            width: 100%;
            margin: 2px 0 2px 10px;
        }

        .info-table td {
            padding: 2px 0;
        }

        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .grade-table th,
        .grade-table td {
            border: 1px solid black;
            padding: 1px 4px;
            font-size: 8pt;
        }

        .grade-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .category-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .footer-container {
            margin-top: -15px;
            width: 100%;
        }

        .footer-right {
            float: right;
            width: 380px;
            position: relative;
        }

        .photo-box {
            width: 3cm;
            height: 4cm;
            border: 1px solid #ccc;
            text-align: center;
            line-height: 4cm;
            font-size: 8pt;
            color: #999;
            float: left;
            margin-top: 0;
        }

        .signature-box {
            float: right;
            width: 240px;
            text-align: left;
        }

        .signature-box p {
            margin: 0;
        }

        .text-center {
            text-align: center;
        }
    </style>

    <div class="skl-header">
        <h3>SURAT KETERANGAN LULUS</h3>
        <p style="margin-top: 5px;">Nomor :
            {{ $student->skl_number ?: str_pad($student->id, 3, '0', STR_PAD_LEFT) . ' / SKL / ' . rtrim($general->school_code ?? 'MI-BUHUD01', '/') . ' / ' . date('Y') }}
        </p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala {{ $appSetting->school_level ?? 'Madrasah Ibtidaiyah' }}
            {{ $setting->school_name ?? 'Bustanul Huda' }}, Kabupaten {{ $appSetting->city ?? 'Tegal' }}, Provinsi
            {{ $appSetting->province ?? 'Jawa Tengah' }} menerangkan dengan sesungguhnya bahwa :</p>

        <table class="info-table">
            <tr>
                <td width="35%">Nama</td>
                <td width="2%">:</td>
                <td><strong>{{ strtoupper($student->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td>Tempat dan tanggal lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Nama orang tua / wali</td>
                <td>:</td>
                <td>{{ ($student->parents ?? collect())->where('type', 'Ayah')->first()->name ?? (($student->parents ?? collect())->where('type', 'Ibu')->first()->name ?? '-') }}
                </td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa Madrasah</td>
                <td>:</td>
                <td>{{ $student->nis }}</td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa Nasional</td>
                <td>:</td>
                <td>{{ $student->nisn }}</td>
            </tr>
            <tr>
                <td>Nomor Peserta Ujian Madrasah</td>
                <td>:</td>
                <td>{{ $student->registration_number ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPSN Madrasah</td>
                <td>:</td>
                <td>{{ $appSetting->npsn ?? '-' }}</td>
            </tr>
        </table>

        <p>Dinyatakan <strong>LULUS</strong> dari satuan pendidikan berdasarkan kriteria kelulusan
            {{ $appSetting->school_level ?? 'Madrasah Ibtidaiyah' }} {{ $setting->school_name ?? 'Bustanul Huda' }} Tahun
            Pelajaran {{ $student->academicYear->name ?? '2021/2022' }}, dengan nilai rata-rata raport dan nilai ujian
            madrasah sebagai berikut :</p>

        <table class="grade-table">
            <thead>
                <tr>
                    <th width="5%">NO.</th>
                    <th>MATA PELAJARAN</th>
                    <th width="20%">NILAI</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $globalIndex = 1;
                    $totalScore = 0;
                    $subjectCount = 0;
                @endphp

                @foreach ($groupedGrades as $category => $subjects)
                    @if (count($subjects) > 0)
                        @if (!in_array(strtolower($level ?? ''), ['smp', 'sma', 'negeri', 'umum', 'nasional']))
                            <tr class="category-row">
                                <td colspan="3">{{ $category }}</td>
                            </tr>
                        @endif

                        @php
                            $isMtsMode = !in_array(strtolower($level), ['smp', 'sma', 'negeri', 'umum']);
                            $paiSubjects = [];
                            $otherSubjects = [];

                            foreach ($subjects as $s) {
                                if ($s['is_agama']) {
                                    $paiSubjects[] = $s;
                                } else {
                                    $otherSubjects[] = $s;
                                }
                            }
                        @endphp

                        @if (count($paiSubjects) > 0)
                            {{-- Grouped PAI header --}}
                            <tr>
                                <td style="text-align: center;">{{ $globalIndex++ }}</td>
                                <td style="font-weight: bold;">Pendidikan Agama Islam</td>
                                <td></td>
                            </tr>
                            @php $subLetter = 'a'; @endphp
                            @foreach ($paiSubjects as $s)
                                <tr>
                                    <td></td>
                                    <td style="padding-left: 20px;">{{ $subLetter++ }}. {{ $s['subject'] }}</td>
                                    <td style="text-align: center;">{{ number_format($s['ns'], 0) }}</td>
                                </tr>
                                @php
                                    $totalScore += $s['ns'];
                                    $subjectCount++;
                                @endphp
                            @endforeach
                        @endif

                        @foreach ($otherSubjects as $s)
                            <tr>
                                <td style="text-align: center;">{{ $globalIndex++ }}</td>
                                <td>{{ $s['subject'] }}</td>
                                <td style="text-align: center;">{{ number_format($s['ns'], 0) }}</td>
                            </tr>
                            @php
                                $totalScore += $s['ns'];
                                $subjectCount++;
                            @endphp
                        @endforeach
                    @endif
                @endforeach

                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="2" style="text-align: center;">Rata-rata</td>
                    <td style="text-align: center;">
                        {{ $subjectCount > 0 ? number_format($totalScore / $subjectCount, 2) : 0 }}</td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 15px;">Surat Keterangan Lulus ini berlaku sementara sampai dengan diterbitkannya ijazah Tahun
            Pelajaran {{ $student->academicYear->name ?? '2021/2022' }}, untuk menjadikan maklum bagi yang berkepentingan.
        </p>
    </div>

    <div class="footer-container">
        <div style="float: left; width: 30%;">
            <img src="{{ $qrCode }}" style="width: 70px; height: 70px;">
            <p style="font-size: 8px; color: #666; margin-top: 5px;">
                Scan untuk verifikasi digital.
            </p>
        </div>

        <div class="footer-right">
            <div class="photo-box">Pas foto 3x4</div>

            <div class="signature-box">
                @php
                    $kepala = get_kepala_madrasah();
                    $general = \App\Models\MailSetting::first();
                @endphp
                <p>{{ $appSetting->city ?? 'Tegal' }}, {{ tanggal_indonesia(date('Y-m-d')) }}</p>
                <p>{{ $general->default_signer_position ?? 'Kepala Madrasah' }},</p>
                <div style="height: 50px;"></div>
                <p><strong><u>{{ $kepala->name ?? ($general->default_signer_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                    NIP. {{ $kepala->nip ?? ($general->default_signer_nip ?? '-') }}</p>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
