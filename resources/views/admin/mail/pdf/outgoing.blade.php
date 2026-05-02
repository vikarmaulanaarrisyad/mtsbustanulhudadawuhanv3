@extends('admin.mail.pdf.layout', ['title' => 'Surat Keluar - ' . $mail->mail_number])

@section('main-content')
    <div class="mail-info">
        @php $general = \App\Models\Setting::first(); @endphp
        <table>
            <tr>
                <td width="15%">Nomor</td>
                <td width="2%">:</td>
                <td>{{ $mail->mail_number }}</td>
                <td width="25%" align="right">{{ $general->city ?? 'Dawuhan' }}, {{ tanggal_indonesia($mail->mail_date) }}
                </td>
            </tr>
            <tr>
                <td>Sifat</td>
                <td>:</td>
                <td>Penting</td>
                <td></td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ $mail->mail_subject }}</strong></td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="mail-recipient">
        <p>Kepada Yth.<br>
            <strong>{{ $mail->mail_recipient }}</strong><br>
            di - Tempat
        </p>
    </div>

    <div class="content">
        {!! $mail->mail_content !!}
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>{{ $mail->signer_position ?? 'Kepala Madrasah' }},</p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $mail->signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
                NIP. {{ $mail->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
