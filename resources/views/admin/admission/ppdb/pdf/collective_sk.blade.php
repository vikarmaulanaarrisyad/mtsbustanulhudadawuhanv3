@extends('admin.mail.pdf.layout', ['title' => 'Pengumuman Kelulusan Kolektif PPDB'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">PENGUMUMAN HASIL SELEKSI PPDB (KOLEKTIF)</h3>
        <p>Tahun Pelajaran {{ $admission->admission_year ?? '' }}</p>
    </div>

    <div class="content" style="margin-top: 20px;">
        <p>Berdasarkan Keputusan Kepala {{ $source->school_name ?? 'Madrasah' }} Nomor {{ $admission->sk_letter_number ?? ('... / PPDB / ' . ($source->school_code ?? 'MTs-BH') . ' / ' . date('Y')) }}, berikut adalah daftar pendaftar yang dinyatakan <strong>LULUS / DITERIMA</strong> sebagai Peserta Didik Baru:</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #000; padding: 8px; width: 5%;">No</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 20%;">No. Daftar</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: left;">Nama Lengkap</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 15%;">NISN</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 25%;">Asal Sekolah</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 10%;">JK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrants as $index => $r)
                <tr>
                    <td style="border: 1px solid #000; padding: 6px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000; padding: 6px; text-align: center;">{{ $r->registration_number }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">{{ $r->nama_lengkap }}</td>
                    <td style="border: 1px solid #000; padding: 6px; text-align: center;">{{ $r->nisn ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">{{ $r->asal_sekolah ?? '-' }}</td>
                    <td style="border: 1px solid #000; padding: 6px; text-align: center;">{{ $r->jenis_kelamin }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="border: 1px solid #000; padding: 20px; text-align: center; color: #999;">Belum ada data siswa yang diterima.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <p><strong>Catatan Penting:</strong></p>
            <ol style="font-size: 10pt; line-height: 1.5;">
                <li>Bagi siswa yang dinyatakan LULUS, wajib melakukan pendaftaran ulang pada tanggal ............................</li>
                <li>Membawa dokumen persyaratan yang ditentukan (bisa dilihat di Dashboard PPDB masing-masing).</li>
                <li>Apabila sampai batas waktu yang ditentukan tidak melakukan daftar ulang, maka dianggap mengundurkan diri.</li>
            </ol>
        </div>
    </div>

    <div class="signature" style="margin-top: 30px;">
        <div class="signature-box" style="float: right; width: 250px; text-align: center;">
            <p>{{ $source->city ?? 'Dawuhan' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
               Kepala Madrasah,
            </p>
            <div class="signature-space" style="height: 60px;"></div>
            <p><strong><u>{{ $source->default_signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
                NIP. {{ $source->default_signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
