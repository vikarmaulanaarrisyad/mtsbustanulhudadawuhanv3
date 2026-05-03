@extends('admin.mail.pdf.layout', ['title' => 'Lembar Verifikasi Berkas PPDB'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">LEMBAR VERIFIKASI BERKAS PENDAFTARAN</h3>
        <p>Tahun Pelajaran {{ $admission->admission_year ?? '' }}</p>
    </div>

    <div class="content" style="margin-top: 20px;">
        <table style="width: 100%; margin-bottom: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 5px;">
            <tr>
                <td width="20%">No. Daftar</td>
                <td width="30%">: <strong>{{ $registrant->registration_number }}</strong></td>
                <td width="20%">Jalur</td>
                <td width="30%">: {{ $registrant->admissionType->admission_type_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>: <strong>{{ $registrant->nama_lengkap }}</strong></td>
                <td>Gelombang</td>
                <td>: {{ $registrant->admissionPhase->phase_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Asal Sekolah</td>
                <td colspan="3">: {{ $registrant->asal_sekolah ?? '-' }}</td>
            </tr>
        </table>

        <p>Petugas Verifikator, mohon periksa kelengkapan dokumen berikut:</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="background-color: #eee;">
                    <th style="border: 1px solid #000; padding: 8px; width: 5%;">No</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: left;">Nama Dokumen / Berkas Persyaratan</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 15%;">Wajib</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 10%;">Ada</th>
                    <th style="border: 1px solid #000; padding: 8px; width: 20%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $docs = [
                        ['name' => 'Fotokopi Akta Kelahiran', 'req' => 'Ya'],
                        ['name' => 'Fotokopi Kartu Keluarga (KK)', 'req' => 'Ya'],
                        ['name' => 'Fotokopi Ijazah / SKL (Legalisir)', 'req' => 'Ya'],
                        ['name' => 'Fotokopi SKHUN / Sertifikat Hasil Ujian', 'req' => 'Tidak'],
                        ['name' => 'Fotokopi Rapor Semester Terakhir', 'req' => 'Ya'],
                        ['name' => 'Pas Foto 3x4 (3 Lembar)', 'req' => 'Ya'],
                        ['name' => 'Fotokopi KIP / PKH / KKS (Jika Ada)', 'req' => 'Tidak'],
                    ];
                @endphp
                @foreach($docs as $index => $doc)
                <tr>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000; padding: 8px;">{{ $doc['name'] }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $doc['req'] }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">[ &nbsp; ]</td>
                    <td style="border: 1px solid #000; padding: 8px;"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px; border: 1px solid #000; padding: 10px; background-color: #fff9e6;">
            <strong>Catatan Petugas:</strong>
            <div style="height: 60px;"></div>
        </div>
    </div>

    <div class="signature" style="margin-top: 40px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: center; width: 40%;">
                    <p>Orang Tua / Wali Murid,</p>
                    <div style="height: 60px;"></div>
                    <p>( ........................................ )</p>
                </td>
                <td style="text-align: center; width: 20%;">
                    @php
                        $url = route('ppdb.check_verify', $registrant->registration_number);
                        $qrcode = base64_encode(QrCode::format('svg')->size(100)->margin(1)->generate($url));
                    @endphp
                    <img src="data:image/svg+xml;base64, {!! $qrcode !!}" style="width: 80px; height: 80px;">
                    <p style="font-size: 7pt; margin-top: 5px;">Scan untuk Verifikasi</p>
                </td>
                <td style="text-align: center; width: 40%;">
                    <p>{{ $source->city ?? 'Dawuhan' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Petugas Verifikator,</p>
                    <div style="height: 60px;"></div>
                    <p>( ........................................ )</p>
                </td>
            </tr>
        </table>
    </div>
@endsection
