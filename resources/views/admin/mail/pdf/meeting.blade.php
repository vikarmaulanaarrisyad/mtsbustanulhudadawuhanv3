@extends('admin.mail.pdf.layout', ['title' => 'Surat Undangan'])

@section('main-content')
    <div class="mail-info">
        @php $general = \App\Models\Setting::first(); @endphp
        <table>
            <tr>
                <td width="15%">Nomor</td>
                <td width="2%">:</td>
                <td>{{ $meeting->meeting_number }}</td>
                <td width="25%" align="right">{{ $general->city ?? 'Dawuhan' }},
                    {{ tanggal_indonesia($meeting->mail_date) }}</td>
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
                <td><strong>{{ $meeting->meeting_subject }}</strong></td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="mail-recipient">
        <p>Kepada Yth.<br>
            <strong>{{ $meeting->recipient_description }}</strong><br>
            di - Tempat
        </p>
    </div>

    <div class="content">
        <p>Assalamu'alaikum Wr. Wb.</p>
        <p>Dengan hormat, sehubungan dengan adanya agenda kegiatan sekolah, maka kami mengharap kehadiran Bapak/Ibu pada:
        </p>

        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr>
                <td width="25%">Hari, Tanggal</td>
                <td width="2%">:</td>
                <td><strong>{{ \Carbon\Carbon::parse($meeting->meeting_date)->translatedFormat('l, d F Y') }}</strong></td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('H:i') }} WIB s.d Selesai</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $meeting->meeting_place }}</td>
            </tr>
            <tr>
                <td>Acara / Agenda</td>
                <td>:</td>
                <td>{{ $meeting->meeting_agenda }}</td>
            </tr>
        </table>

        <p>Mengingat pentingnya acara tersebut, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktunya.</p>
        <p>Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.</p>
        <p>Wassalamu'alaikum Wr. Wb.</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>{{ $meeting->signer_position ?? 'Kepala Madrasah' }},</p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $meeting->signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
                NIP. {{ $meeting->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
