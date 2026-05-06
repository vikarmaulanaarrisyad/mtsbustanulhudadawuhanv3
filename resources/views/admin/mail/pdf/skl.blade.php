@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Lulus'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">SURAT KETERANGAN LULUS (SKL)</h3>
        <p>Nomor: {{ $student->skl_number ?? '... / SKL / ' . ($setting->school_code ?? 'MTs-BH') . ' / ' . date('Y') }}</p>
    </div>

    <div class="content" style="margin-top: 30px;">
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name ?? 'MTs. Bustanul Huda' }},
            menerangkan bahwa:</p>

        <table style="width: 100%; margin-left: 50px; margin-top: 20px; margin-bottom: 20px;">
            <tr>
                <td width="30%">Nama Lengkap</td>
                <td width="3%">:</td>
                <td><strong>{{ $student->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $student->nis }} / {{ $student->nisn }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua / Wali</td>
                <td>:</td>
                <td>{{ $student->parents->father_name ?? ($student->parents->mother_name ?? '-') }}</td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td>:</td>
                <td>{{ $student->asal_sekolah ?? '-' }}</td>
            </tr>
        </table>

        <p style="text-align: justify; line-height: 1.6;">
            Berdasarkan kriteria kelulusan peserta didik yang ditetapkan oleh satuan pendidikan dan hasil rapat pleno dewan
            guru pada tanggal {{ \Carbon\Carbon::parse($student->tanggal_keluar)->translatedFormat('d F Y') }}, nama yang
            tersebut di atas dinyatakan:
        </p>

        <div
            style="text-align: center; margin: 30px 0; border: 2px solid #000; padding: 10px; width: 50%; margin-left: 25%;">
            <h2 style="margin: 0;">LULUS</h2>
        </div>

        <p style="text-align: justify; line-height: 1.6;">
            Surat Keterangan ini berlaku sementara sampai dengan diterbitkannya Ijazah asli sebagai bukti kelulusan.
            Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature" style="margin-top: 40px;">
        @php
            $general = \App\Models\MailSetting::first();
            $kepala = get_kepala_madrasah();
        @endphp
        <div class="signature-box">
            <p>{{ $general->city ?? 'Dawuhan' }},
                {{ tanggal_indonesia($student->tanggal_keluar) }}<br>{{ $general->default_signer_position ?? 'Kepala Madrasah' }},
            </p>
            <div class="signature-space" style="height: 80px;"></div>
            <p><strong><u>{{ $kepala->name ?? ($general->default_signer_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                NIP. {{ $kepala->nip ?? ($general->default_signer_nip ?? '-') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
