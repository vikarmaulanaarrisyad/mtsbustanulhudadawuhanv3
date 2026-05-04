@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Siswa Aktif'])

@section('main-content')
    <div class="mail-title">
        <h3>SURAT KETERANGAN AKTIF BELAJAR</h3>
        <p>Nomor: {{ $cert->certificate_number }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name }}, dengan ini menerangkan bahwa:</p>

        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr>
                <td width="30%">Nama Lengkap</td>
                <td width="2%">:</td>
                <td><strong>{{ $cert->student->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NISN / NIK</td>
                <td>:</td>
                <td>{{ $cert->student->nisn ?? '-' }} / {{ $cert->student->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td>:</td>
                <td>{{ $cert->student->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($cert->student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $cert->student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $cert->student->kelas_lengkap }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>:</td>
                <td>{{ $cert->student->parents->father_name ?? ($cert->student->parents->mother_name ?? '-') }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $cert->student->alamat ?? '-' }}</td>
            </tr>
        </table>

        <p>Adalah benar-benar siswa Madrasah {{ $setting->school_name }} yang aktif belajar pada Tahun Pelajaran
            {{ $cert->student->academicYear->academic_year ?? '' }}.</p>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya, yaitu
            untuk: <strong>{{ $cert->purpose }}</strong>.</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>Dawuhan, {{ \Carbon\Carbon::parse($cert->certificate_date)->translatedFormat('d F Y') }}<br>Kepala Madrasah,
            </p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $cert->signer_name ?? '...........................' }}</u></strong><br>
                NIP. {{ $cert->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
