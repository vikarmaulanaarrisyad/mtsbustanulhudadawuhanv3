@extends('admin.mail.pdf.layout', ['title' => 'Kwitansi Pembayaran PPDB'])

@section('main-content')
    @php
        $isVerified = in_array($registrant->status, ['daftar_ulang_terverifikasi', 'sudah_masuk_siswa']);
        $statusLabel = $isVerified ? 'LUNAS / TERVERIFIKASI' : 'MENUNGGU VERIFIKASI';
        $themeColor = $isVerified ? '#10b981' : '#3b82f6';
    @endphp

    <div style="border: 2px solid {{ $themeColor }}; padding: 15px; position: relative; background: #fff; min-height: 300px;">
        {{-- Watermark Status --}}
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-25deg); font-size: 50pt; color: {{ $themeColor }}; opacity: 0.1; font-weight: bold; z-index: 0; pointer-events: none; width: 100%; text-align: center;">
            {{ $statusLabel }}
        </div>

        <div style="position: relative; z-index: 1;">
            {{-- Header Kwitansi --}}
            <table style="width: 100%; border-bottom: 1px solid #eee; margin-bottom: 15px; padding-bottom: 10px;">
                <tr>
                    <td width="60%">
                        <h2 style="margin: 0; color: {{ $themeColor }};">KWITANSI PEMBAYARAN</h2>
                        <p style="margin: 5px 0 0; font-size: 9pt; color: #64748b;">No. Transaksi: PPDB/{{ date('Ymd') }}/{{ $registrant->id }}</p>
                    </td>
                    <td width="40%" style="text-align: right;">
                        <div style="background: {{ $themeColor }}; color: white; padding: 5px 10px; border-radius: 4px; display: inline-block; font-weight: bold; font-size: 10pt;">
                            {{ $statusLabel }}
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Detail Pembayaran --}}
            <table style="width: 100%; font-size: 10pt; line-height: 1.8;">
                <tr>
                    <td width="25%">Telah Terima Dari</td>
                    <td width="3%">:</td>
                    <td><strong>{{ $registrant->nama_lengkap }}</strong> ({{ $registrant->registration_number }})</td>
                </tr>
                <tr>
                    <td>Uraian Pembayaran</td>
                    <td>:</td>
                    <td>Biaya Daftar Ulang PPDB Tahun Pelajaran {{ $admission->admission_year ?? date('Y') }}</td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>:</td>
                    <td style="text-transform: capitalize;">{{ $registrant->payment_method ? str_replace('_', ' ', $registrant->payment_method) : 'Transfer/Tunai' }}</td>
                </tr>
                <tr>
                    <td>Waktu Bayar</td>
                    <td>:</td>
                    <td>{{ $registrant->confirmed_at ? \Carbon\Carbon::parse($registrant->confirmed_at)->translatedFormat('d F Y, H:i') : '-' }} WIB</td>
                </tr>
            </table>

            {{-- Nominal --}}
            <div style="margin-top: 20px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1;">
                <table style="width: 100%;">
                    <tr>
                        <td width="50%">
                            <span style="font-size: 8pt; color: #64748b; text-transform: uppercase;">Total Pembayaran:</span><br>
                            <strong style="font-size: 14pt; color: #0f172a;">Rp {{ number_format($registrant->payment_amount ?? 0, 0, ',', '.') }}</strong>
                        </td>
                        <td width="50%" style="text-align: right;">
                            <span style="font-size: 8pt; color: #64748b; text-transform: uppercase;">Status:</span><br>
                            <strong style="font-size: 14pt; color: {{ $themeColor }};">{{ $isVerified ? 'LUNAS' : 'DIPROSES' }}</strong>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Footer Kwitansi --}}
            <div style="margin-top: 25px;">
                <div style="float: left; width: 40%; font-size: 8pt; color: #64748b;">
                    @php
                        $url = route('ppdb.check_verify', $registrant->registration_number);
                        $qrcode = base64_encode(QrCode::format('svg')->size(70)->margin(1)->generate($url));
                    @endphp
                    <img src="data:image/svg+xml;base64, {!! $qrcode !!}" style="width: 70px; height: 70px; margin-bottom: 5px;"><br>
                    Scan untuk validasi sistem
                </div>
                <div style="float: right; width: 40%; text-align: center; font-size: 9pt;">
                    {{ $source->city ?? 'Dawuhan' }}, {{ date('d M Y') }}<br>
                    Bendahara PPDB,<br>
                    <div style="height: 40px;"></div>
                    <strong>( ............................ )</strong>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>

    <div style="margin-top: 10px; font-size: 8pt; color: #94a3b8; font-style: italic;">
        * Kwitansi ini adalah bukti pembayaran sah yang dihasilkan oleh sistem. Mohon simpan bukti ini untuk keperluan administrasi selanjutnya.
    </div>
@endsection
