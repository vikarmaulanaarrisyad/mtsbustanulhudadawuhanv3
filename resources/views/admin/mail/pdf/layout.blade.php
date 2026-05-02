<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; margin: 0; padding: 0; }
        .container { padding: 0 40px; }
        
        /* KOP SURAT */
        .kop-surat { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-table { width: 100%; border-collapse: collapse; }
        .kop-logo-td { width: 80px; vertical-align: middle; text-align: left; }
        .kop-logo { width: 80px; }
        .kop-text-td { text-align: center; vertical-align: middle; }
        .kop-text h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .kop-text h1 { margin: 0; font-size: 18pt; text-transform: uppercase; font-weight: bold; }
        .kop-text p { margin: 0; font-size: 10pt; }
        .header-line { border-top: 1px solid #000; margin-top: 2px; }

        .mail-info { margin-bottom: 20px; }
        .mail-info table { width: 100%; border-collapse: collapse; }
        .mail-info td { vertical-align: top; }

        .mail-title { text-align: center; margin-bottom: 30px; }
        .mail-title h3 { text-decoration: underline; margin-bottom: 0; text-transform: uppercase; }
        .mail-title p { margin-top: 0; }

        .content { text-align: justify; margin-bottom: 40px; }
        
        .signature { width: 100%; }
        .signature table { width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="kop-surat">
            <table class="kop-table">
                <tr>
                    <td class="kop-logo-td">
                        @if($setting && $setting->logo && file_exists(storage_path('app/public/' . $setting->logo)))
                            <img src="{{ storage_path('app/public/' . $setting->logo) }}" class="kop-logo">
                        @endif
                    </td>
                    <td class="kop-text-td">
                        <div class="kop-text">
                            <h2>{{ $setting->sub_header ?? 'YAYASAN PENDIDIKAN' }}</h2>
                            <h1>{{ $setting->school_name ?? 'NAMA SEKOLAH' }}</h1>
                            <p>{{ $setting->address ?? 'Alamat Sekolah Belum Diatur' }}</p>
                            <p>Telp: {{ $setting->phone ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
                            @if($setting && $setting->website)
                                <p>Website: {{ $setting->website }}</p>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
            @if($setting && $setting->header_line_style == 'double')
                <div class="header-line"></div>
            @endif
        </div>

        @yield('main-content')

    </div>
</body>
</html>
