@extends('admin.mail.pdf.layout', ['title' => 'Bukti Pendaftaran PPDB'])

@section('main-content')
    <div class="mail-title">
        <h3 style="text-decoration: underline; margin-bottom: 5px;">KARTU BUKTI PENDAFTARAN ONLINE</h3>
        <p>Tahun Pelajaran {{ $admission->admission_year ?? '' }}</p>
    </div>

    <div class="content" style="margin-top: 20px;">
        <div style="border: 1px solid #000; padding: 20px; position: relative; min-height: 250px;">
            <div style="float: left; width: 70%;">
                <table style="width: 100%;">
                    <tr>
                        <td width="35%">No. Pendaftaran</td>
                        <td width="3%">:</td>
                        <td><strong style="font-size: 14pt; color: #28a745;">{{ $registrant->registration_number }}</strong></td>
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
                        <td>JK / TTL</td>
                        <td>:</td>
                        <td>{{ $registrant->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $registrant->tempat_lahir }}, {{ \Carbon\Carbon::parse($registrant->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Asal Sekolah</td>
                        <td>:</td>
                        <td>{{ $registrant->asal_sekolah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Gelombang</td>
                        <td>:</td>
                        <td>{{ $registrant->admissionPhase->phase_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jalur</td>
                        <td>:</td>
                        <td>{{ $registrant->admissionType->admission_type_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Daftar</td>
                        <td>:</td>
                        <td>{{ $registrant->created_at->translatedFormat('d F Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>

            <div style="float: right; width: 110px; height: 140px; border: 1px solid #000; text-align: center; line-height: 140px; background-color: #f8f9fa;">
                @if($registrant->foto)
                    <img src="{{ public_path('storage/' . $registrant->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 8pt; color: #999;">FOTO 3X4</span>
                @endif
            </div>

            <div style="clear: both;"></div>

            <div style="margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 10px; font-size: 9pt; color: #333;">
                <p style="margin-bottom: 5px;"><strong>Informasi Penting:</strong></p>
                <ul style="margin-top: 0; padding-left: 20px;">
                    <li>Simpan bukti pendaftaran ini sebagai syarat verifikasi berkas fisik di sekolah.</li>
                    <li>Silakan lengkapi berkas persyaratan dan bawa saat jadwal verifikasi tiba.</li>
                    <li>Pantau terus status pendaftaran Anda melalui Dashboard PPDB Online secara berkala.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="signature" style="margin-top: 30px;">
        <div style="float: left; width: 200px; text-align: center; font-size: 9pt;">
            <div style="padding: 10px;">
                @php
                    $url = route('ppdb.check_verify', $registrant->registration_number);
                    $qrcode = base64_encode(QrCode::format('png')->size(100)->margin(1)->generate($url));
                @endphp
                <img src="data:image/png;base64, {!! $qrcode !!}" style="width: 100px; height: 100px;">
            </div>
            <p style="margin-top: 5px;">Verifikasi Keaslian Data</p>
        </div>
        
        <div class="signature-box" style="float: right; width: 250px; text-align: center;">
            <p>{{ $source->city ?? 'Dawuhan' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
               Pendaftar,
            </p>
            <div class="signature-space" style="height: 60px;"></div>
            <p><strong>{{ $registrant->nama_lengkap }}</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
