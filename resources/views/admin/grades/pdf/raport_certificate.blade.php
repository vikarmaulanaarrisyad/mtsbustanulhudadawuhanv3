@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Nilai Raport'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">SURAT KETERANGAN NILAI RAPORT</h3>
        <p>Nomor: ... / SKNR / {{ $setting->school_code ?? 'MTs-BH' }} / {{ date('Y') }}</p>
    </div>

    <div class="content" style="margin-top: 20px;">
        <p>Kepala Madrasah {{ $setting->school_name ?? 'MTs. Bustanul Huda' }} menerangkan bahwa:</p>

        <table style="width: 100%; margin-left: 20px; margin-bottom: 20px;">
            <tr>
                <td width="25%">Nama Lengkap</td>
                <td width="3%">:</td>
                <td><strong>{{ $student->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }}, {{ \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $student->nis }} / {{ $student->nisn }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>:</td>
                <td>{{ $student->parents->father_name ?? '-' }}</td>
            </tr>
        </table>

        <p>Adalah benar siswa pada madrasah kami dengan perolehan nilai raport sebagai berikut:</p>

        <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th rowspan="2" width="5%">NO</th>
                    <th rowspan="2">MATA PELAJARAN</th>
                    @foreach($classLevels as $cl)
                        <th colspan="2">KELAS {{ $cl }}</th>
                    @endforeach
                    <th rowspan="2" width="10%">NR</th>
                </tr>
                <tr style="background-color: #f2f2f2;">
                    <th>S1</th><th>S2</th>
                    <th>S1</th><th>S2</th>
                    <th>S1</th><th>S2</th>
                </tr>
            </thead>
            <tbody>
                @php $totalNR = 0; @endphp
                @foreach($dataGrades as $index => $grade)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding-left: 5px;">{{ $grade['subject'] }}</td>
                        @foreach($classLevels as $cl)
                            <td style="text-align: center;">{{ $grade['scores']["c{$cl}s1"] }}</td>
                            <td style="text-align: center;">{{ $grade['scores']["c{$cl}s2"] }}</td>
                        @endforeach
                        <td style="text-align: center; font-weight: bold;">{{ number_format($grade['nr'], 2) }}</td>
                    </tr>
                    @php $totalNR += $grade['nr']; @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="{{ (count($classLevels) * 2) + 2 }}" style="text-align: right; padding-right: 10px;">JUMLAH NILAI RAPORT (NR)</td>
                    <td style="text-align: center;">{{ number_format($totalNR, 2) }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="{{ (count($classLevels) * 2) + 2 }}" style="text-align: right; padding-right: 10px;">RATA-RATA NILAI RAPORT</td>
                    <td style="text-align: center;">{{ count($dataGrades) > 0 ? number_format($totalNR / count($dataGrades), 2) : 0 }}</td>
                </tr>
            </tfoot>
        </table>

        <p style="margin-top: 20px;">Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature" style="margin-top: 30px;">
        @php
            $kepala = get_kepala_madrasah();
            $general = \App\Models\MailSetting::first();
        @endphp
        <div class="signature-box">
            <p>{{ $general->city ?? 'Dawuhan' }}, {{ tanggal_indonesia(date('Y-m-d')) }}<br>{{ $general->default_signer_position ?? 'Kepala Madrasah' }},</p>
            <div class="signature-space" style="height: 60px;"></div>
            <p><strong><u>{{ $kepala->name ?? ($general->default_signer_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
            NIP. {{ $kepala->nip ?? ($general->default_signer_nip ?? '-') }}</p>
        </div>
    </div>
@endsection
