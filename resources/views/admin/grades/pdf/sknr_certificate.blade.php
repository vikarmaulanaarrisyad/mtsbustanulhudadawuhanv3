@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Nilai Raport'])

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
            margin: 5px 0 5px 15px;
        }
        .info-table td {
            padding: 2px 0;
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .grade-table th, .grade-table td {
            border: 1px solid black;
            padding: 1px 3px;
            font-size: 8pt;
        }
        .grade-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .category-row {
            background-color: #f9f9f9;
            font-weight: bold;
            text-transform: uppercase;
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
        .text-center { text-align: center; }
    </style>

    <div class="skl-header">
        <h3>SURAT KETERANGAN NILAI RAPORT</h3>
        <p style="margin-top: 5px;">Nomor : {{ $student->registration_number ?: (str_pad($student->id, 3, '0', STR_PAD_LEFT) . ' / SKNR / ' . rtrim($general->school_code ?? 'MI-BUHUD01', '/') . ' / ' . date('Y')) }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala {{ $appSetting->school_level ?? 'Madrasah Ibtidaiyah' }} {{ $setting->school_name ?? 'Bustanul Huda' }}, Kabupaten {{ $appSetting->city ?? 'Tegal' }}, Provinsi {{ $appSetting->province ?? 'Jawa Tengah' }} menerangkan dengan sesungguhnya bahwa :</p>

        <table class="info-table">
            <tr>
                <td width="35%">Nama</td>
                <td width="2%">:</td>
                <td><strong>{{ strtoupper($student->nama_lengkap) }}</strong></td>
            </tr>
            <tr>
                <td>Tempat dan tanggal lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }}, {{ \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa Nasional</td>
                <td>:</td>
                <td>{{ $student->nisn }}</td>
            </tr>
        </table>

        <p>memiliki nilai rapor semester 7 sampai dengan semester 11 sebagai berikut :</p>

        <table class="grade-table">
            <thead>
                <tr>
                    <th rowspan="2" width="5%">No</th>
                    <th rowspan="2">Mata Pelajaran</th>
                    <th colspan="{{ count($semesterMap) }}">Nilai Rapor Semester</th>
                    <th rowspan="2" width="8%">Jumlah</th>
                    <th rowspan="2" width="10%">Rata-rata</th>
                </tr>
                <tr>
                    @foreach($semesterMap as $sem => $info)
                        <th width="6%">{{ $sem }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php 
                    $globalIndex = 1; 
                    $totalAllScores = array_fill_keys(array_keys($semesterMap), 0);
                    $totalSum = 0;
                    $totalAvg = 0;
                    $rowCount = 0;
                @endphp

                @foreach($groupedGrades as $category => $subjects)
                    @if(!in_array(strtolower($target ?? ''), ['smp', 'sma', 'negeri', 'umum', 'nasional']))
                        <tr class="category-row">
                            <td colspan="{{ 4 + count($semesterMap) }}">{{ $category }}</td>
                        </tr>
                    @endif
                    
                    @php
                        $isMtsMode = !in_array(strtolower($target), ['smp', 'sma', 'negeri', 'umum']);
                        $paiSubjects = [];
                        $otherSubjects = [];
                        
                        foreach ($subjects as $s) {
                            if ($s['is_agama'] ?? false) $paiSubjects[] = $s;
                            else $otherSubjects[] = $s;
                        }
                    @endphp

                    @if(count($paiSubjects) > 0)
                        {{-- Grouped PAI header --}}
                        <tr>
                            <td class="text-center">{{ $globalIndex++ }}</td>
                            <td style="font-weight: bold;">Pendidikan Agama Islam</td>
                            @foreach($semesterMap as $sem => $info) <td></td> @endforeach
                            <td></td>
                            <td></td>
                        </tr>
                        @php $subLetter = 'a'; @endphp
                        @foreach($paiSubjects as $s)
                            <tr>
                                <td></td>
                                <td style="padding-left: 20px;">{{ $subLetter++ }}. {{ $s['subject'] }}</td>
                                @foreach($semesterMap as $sem => $info)
                                    <td class="text-center">{{ number_format($s['scores'][$sem], 0) }}</td>
                                    @php $totalAllScores[$sem] += $s['scores'][$sem]; @endphp
                                @endforeach
                                <td class="text-center">{{ number_format($s['total'], 0) }}</td>
                                <td class="text-center">{{ number_format($s['nr'], 0) }}</td>
                            </tr>
                            @php 
                                $totalSum += $s['total'];
                                $totalAvg += $s['nr'];
                                $rowCount++;
                            @endphp
                        @endforeach
                    @endif

                    @foreach($otherSubjects as $s)
                        <tr>
                            <td class="text-center">{{ $globalIndex++ }}</td>
                            <td>{{ $s['subject'] }}</td>
                            @foreach($semesterMap as $sem => $info)
                                <td class="text-center">{{ number_format($s['scores'][$sem], 0) }}</td>
                                @php $totalAllScores[$sem] += $s['scores'][$sem]; @endphp
                            @endforeach
                            <td class="text-center">{{ number_format($s['total'], 0) }}</td>
                            <td class="text-center">{{ number_format($s['nr'], 0) }}</td>
                        </tr>
                        @php 
                            $totalSum += $s['total'];
                            $totalAvg += $s['nr'];
                            $rowCount++;
                        @endphp
                    @endforeach
                @endforeach

                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="2" class="text-center">Jumlah</td>
                    @foreach($semesterMap as $sem => $info)
                        <td class="text-center">{{ number_format($totalAllScores[$sem], 0) }}</td>
                    @endforeach
                    <td class="text-center">{{ number_format($totalSum, 0) }}</td>
                    <td class="text-center">{{ number_format($totalAvg, 0) }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="2" class="text-center">Rata-rata Total</td>
                    @foreach($semesterMap as $sem => $info)
                        <td class="text-center">{{ $rowCount > 0 ? number_format($totalAllScores[$sem] / $rowCount, 2) : '-' }}</td>
                    @endforeach
                    <td class="text-center">{{ $rowCount > 0 ? number_format($totalSum / $rowCount, 2) : '-' }}</td>
                    <td class="text-center">{{ $rowCount > 0 ? number_format($totalAvg / $rowCount, 2) : '-' }}</td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 15px;">Demikian Surat Keterangan Nilai Rapor ini dibuat untuk digunakan sebagai persyaratan Penerimaan Peserta Didik Baru (PPDB).</p>
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
                <div style="height: 70px;"></div>
                <p><strong><u>{{ $kepala->name ?? ($general->default_signer_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                NIP. {{ $kepala->nip ?? ($general->default_signer_nip ?? '-') }}</p>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
