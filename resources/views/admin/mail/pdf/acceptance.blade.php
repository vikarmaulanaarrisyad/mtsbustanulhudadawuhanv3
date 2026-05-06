@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Bersedia Menerima'])

@section('main-content')
    <div class="mail-title">
        <h3>SURAT KETERANGAN BERSEDIA MENERIMA</h3>
        <p>Nomor: {{ $acceptance->acceptance_number }}</p>
    </div>

    <div class="content">
        @php
            $general = \App\Models\Setting::first();
        @endphp
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name }}, {{ $general->city ?? '...' }},
            Provinsi {{ $general->province ?? '...' }}, dengan ini menerangkan bahwa:</p>

        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr>
                <td width="30%">Nama Lengkap</td>
                <td width="2%">:</td>
                <td><strong>{{ $acceptance->student->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $acceptance->student->nis }} / {{ $acceptance->student->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td>:</td>
                <td>{{ $acceptance->student->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($acceptance->student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $acceptance->student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $acceptance->student->profile->alamat ?? '-' }}</td>
            </tr>
        </table>

        <p style="text-align: justify;">Adalah benar-benar telah kami terima untuk menjadi siswa pada Madrasah
            {{ $setting->school_name }} di<strong> {{ $acceptance->student->classGroup->class_group ?? '...' }}</strong>
            pada Tahun Pelajaran {{ $acceptance->student->academicYear->academic_year ?? '...' }}.</p>

        <p style="text-align: justify;">Siswa tersebut di atas adalah pindahan dari:</p>
        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr>
                <td width="30%">Nama Madrasah/Sekolah</td>
                <td width="2%">:</td>
                <td>{{ $acceptance->origin_school }}</td>
            </tr>
            <tr>
                <td>Kelas di Sekolah Asal</td>
                <td>:</td>
                <td>{{ $acceptance->origin_class ?? '-' }}</td>
            </tr>
        </table>

        <p>Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        @php
            $kepala = get_kepala_madrasah();
        @endphp
        <div class="signature-box">
            <p>{{ $general->city ?? 'Dawuhan' }},
                {{ tanggal_indonesia($acceptance->acceptance_date) }}<br>{{ $acceptance->signer_position ?? 'Kepala Madrasah' }},
            </p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $kepala->name ?? ($acceptance->signer_name ?? ($general->owner_name ?? 'KEPALA MADRASAH')) }}</u></strong><br>
                NIP. {{ $kepala->nip ?? ($acceptance->signer_nip ?? '-') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
