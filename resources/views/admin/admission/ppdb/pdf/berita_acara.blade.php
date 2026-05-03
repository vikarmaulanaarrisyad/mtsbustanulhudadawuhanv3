@extends('admin.mail.pdf.layout', ['title' => 'Berita Acara Kelulusan PPDB'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">BERITA ACARA RAPAT PENETAPAN KELULUSAN PPDB</h3>
        <p>Nomor: {{ $admission->ba_letter_number ?? ('... / BA-PPDB / ' . ($source->school_code ?? 'MTs-BH') . ' / ' . date('Y')) }}</p>
    </div>

    <div class="content" style="margin-top: 30px; line-height: 1.8;">
        <p>Pada hari ini, <strong>{{ \Carbon\Carbon::now()->translatedFormat('l') }}</strong> tanggal <strong>{{ \Carbon\Carbon::now()->translatedFormat('d') }}</strong> bulan <strong>{{ \Carbon\Carbon::now()->translatedFormat('F') }}</strong> tahun <strong>{{ \Carbon\Carbon::now()->translatedFormat('Y') }}</strong>, bertempat di {{ $source->school_name ?? 'Madrasah' }}, telah dilaksanakan Rapat Pleno Panitia Penerimaan Peserta Didik Baru (PPDB) Tahun Pelajaran {{ $admission->admission_year ?? '' }}.</p>

        <p>Rapat tersebut dihadiri oleh Kepala Madrasah, Komite Madrasah, dan segenap Panitia PPDB, dengan agenda utama Penetapan Kelulusan Calon Peserta Didik Baru.</p>

        <p>Berdasarkan hasil verifikasi dokumen, tes seleksi, dan kriteria penerimaan yang telah ditetapkan, maka Panitia PPDB memutuskan dan menetapkan bahwa dari sejumlah <strong>{{ $total_applicants }}</strong> pendaftar:</p>

        <table style="width: 80%; margin-left: 50px; margin-top: 10px; margin-bottom: 10px;">
            <tr>
                <td width="50%">1. Dinyatakan <strong>DITERIMA</strong></td>
                <td width="5%">:</td>
                <td><strong>{{ $total_accepted }}</strong> siswa</td>
            </tr>
            <tr>
                <td>2. Dinyatakan <strong>DITOLAK</strong></td>
                <td>:</td>
                <td><strong>{{ $total_rejected }}</strong> siswa</td>
            </tr>
            <tr>
                <td>3. Cadangan / Proses</td>
                <td>:</td>
                <td><strong>{{ $total_pending }}</strong> siswa</td>
            </tr>
        </table>

        <p>Daftar nama-nama calon peserta didik yang dinyatakan diterima sebagaimana tercantum dalam Lampiran Berita Acara ini yang merupakan satu kesatuan yang tidak terpisahkan.</p>

        <p>Demikian Berita Acara ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature" style="margin-top: 50px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; width: 45%;">
                    <p>Ketua Panitia PPDB,</p>
                    <div style="height: 80px;"></div>
                    <p><strong>( ........................................ )</strong></p>
                </td>
                <td style="width: 10%;"></td>
                <td style="text-align: center; width: 45%;">
                    <p>Kepala Madrasah,</p>
                    <div style="height: 80px;"></div>
                    <p><strong><u>{{ $source->default_signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
                    NIP. {{ $source->default_signer_nip ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>
@endsection
