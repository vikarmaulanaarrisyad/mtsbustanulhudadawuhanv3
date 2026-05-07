@extends('admin.mail.pdf.layout', ['title' => 'Daftar Nilai SKL'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">DAFTAR NILAI KELULUSAN</h3>
        <p>Nomor: ... / DNL / {{ $setting->school_code ?? 'MTs-BH' }} / {{ date('Y') }}</p>
    </div>

    <div class="content" style="margin-top: 20px;">
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
        </table>

        <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11pt;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th width="5%">NO</th>
                    <th>MATA PELAJARAN</th>
                    <th width="15%">RATA-RATA RAPORT (NR)</th>
                    <th width="15%">UJIAN MADRASAH (UM)</th>
                    <th width="15%">NILAI IJAZAH (NS)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalNS = 0; 
                    $totalUM = 0;
                @endphp
                @foreach($dataGrades as $index => $grade)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding-left: 5px;">{{ $grade['subject'] }}</td>
                        <td style="text-align: center;">{{ number_format($grade['nr'], 2) }}</td>
                        <td style="text-align: center;">{{ number_format($grade['um'], 2) }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ number_format($grade['ns'], 2) }}</td>
                    </tr>
                    @php 
                        $totalNS += $grade['ns']; 
                        $totalUM += $grade['um'];
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="3" style="text-align: right; padding-right: 10px;">JUMLAH NILAI</td>
                    <td style="text-align: center;">{{ number_format($totalUM, 2) }}</td>
                    <td style="text-align: center;">{{ number_format($totalNS, 2) }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="3" style="text-align: right; padding-right: 10px;">RATA-RATA</td>
                    <td style="text-align: center;">{{ count($dataGrades) > 0 ? number_format($totalUM / count($dataGrades), 2) : 0 }}</td>
                    <td style="text-align: center;">{{ count($dataGrades) > 0 ? number_format($totalNS / count($dataGrades), 2) : 0 }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="signature-container" style="margin-top: 30px; position: relative;">
        {{-- QR VERIFICATION BOX --}}
        <div style="float: left; width: 40%; text-align: left; padding-top: 10px;">
            <div style="display: inline-block; padding: 5px; border: 1px solid #ccc; background: #fff;">
                {!! $qrCode !!}
            </div>
            <p style="font-size: 8px; color: #666; margin-top: 5px; font-style: italic;">
                Dokumen ini sah & terverifikasi secara digital.<br>
                Scan QR Code untuk cek keaslian.<br>
                Kode: <strong>{{ $verification->verification_code }}</strong>
            </p>
        </div>

        @php
            $kepala = get_kepala_madrasah();
            $general = \App\Models\MailSetting::first();
        @endphp
        <div class="signature-box" style="float: right; width: 50%; text-align: center;">
            <p>{{ $general->city ?? 'Dawuhan' }}, {{ tanggal_indonesia(date('Y-m-d')) }}<br>{{ $general->default_signer_position ?? 'Kepala Madrasah' }},</p>
            <div class="signature-space" style="height: 60px;"></div>
            <p><strong><u>{{ $kepala->name ?? ($general->default_signer_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
            NIP. {{ $kepala->nip ?? ($general->default_signer_nip ?? '-') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
