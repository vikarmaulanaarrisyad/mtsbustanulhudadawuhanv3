@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Nilai Raport'])

@section('styles')
<style>
    .page-border {
        border: 2px solid #000;
        padding: 30px;
        height: 100%;
    }
    .mail-title {
        text-align: center;
        margin-bottom: 25px;
    }
    .mail-title h3 {
        margin-bottom: 5px;
        text-decoration: underline;
        font-size: 16pt;
        color: #1a1a1a;
    }
    .mail-title p {
        font-size: 11pt;
        margin: 0;
        color: #333;
    }
    .content p {
        margin-bottom: 12px;
        line-height: 1.6;
        font-size: 11pt;
    }
    .info-table {
        width: 100%;
        margin-bottom: 20px;
        margin-left: 10px;
    }
    .info-table td {
        padding: 4px 0;
        vertical-align: top;
        font-size: 11pt;
    }
    .grade-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10pt;
        margin-bottom: 20px;
    }
    .grade-table th, .grade-table td {
        border: 1px solid black;
        padding: 6px 4px;
    }
    .grade-table th {
        background-color: #f2f2f2;
        text-align: center;
        text-transform: uppercase;
        font-weight: bold;
    }
    .category-row {
        background-color: #e9ecef;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 10pt;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
    .italic { font-style: italic; }
    
    .signature-container {
        margin-top: 40px;
        width: 100%;
    }
    .signature-box {
        float: right;
        width: 300px;
        text-align: left;
    }
    .signature-space {
        height: 70px;
    }
</style>
@endsection

@section('main-content')
<div class="page-border">
    <div class="mail-title">
        <h3>SURAT KETERANGAN NILAI RAPORT</h3>
        <p>Nomor: {{ $student->registration_number ?? '...' }} / SKNR / {{ $setting->school_code ?? 'MTs-BH' }} / {{ date('Y') }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala {{ $setting->school_name ?? 'MI Bustanul Huda 01' }} Dawuhan, Kecamatan Talang, Kabupaten Tegal, menerangkan bahwa :</p>

        <table class="info-table">
            <tr>
                <td width="30%">Nama</td>
                <td width="2%">:</td>
                <td class="font-bold">{{ strtoupper($student->nama_lengkap) }}</td>
            </tr>
            <tr>
                <td>Tempat dan Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }}, {{ \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Nomor Induk Siswa Nasional (NISN)</td>
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
                    <th rowspan="2" width="10%">Rata-rata<br>Nilai<br>Semester<br>7-11</th>
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
                    <tr class="category-row">
                        <td colspan="{{ 4 + count($semesterMap) }}">{{ strtoupper($category) }}</td>
                    </tr>
                    
                    @php
                        // Special handling for PAI grouping in MTs/Separate mode
                        $isMtsMode = !in_array(strtolower($target), ['smp', 'sma', 'negeri', 'umum']);
                        $paiSubjects = [];
                        $otherSubjects = [];
                        
                        if ($category === 'Kelompok A' && $isMtsMode) {
                            foreach ($subjects as $s) {
                                if ($s['is_agama']) $paiSubjects[] = $s;
                                else $otherSubjects[] = $s;
                            }
                        } else {
                            $otherSubjects = $subjects;
                        }
                    @endphp

                    @if(count($paiSubjects) > 0)
                        {{-- Grouped PAI header --}}
                        <tr>
                            <td class="text-center">{{ $globalIndex++ }}</td>
                            <td class="font-bold">Pendidikan Agama Islam</td>
                            @foreach($semesterMap as $sem => $info) <td></td> @endforeach
                            <td></td>
                            <td></td>
                        </tr>
                        @php $subLetter = 'a'; @endphp
                        @foreach($paiSubjects as $s)
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding-left: 20px;">{{ $subLetter++ }}. {{ $s['subject'] }}</td>
                                @foreach($semesterMap as $sem => $info)
                                    <td class="text-center">{{ number_format($s['scores'][$sem], 2, ',', '.') }}</td>
                                    @php $totalAllScores[$sem] += $s['scores'][$sem]; @endphp
                                @endforeach
                                <td class="text-center font-bold">{{ number_format($s['total'], 0, ',', '.') }}</td>
                                <td class="text-center font-bold">{{ number_format($s['nr'], 2, ',', '.') }}</td>
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
                                <td class="text-center">{{ number_format($s['scores'][$sem], 2, ',', '.') }}</td>
                                @php $totalAllScores[$sem] += $s['scores'][$sem]; @endphp
                            @endforeach
                            <td class="text-center font-bold">{{ number_format($s['total'], 0, ',', '.') }}</td>
                            <td class="text-center font-bold">{{ number_format($s['nr'], 2, ',', '.') }}</td>
                        </tr>
                        @php 
                            $totalSum += $s['total'];
                            $totalAvg += $s['nr'];
                            $rowCount++;
                        @endphp
                    @endforeach
                @endforeach

                {{-- Totals --}}
                <tr class="font-bold">
                    <td colspan="2" class="text-center">Jumlah</td>
                    @foreach($semesterMap as $sem => $info)
                        <td class="text-center">{{ number_format($totalAllScores[$sem], 0, ',', '.') }}</td>
                    @endforeach
                    <td class="text-center">{{ number_format($totalSum, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($totalAvg, 2, ',', '.') }}</td>
                </tr>
                <tr class="font-bold">
                    <td colspan="2" class="text-center">Ratarata Total</td>
                    @foreach($semesterMap as $sem => $info)
                        <td class="text-center">{{ $rowCount > 0 ? number_format($totalAllScores[$sem] / $rowCount, 2, ',', '.') : '-' }}</td>
                    @endforeach
                    <td class="text-center">{{ $rowCount > 0 ? number_format($totalSum / $rowCount, 2, ',', '.') : '-' }}</td>
                    <td class="text-center">{{ $rowCount > 0 ? number_format($totalAvg / $rowCount, 2, ',', '.') : '-' }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center font-bold italic">Peringkat</td>
                    <td colspan="{{ 2 + count($semesterMap) }}" class="text-center font-bold italic">
                        Peringkat ke- {{ $rankData['rank'] ?? '-' }} dari {{ $rankData['total_students'] ?? '-' }} Siswa
                    </td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 15px;">Demikian Surat Keterangan Nilai Rapor ini dibuat untuk digunakan sebagai persyaratan Penerimaan Peserta Didik Baru (PPDB).</p>
    </div>

    <div class="signature-container">
        <div class="signature-box">
            <p>{{ $setting->city ?? 'Dawuhan' }}, {{ tanggal_indonesia(date('Y-m-d')) }}</p>
            <p>{{ $setting->default_signer_position ?? 'Kepala Madrasah' }},</p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $setting->default_signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
            NIP. {{ $setting->default_signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
@endsection
