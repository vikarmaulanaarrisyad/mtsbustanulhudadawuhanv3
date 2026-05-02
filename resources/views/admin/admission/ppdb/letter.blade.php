@extends('admin.mail.pdf.layout', ['title' => 'Surat Hasil Seleksi PPDB'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">SURAT KETERANGAN HASIL SELEKSI PPDB</h3>
        <p>Nomor: {{ $registrant->letter_number ?? '... / PPDB / ' . ($source->school_code ?? 'MTs-BH') . ' / ' . date('Y') }}</p>
    </div>

    <div class="content" style="margin-top: 30px;">
        <p>Berdasarkan hasil verifikasi berkas dan seleksi Penerimaan Peserta Didik Baru (PPDB) {{ $source->school_name ?? 'Madrasah' }} Tahun Pelajaran {{ $admission->admission_year ?? '' }}, dengan ini menerangkan bahwa:</p>

        <table style="width: 100%; margin-left: 50px; margin-top: 20px; margin-bottom: 20px;">
            <tr>
                <td width="30%">No. Pendaftaran</td>
                <td width="3%">:</td>
                <td><strong>{{ $registrant->registration_number }}</strong></td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td><strong>{{ $registrant->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>{{ $registrant->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $registrant->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td>:</td>
                <td>{{ $registrant->asal_sekolah ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jalur Pendaftaran</td>
                <td>:</td>
                <td>{{ $registrant->admissionType->admission_type_name ?? '-' }}</td>
            </tr>
        </table>

        <p style="text-align: justify; line-height: 1.6;">
            Setelah melalui proses seleksi administrasi dan persyaratan lainnya, nama yang tersebut di atas dinyatakan:
        </p>

        @if ($registrant->status == 'diterima')
            <div style="text-align: center; margin: 30px 0; border: 2px solid #000; padding: 15px; width: 60%; margin-left: 20%; background-color: #f8f9fa;">
                <h2 style="margin: 0; text-transform: uppercase; color: #155724;">LULUS / DITERIMA</h2>
            </div>
            <p style="text-align: justify; line-height: 1.6;">
                Selamat bagi calon siswa yang dinyatakan diterima. Silakan melakukan proses daftar ulang dengan melengkapi persyaratan yang telah ditentukan oleh panitia sesuai dengan jadwal yang berlaku.
            </p>
        @elseif ($registrant->status == 'ditolak')
            <div style="text-align: center; margin: 30px 0; border: 2px solid #000; padding: 15px; width: 60%; margin-left: 20%; background-color: #f8f9fa;">
                <h2 style="margin: 0; text-transform: uppercase; color: #721c24;">TIDAK LULUS / DITOLAK</h2>
            </div>
            <p style="text-align: justify; line-height: 1.6;">
                Mohon maaf, berdasarkan hasil seleksi Anda dinyatakan belum dapat diterima sebagai siswa di {{ $source->school_name ?? 'Madrasah kami' }}. Tetap semangat dan terima kasih atas partisipasi Anda.
            </p>
        @else
            <div style="text-align: center; margin: 30px 0; border: 2px solid #000; padding: 15px; width: 60%; margin-left: 20%;">
                <h2 style="margin: 0; text-transform: uppercase;">DALAM PROSES</h2>
            </div>
            <p style="text-align: justify; line-height: 1.6;">
                Status pendaftaran Anda saat ini masih dalam tahap peninjauan oleh panitia. Silakan cek secara berkala untuk informasi selanjutnya.
            </p>
        @endif

        <p style="text-align: justify; line-height: 1.6; margin-top: 20px;">
            Demikian surat keterangan hasil seleksi ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature" style="margin-top: 30px;">
        <div class="signature-box" style="float: right; width: 250px; text-align: center;">
            <p>{{ $source->city ?? 'Dawuhan' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
               {{ $source->default_signer_position ?? 'Kepala Madrasah' }},
            </p>
            <div class="signature-space" style="height: 80px;"></div>
            <p><strong><u>{{ $source->default_signer_name ?? ($source->owner_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                NIP. {{ $source->default_signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
