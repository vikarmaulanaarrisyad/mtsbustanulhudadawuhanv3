<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
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
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo-td {
            width: 80px;
            vertical-align: middle;
            text-align: left;
        }

        .kop-logo {
            width: 80px;
        }

        .kop-text-td {
            text-align: center;
            vertical-align: middle;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .kop-text h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            font-weight: bold;
            line-height: 1.1;
        }

        .kop-text p {
            margin: 0;
            font-size: 9pt;
            font-style: italic;
            line-height: 1.1;
        }

        .header-line {
            border-top: 1px solid #000;
            margin-top: 2px;
        }

        .mail-info {
            margin-bottom: 20px;
        }

        .mail-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .mail-info td {
            vertical-align: top;
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

        .mail-title p {
            margin-top: 0;
        }

        .content {
            text-align: justify;
            margin-bottom: 40px;
        }

        .signature {
            width: 100%;
        }

        .signature table {
            width: 100%;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-space {
            height: 80px;
        }
    </style>
</head>

<body>
    <div class="container">
        @php
            $mailSetting = \App\Models\MailSetting::first();

            // Gunakan data yang dilempar dari controller ($setting), jika tidak ada gunakan $mailSetting
            $source = $mailSetting;

            $generalSetting = \App\Models\Setting::first();
            
            $schoolName = $source->school_name ?? 'NAMA SEKOLAH';
            $subHeader = $source->sub_header ?? 'YAYASAN PENDIDIKAN';
            $address = $source->address ?? 'Alamat Belum Diatur';
            $phone = $source->phone ?? '-';
            $email = $source->email ?? '-';
            $website = $source->website ?? '';
            $logo = $source->logo ?? null;
            $lineStyle = $source->header_line_style ?? 'double';
            $city = $generalSetting->city ?? 'Blitar';
            $province = $generalSetting->province ?? 'Jawa Timur';

            $logoPath = null;
            if ($logo) {
                $logoPath = storage_path('app/public/' . $logo);
                if (!file_exists($logoPath)) {
                    $logoPath = public_path('storage/' . $logo);
                }
            }
        @endphp

        <div class="kop-surat"
            style="{{ $lineStyle == 'none' ? 'border-bottom: none;' : 'border-bottom: 3px solid #000;' }}">
            <table class="kop-table">
                <tr>
                    <td class="kop-logo-td">
                        @if ($logoPath && file_exists($logoPath))
                            <img src="{{ $logoPath }}" style="width: 80px;">
                        @else
                            <div
                                style="width: 80px; height: 80px; background: #eee; border: 1px dashed #ccc; text-align: center; line-height: 80px; font-size: 8pt;">
                                NO LOGO
                            </div>
                        @endif
                    </td>
                    <td class="kop-text-td">
                        <div class="kop-text">
                            <h2>{{ $subHeader }}</h2>
                            <h1>{{ $schoolName }}</h1>
                            <p>{{ $address }}</p>
                            <p>Telp: {{ $phone }} | Email: {{ $email }}</p>
                            @if ($website)
                                <p>Website: {{ $website }}</p>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
            @if ($lineStyle == 'double')
                <div style="border-top: 1px solid #000; margin-top: 2px;"></div>
            @endif
        </div>

        @yield('main-content')

    </div>
</body>

</html>
