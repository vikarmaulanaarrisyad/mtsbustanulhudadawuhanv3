@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Pindah'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">SURAT KETERANGAN PINDAH SEKOLAH/MADRASAH</h3>
        <p>Nomor: {{ $student->surat_pindah_number ?? '... / SKP / ' . ($setting->school_code ?? 'MTs-BH') . ' / ' . date('Y') }}</p>
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
                <td>NIS / NISN</td>
                <td>:</td>
                <td>{{ $student->nis }} / {{ $student->nisn }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>{{ $student->tempat_lahir }}, {{ tanggal_indonesia($student->tanggal_lahir) }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Kelas / Tingkat</td>
                <td>:</td>
                <td>{{ $student->kelas_lengkap }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua / Wali</td>
                <td>:</td>
                <td>{{ $student->parents->father_name ?? ($student->parents->mother_name ?? '-') }}</td>
            </tr>
        </table>

        <p style="text-align: justify; line-height: 1.6;">
            Sesuai dengan surat permohonan pindah sekolah/madrasah oleh orang tua/wali murid tertanggal {{ tanggal_indonesia($student->tanggal_keluar) }}, 
            maka terhitung mulai tanggal tersebut, siswa yang bersangkutan telah resmi **MUTASI KELUAR** dari {{ $setting->school_name ?? 'MTs. Bustanul Huda' }} 
            atas permintaan sendiri dengan alasan:
        </p>

        <div style="margin: 15px 50px; font-weight: bold; font-style: italic;">
            "{{ $student->alasan_pindah ?? '-' }}"
        </div>

        <p style="text-align: justify; line-height: 1.6;">
            Siswa tersebut akan pindah/melanjutkan ke:
        </p>
        
        <table style="width: 100%; margin-left: 50px; margin-bottom: 20px;">
            <tr>
                <td width="30%">Nama Sekolah Tujuan</td>
                <td width="3%">:</td>
                <td><strong>{{ $student->pindah_ke ?? '-' }}</strong></td>
            </tr>
        </table>

        <p style="text-align: justify; line-height: 1.6;">
            Demikian surat keterangan pindah ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya. 
            Bilamana di kemudian hari terdapat kekeliruan dalam surat ini, akan diperbaiki sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-container" style="margin-top: 40px; position: relative;">
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
            $general = \App\Models\MailSetting::first();
            $kepala = get_kepala_madrasah();
        @endphp
        <div class="signature-box" style="float: right; width: 50%; text-align: center;">
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
