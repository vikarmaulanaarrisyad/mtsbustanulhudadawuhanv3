@extends('admin.mail.pdf.layout', ['title' => 'Bukti Daftar Ulang PPDB'])

@section('main-content')
    <div class="mail-title">
        <div style="background: #10b981; color: white; padding: 10px; border-radius: 8px; margin-bottom: 15px;">
            <h3 style="margin: 0; text-transform: uppercase;">KARTU BUKTI DAFTAR ULANG</h3>
        </div>
        <p>Tahun Pelajaran {{ $admission->admission_year ?? '' }}</p>
    </div>

    <div class="content" style="margin-top: 15px;">
        <div style="border: 2px solid #10b981; padding: 20px; position: relative; background: #fff;">
            {{-- Watermark LUNAS --}}
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 80pt; color: rgba(16, 185, 129, 0.15); font-weight: bold; z-index: 0; pointer-events: none;">
                LUNAS
            </div>

            <div style="position: relative; z-index: 1;">
                <div style="float: left; width: 70%;">
                    <table style="width: 100%;">
                        <tr>
                            <td width="35%">No. Registrasi</td>
                            <td width="3%">:</td>
                            <td><strong style="font-size: 14pt; color: #10b981;">{{ $registrant->registration_number }}</strong></td>
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
                            <td>Status Akhir</td>
                            <td>:</td>
                            <td><span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 9pt;">DITERIMA & SUDAH DAFTAR ULANG</span></td>
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
                            <td>Waktu Konfirmasi</td>
                            <td>:</td>
                            <td>{{ $registrant->confirmed_at ? \Carbon\Carbon::parse($registrant->confirmed_at)->translatedFormat('d F Y, H:i') : '-' }} WIB</td>
                        </tr>
                    </table>
                </div>

                <div style="float: right; width: 110px; height: 140px; border: 2px solid #10b981; text-align: center; background-color: #f8f9fa; overflow: hidden;">
                    @if($registrant->foto)
                        <img src="{{ public_path('storage/' . $registrant->foto) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="padding-top: 50px; font-size: 8pt; color: #999;">FOTO 3X4</div>
                    @endif
                </div>

                <div style="clear: both;"></div>

                <div style="margin-top: 20px; border-top: 1px solid #10b981; padding-top: 15px; font-size: 9pt;">
                    <p style="margin-bottom: 8px; color: #059669; font-weight: bold;">Pesan Untuk Calon Siswa:</p>
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px; border-radius: 5px; color: #166534;">
                        Selamat bergabung! Anda telah resmi terdaftar sebagai calon siswa baru. Simpan kartu bukti daftar ulang ini sebagai bukti sah administrasi. Silakan pantau grup koordinasi atau website madrasah untuk jadwal masuk sekolah dan pembagian kelas.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="signature" style="margin-top: 25px;">
        <div style="float: left; width: 200px; text-align: center; font-size: 9pt;">
            <div style="padding: 10px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-block;">
                @php
                    $url = route('ppdb.check_verify', $registrant->registration_number);
                    $qrcode = base64_encode(QrCode::format('svg')->size(100)->margin(1)->generate($url));
                @endphp
                <img src="data:image/svg+xml;base64, {!! $qrcode !!}" style="width: 100px; height: 100px;">
                <p style="margin-top: 8px; color: #64748b; font-size: 7pt; font-weight: bold;">SCAN UNTUK VERIFIKASI PANITIA</p>
            </div>
        </div>
        
        <div class="signature-box" style="float: right; width: 250px; text-align: center;">
            @php
                $kepala = get_kepala_madrasah();
            @endphp
            <p>{{ $source->city ?? 'Dawuhan' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
               Kepala Madrasah,
            </p>
            <div style="height: 60px;">
                {{-- Placeholder stempel digital jika ada --}}
            </div>
            <p><strong><u>{{ $kepala->name ?? ($source->default_signer_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                NIP. {{ $kepala->nip ?? ($source->default_signer_nip ?? '-') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px;">
        Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi PPDB {{ $source->school_name ?? '' }}.<br>
        Keaslian dokumen dapat diverifikasi melalui scan QR Code di atas.
    </div>
@endsection
