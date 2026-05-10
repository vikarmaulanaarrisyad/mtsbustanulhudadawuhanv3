<!DOCTYPE html>
<html>
<head>
    <title>Cetak Massal SKL</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 0 40px;
        }

        /* KOP SURAT */
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 25px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo-td {
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo {
            width: 80px;
        }

        .kop-text-td {
            text-align: center;
            vertical-align: middle;
            padding-left: 15px;
            padding-right: 80px;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .kop-text h1 {
            margin: 2px 0;
            font-size: 18pt;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.2;
        }

        .kop-text p {
            margin: 1px 0;
            font-size: 10pt;
            font-style: normal;
            line-height: 1.2;
        }

        .mail-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .mail-title h3 {
            text-decoration: underline;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        .content {
            text-align: justify;
            margin-bottom: 40px;
        }

        .signature-container {
            margin-top: 40px;
            position: relative;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-space {
            height: 80px;
        }

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: never;
        }
    </style>
</head>
<body>
    @php
        $mailSetting = \App\Models\MailSetting::first();
        $generalSetting = \App\Models\Setting::first();
        
        $schoolName = $mailSetting->school_name ?? 'NAMA SEKOLAH';
        $subHeader = $mailSetting->sub_header ?? 'YAYASAN PENDIDIKAN';
        $address = $mailSetting->address ?? 'Alamat Belum Diatur';
        $phone = $mailSetting->phone ?? '-';
        $email = $mailSetting->email ?? '-';
        $website = $mailSetting->website ?? '';
        $logo = $mailSetting->logo ?? null;

        $logoPath = null;
        if ($logo) {
            $logoPath = storage_path('app/public/' . $logo);
            if (!file_exists($logoPath)) {
                $logoPath = public_path('storage/' . $logo);
            }
        }
    @endphp

    @foreach($data as $item)
        @php
            $student = $item['student'];
            $verification = $item['verification'];
            $qrCode = $item['qrCode'];
        @endphp

        <div class="container page-break">
            {{-- KOP SURAT --}}
            <div class="kop-surat">
                <table class="kop-table">
                    <tr>
                        <td class="kop-logo-td">
                            @if ($logoPath && file_exists($logoPath))
                                <img src="{{ $logoPath }}" style="width: 80px;">
                            @else
                                <div style="width: 80px; height: 80px; background: #eee; border: 1px dashed #ccc; text-align: center; line-height: 80px; font-size: 8pt;">
                                    NO LOGO
                                </div>
                            @endif
                        </td>
                        <td class="kop-text-td">
                            <div class="kop-text">
                                <h2>{{ $subHeader }}</h2>
                                <h1>{{ $schoolName }}</h1>
                                @if($generalSetting && $generalSetting->accreditation)
                                    <p style="font-weight: bold; font-size: 11pt; margin-bottom: 2px;">
                                        TERAKREDITASI {{ $generalSetting->accreditation }} &nbsp;&nbsp; 
                                        @if($generalSetting->nsm) NSM: {{ $generalSetting->nsm }} &nbsp;&nbsp; @endif
                                        @if($generalSetting->npsn) NPSN: {{ $generalSetting->npsn }} @endif
                                    </p>
                                @endif
                                <p>{{ $address }}</p>
                                <p>Telp: {{ $phone }} | Email: {{ $email }}</p>
                                @if ($website)
                                    <p>Website: {{ $website }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="border-top: 1px solid #000; margin-top: 2px;"></div>
            </div>

            {{-- CONTENT SKL --}}
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
                            {{ tanggal_indonesia($student->tanggal_lahir) }}</td>
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
                    guru pada tanggal {{ tanggal_indonesia($student->tanggal_keluar) }}, nama yang
                    tersebut di atas dinyatakan:
                </p>

                <div style="text-align: center; margin: 30px 0; border: 2px solid #000; padding: 10px; width: 50%; margin-left: 25%;">
                    <h2 style="margin: 0;">LULUS</h2>
                </div>

                <p style="text-align: justify; line-height: 1.6;">
                    Surat Keterangan ini berlaku sementara sampai dengan diterbitkannya Ijazah asli sebagai bukti kelulusan.
                    Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
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
                    $kepalaName = $student->graduated_principal_name ?: (get_kepala_madrasah()->name ?? ($setting->default_signer_name ?? 'KEPALA MADRASAH'));
                    $kepalaNip = $student->graduated_principal_nip ?: (get_kepala_madrasah()->nip ?? ($setting->default_signer_nip ?? '-'));
                @endphp
                <div class="signature-box" style="float: right; width: 50%; text-align: center;">
                    <p>{{ $setting->city ?? 'Dawuhan' }},
                        {{ tanggal_indonesia($student->tanggal_keluar) }}<br>{{ $setting->default_signer_position ?? 'Kepala Madrasah' }},
                    </p>
                    <div class="signature-space" style="height: 80px;"></div>
                    <p><strong><u>{{ $kepalaName }}</u></strong><br>
                        NIP. {{ $kepalaNip }}</p>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    @endforeach
</body>
</html>
