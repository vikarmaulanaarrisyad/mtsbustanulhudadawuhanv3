@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Siswa Aktif'])

@section('main-content')
    <div class="mail-title">
        <h3>SURAT KETERANGAN AKTIF BELAJAR</h3>
        <p>Nomor: {{ $statement->letter_number }}</p>
    </div>

    <div class="content">
        @php $general = \App\Models\Setting::first(); @endphp
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name }}, {{ $general->city ?? '...' }},
            Provinsi {{ $general->province ?? '...' }}, dengan ini menerangkan bahwa:</p>

        @php $student = $statement->students->first(); @endphp
        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr>
                <td width="30%">Nama Lengkap</td>
                <td width="2%">:</td>
                <td><strong>{{ $student->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $student->nis }} / {{ $student->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tgl Lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }},
                    {{ tanggal_indonesia($student->tanggal_lahir) }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $student->kelas_lengkap }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua</td>
                <td>:</td>
                <td>{{ $student->parents->father_name ?? ($student->parents->mother_name ?? '-') }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $student->profile->alamat ?? '-' }}</td>
            </tr>
        </table>

        <p>Adalah benar-benar siswa Madrasah {{ $setting->school_name }} yang aktif belajar pada Tahun Pelajaran
            {{ $student->academicYear->academic_year ?? '' }}.</p>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya, yaitu
            untuk: <strong>{{ $statement->purpose }}</strong>.</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>{{ $general->city ?? 'Dawuhan' }},
                {{ tanggal_indonesia($statement->letter_date) }}<br>{{ $statement->signer_position ?? 'Kepala Madrasah' }},
            </p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $statement->signer_name ?? ($general->owner_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                NIP. {{ $statement->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
