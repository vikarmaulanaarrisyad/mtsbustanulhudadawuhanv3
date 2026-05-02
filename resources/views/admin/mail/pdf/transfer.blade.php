@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Pindah (Mutasi)'])

@section('main-content')
    <div class="mail-title">
        <h3>SURAT KETERANGAN PINDAH (MUTASI)</h3>
        <p>Nomor: {{ $transfer->transfer_number }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name }}, menerangkan bahwa:</p>
        
        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr><td width="30%">Nama Lengkap</td><td width="2%">:</td><td><strong>{{ $transfer->student->nama_lengkap }}</strong></td></tr>
            <tr><td>NISN / NIK</td><td>:</td><td>{{ $transfer->student->nisn ?? '-' }} / {{ $transfer->student->nik ?? '-' }}</td></tr>
            <tr><td>Tempat, Tgl Lahir</td><td>:</td><td>{{ $transfer->student->tempat_lahir }}, {{ \Carbon\Carbon::parse($transfer->student->tanggal_lahir)->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $transfer->student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
            <tr><td>Kelas Terakhir</td><td>:</td><td>{{ $transfer->student->kelas_lengkap }}</td></tr>
        </table>

        <p>Sesuai surat permohonan pindah sekolah oleh orang tua/wali murid:</p>
        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr><td width="30%">Nama Orang Tua</td><td width="2%">:</td><td>{{ $transfer->student->parents->father_name ?? $transfer->student->parents->mother_name ?? '-' }}</td></tr>
            <tr><td>Alamat</td><td>:</td><td>{{ $transfer->student->alamat ?? '-' }}</td></tr>
        </table>

        <p>Telah mengajukan pindah dari {{ $setting->school_name }} ke:</p>
        <table style="width: 100%; margin-left: 30px; margin-bottom: 20px;">
            <tr><td width="30%">Sekolah Tujuan</td><td width="2%">:</td><td><strong>{{ $transfer->destination_school }}</strong></td></tr>
            <tr><td>Alasan Pindah</td><td>:</td><td>{{ $transfer->reason ?? '-' }}</td></tr>
        </table>

        <p>Bersama ini kami lampirkan dokumen pendukung (Buku Rapor) yang bersangkutan. Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>Dawuhan, {{ \Carbon\Carbon::parse($transfer->transfer_date)->translatedFormat('d F Y') }}<br>Kepala Madrasah,</p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $transfer->signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
            NIP. {{ $transfer->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
