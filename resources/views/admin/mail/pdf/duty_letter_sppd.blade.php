@extends('admin.mail.pdf.layout', ['title' => 'SPPD'])

@section('main-content')
    @foreach($letter->teachers as $index => $teacher)
    <div style="page-break-after: always;">
        <div class="mail-info">
            <table style="width: 100%;">
                <tr>
                    <td width="60%"></td>
                    <td width="40%">
                        <table style="width: 100%;">
                            <tr><td width="40%">Lembar Ke</td><td>:</td><td></td></tr>
                            <tr><td>Kode No</td><td>:</td><td></td></tr>
                            <tr><td>Nomor</td><td>:</td><td>{{ $letter->letter_number }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="mail-title" style="margin-top: 10px;">
            <h3 style="text-decoration: underline; margin-bottom: 5px;">SURAT PERJALANAN DINAS (SPD)</h3>
        </div>

        <table class="table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <tr><td width="5%" align="center">1</td><td width="45%">Pejabat Berwenang yang memberi perintah</td><td width="50%">{{ $letter->signer_position ?? 'Kepala Madrasah' }}</td></tr>
            <tr><td align="center">2</td><td>Nama / NIP Pegawai yang diperintah</td><td><strong>{{ $teacher->name }}</strong><br>NIP. {{ $teacher->nip ?? '-' }}</td></tr>
            <tr><td align="center">3</td><td>a. Pangkat dan Golongan<br>b. Jabatan<br>c. Tingkat Biaya Perjalanan Dinas</td><td>a. {{ $teacher->rank ?? '-' }}<br>b. {{ $teacher->position ?? '-' }}<br>c. </td></tr>
            <tr><td align="center">4</td><td>Maksud Perjalanan Dinas</td><td>{{ $letter->purpose }}</td></tr>
            <tr><td align="center">5</td><td>Alat Angkutan yang dipergunakan</td><td>{{ $letter->transportation ?? '-' }}</td></tr>
            <tr><td align="center">6</td><td>a. Tempat Berangkat<br>b. Tempat Tujuan</td><td>a. {{ $setting->school_name }}<br>b. {{ $letter->destination }}</td></tr>
            <tr><td align="center">7</td><td>a. Lamanya Perjalanan Dinas<br>b. Tanggal Berangkat<br>c. Tanggal harus kembali/tiba di tempat baru</td><td>
                a. {{ $letter->return_date ? \Carbon\Carbon::parse($letter->departure_date)->diffInDays(\Carbon\Carbon::parse($letter->return_date)) + 1 : '1' }} Hari<br>
                b. {{ \Carbon\Carbon::parse($letter->departure_date)->translatedFormat('d F Y') }}<br>
                c. {{ $letter->return_date ? \Carbon\Carbon::parse($letter->return_date)->translatedFormat('d F Y') : \Carbon\Carbon::parse($letter->departure_date)->translatedFormat('d F Y') }}
            </td></tr>
            <tr><td align="center">8</td><td>Pengikut: Nama</td><td>NIP / Jabatan</td></tr>
            <tr><td align="center">9</td><td>Pembebanan Anggaran<br>a. Instansi<br>b. Mata Anggaran</td><td><br>a. {{ $setting->school_name }}<br>b. {{ $letter->budget_source ?? '-' }}</td></tr>
            <tr><td align="center">10</td><td>Keterangan Lain-lain</td><td></td></tr>
        </table>

        <div class="signature" style="margin-top: 20px;">
            @php $general = \App\Models\Setting::first(); @endphp
            <div class="signature-box">
                <p>Dikeluarkan di : {{ $general->city ?? 'Dawuhan' }}<br>Tanggal : {{ \Carbon\Carbon::parse($letter->letter_date)->translatedFormat('d F Y') }}</p>
                <p>{{ $letter->signer_position ?? 'Kepala Madrasah' }},</p>
                <div class="signature-space" style="height: 60px;"></div>
                <p><strong><u>{{ $letter->signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
                NIP. {{ $letter->signer_nip ?? '-' }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    @endforeach
@endsection
