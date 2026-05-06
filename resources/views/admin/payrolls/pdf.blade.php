<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $payroll->teacher->name }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; line-height: 1.3; margin: 0; padding: 0; }
        .slip-outer { width: 100%; height: 11.8cm; padding: 0.5cm 0; box-sizing: border-box; position: relative; overflow: hidden; }
        
        .header { width: 100%; border-bottom: 2px solid #2c3e50; padding-bottom: 5px; margin-bottom: 12px; }
        .logo { width: 70px; }
        .school-info { text-align: left; padding-left: 5px; }
        .school-info h2 { margin: 0; font-size: 11px; text-transform: uppercase; color: #2c3e50; font-weight: bold; line-height: 1.1; }
        .school-info .school-name { font-size: 18px; color: #000; margin-top: 2px; }
        .school-info p { margin: 2px 0 0 0; font-size: 8.5px; color: #555; }
        
        .slip-title { text-align: center; margin-bottom: 12px; }
        .slip-title h3 { margin: 0; font-size: 13px; text-transform: uppercase; color: #2980b9; letter-spacing: 1.5px; text-decoration: underline; font-weight: bold; }
        .slip-title p { margin: 2px 0 0 0; font-weight: bold; font-size: 10px; }
        
        .info-table { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        .info-table td { padding: 3px 4px; }
        .info-table .label { width: 18%; font-weight: bold; color: #555; }
        .info-table .value { width: 32%; font-weight: bold; color: #000; }
        
        .calc-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .calc-table th { background: #f8f9fa; padding: 6px; text-align: left; border: 1px solid #dee2e6; color: #2c3e50; font-weight: bold; }
        .calc-table td { padding: 5px 8px; border: 1px solid #dee2e6; }
        .amount { text-align: right; width: 110px; font-weight: bold; }
        .section-title { font-weight: bold; background: #f1f3f5; color: #2c3e50; }
        
        .total-row { background: #2c3e50; color: #ffffff; font-weight: bold; }
        .total-row td { padding: 8px 10px; border: 1px solid #2c3e50; font-size: 11px; }
        
        .terbilang-box { background: #f8f9fa; padding: 8px 12px; border-left: 4px solid #2980b9; margin-bottom: 15px; font-style: italic; font-size: 9px; }
        
        .footer-table { width: 100%; margin-top: 10px; }
        .footer-table td { text-align: center; width: 50%; }
        .signature-name { margin-top: 40px; font-weight: bold; text-decoration: underline; font-size: 10.5px; }
    </style>
</head>
<body>

    @php
        $mailSetting = \App\Models\MailSetting::first();
        $schoolName = $mailSetting->school_name ?? 'NAMA MADRASAH';
        $subHeader = $mailSetting->sub_header ?? 'YAYASAN PENDIDIKAN';
        $address = $mailSetting->address ?? 'Alamat Belum Diatur';
        $logo = $mailSetting->logo ?? null;
        
        // DomPDF needs absolute local path for images usually
        $logoPath = $logo ? storage_path('app/public/' . $logo) : public_path('images/logo.png');
    @endphp

    @for ($i = 0; $i < 2; $i++)
    <div class="slip-outer" style="{{ $i == 0 ? 'border-bottom: 1px dashed #999;' : '' }}">
        <div style="text-align: right; font-size: 8px; color: #999; margin-bottom: 1px;">
            {{ $i == 0 ? 'ARSIP GURU' : 'ARSIP ADMINISTRASI' }}
        </div>
        
        <table class="header" style="margin-bottom: 10px;">
            <tr>
                <td width="80" valign="middle" style="text-align: center;">
                    @if(file_exists($logoPath))
                        <img src="{{ $logoPath }}" style="width: 70px; height: auto;">
                    @endif
                </td>
                <td class="school-info" valign="middle">
                    <h2>{{ $subHeader }}</h2>
                    <h2 class="school-name">{{ $schoolName }}</h2>
                    <p>{{ $address }}</p>
                    <p>Telp: {{ $mailSetting->phone ?? '-' }} | Email: {{ $mailSetting->email ?? '-' }}</p>
                </td>
            </tr>
        </table>

        <div class="slip-title">
            <h3>SLIP GAJI GURU & STAF</h3>
            <p>Periode: {{ \Carbon\Carbon::parse($payroll->period)->translatedFormat('F Y') }}</p>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Nama Guru</td>
                <td class="value">: {{ $payroll->teacher->name }}</td>
                <td class="label">Status</td>
                <td class="value">: {{ $payroll->teacher->employment_status }}</td>
            </tr>
            <tr>
                <td class="label">NIP/NUPTK</td>
                <td class="value">: {{ $payroll->teacher->nip ?? '-' }}</td>
                <td class="label">Jabatan</td>
                <td class="value">: {{ $payroll->teacher->position }}</td>
            </tr>
        </table>

        <table class="calc-table" style="margin-bottom: 8px;">
            <thead>
                <tr>
                    <th style="padding: 4px;">Deskripsi Keterangan</th>
                    <th class="amount" width="120" style="padding: 4px;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="section-title">
                    <td colspan="2" style="padding: 2px 4px;">PENGHASILAN (A)</td>
                </tr>
                <tr>
                    <td style="padding: 2px 4px;">Gaji Pokok</td>
                    <td class="amount" style="padding: 2px 4px;">{{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                </tr>
                @foreach($payroll->details->where('type', 'allowance') as $d)
                <tr>
                    <td style="padding: 2px 4px;">{{ $d->name }}</td>
                    <td class="amount" style="padding: 2px 4px;">{{ number_format($d->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                <tr class="section-title">
                    <td colspan="2" style="padding: 2px 4px;">POTONGAN (B)</td>
                </tr>
                @if($payroll->details->where('type', 'deduction')->count() > 0)
                    @foreach($payroll->details->where('type', 'deduction') as $d)
                    <tr>
                        <td style="padding: 2px 4px;">{{ $d->name }}</td>
                        <td class="amount" style="padding: 2px 4px;">({{ number_format($d->amount, 0, ',', '.') }})</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="padding: 2px 4px;">-</td>
                        <td class="amount" style="padding: 2px 4px;">0</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td style="text-align: right; padding: 5px 4px;">TAKE HOME PAY (A - B) :</td>
                    <td class="amount" style="padding: 5px 4px;">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="terbilang-box" style="padding: 5px; margin-bottom: 10px; font-size: 9px;">
            Terbilang: <strong>{{ terbilang($payroll->net_salary) }} Rupiah</strong>
        </div>

        <table class="footer-table" style="margin-top: 10px;">
            <tr>
                <td>
                    <p>Penerima,</p>
                    <div class="signature-name" style="margin-top: 35px;">{{ $payroll->teacher->name }}</div>
                </td>
                <td>
                    @php
                        $bendahara = get_bendahara_madrasah();
                    @endphp
                    <p>{{ \App\Models\Setting::first()->city ?? 'Kota' }}, {{ \Carbon\Carbon::parse($payroll->payment_date)->translatedFormat('d F Y') }}<br>Bendahara,</p>
                    <div class="signature-name" style="margin-top: 35px;">{{ $bendahara->name ?? '..........................................' }}</div>
                    <div style="font-size: 8px;">{{ $bendahara ? 'NIP. ' . ($bendahara->nip ?? '-') : '' }}</div>
                </td>
            </tr>
        </table>
    </div>
    @endfor

</body>
</html>
