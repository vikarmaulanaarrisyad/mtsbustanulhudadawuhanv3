<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->teacher->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 0; }
        body { font-family: 'Inter', sans-serif; font-size: 10px; color: #333; line-height: 1.25; margin: 0; padding: 0; background: #fff; }
        .page-wrapper { width: 100%; height: 297mm; display: flex; flex-direction: column; }
        .slip-outer { height: 50%; padding: 0.6cm 1cm; position: relative; box-sizing: border-box; overflow: hidden; }
        .slip-outer:first-child { border-bottom: 2px dashed #bbb; }
        
        .copy-label { 
            position: absolute; top: 10px; right: 20px; 
            font-size: 8px; font-weight: 800; text-transform: uppercase; 
            color: #ccc; letter-spacing: 1px; border: 1px solid #eee; padding: 1px 6px; border-radius: 3px;
        }

        .header { display: flex; border-bottom: 2px solid #2c3e50; padding-bottom: 8px; margin-bottom: 12px; align-items: center; }
        .logo { width: 70px; height: 70px; object-fit: contain; margin-right: 15px; }
        .school-info h2 { margin: 0 0 1px 0; color: #2c3e50; font-size: 11px; text-transform: uppercase; font-weight: 700; }
        .school-info p { margin: 0; color: #555; font-size: 9px; }
        
        .slip-title { text-align: center; margin-bottom: 10px; }
        .slip-title h3 { margin: 0; color: #2980b9; font-size: 13px; text-transform: uppercase; letter-spacing: 1.5px; text-decoration: underline; font-weight: 700; }
        .slip-title p { margin: 1px 0 0 0; font-weight: 700; font-size: 10px; }
        
        .info-table { width: 100%; margin-bottom: 10px; font-size: 10px; }
        .info-table td { padding: 2px 5px; }
        .info-table td:nth-child(odd) { width: 18%; font-weight: 600; color: #555; }
        .info-table td:nth-child(even) { width: 32%; font-weight: 700; color: #000; }
        
        .calc-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; }
        .calc-table th { background: #f8f9fa; padding: 5px; text-align: left; border: 1px solid #dee2e6; color: #2c3e50; font-weight: 700; }
        .calc-table td { padding: 3px 8px; border: 1px solid #dee2e6; }
        .calc-table .amount { text-align: right; width: 120px; font-weight: 700; }
        .calc-table .section-title { font-weight: 800; background: #f1f3f5; color: #2c3e50; }
        
        .total-row th { background: #2c3e50 !important; color: #fff !important; font-size: 11px; padding: 6px 10px; }
        .total-row .amount { color: #fff !important; font-size: 11px; }
        
        .footer { display: flex; justify-content: space-between; margin-top: 15px; }
        .signature-box { width: 180px; text-align: center; }
        .signature-name { margin-top: 35px; font-weight: 700; text-decoration: underline; font-size: 11px; }
        .signature-nip { margin-top: 0px; font-size: 9px; }
        .terbilang-box { background: #f8f9fa; padding: 5px 10px; border-left: 3px solid #2980b9; margin-bottom: 10px; font-style: italic; font-size: 9px; }

        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none; }
            .slip-outer:first-child { border-bottom: 2px dashed #bbb !important; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="no-print" style="position: fixed; top: 20px; left: 20px; z-index: 9999;">
    <a href="{{ route('payrolls.index') }}" style="background: #2c3e50; color: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Kembali ke Data Gaji
    </a>
</div>

<div class="page-wrapper">
    @php
        $mailSetting = \App\Models\MailSetting::first();
        $schoolName = $mailSetting->school_name ?? 'NAMA MADRASAH';
        $subHeader = $mailSetting->sub_header ?? 'YAYASAN PENDIDIKAN';
        $address = $mailSetting->address ?? 'Alamat Belum Diatur';
        $phone = $mailSetting->phone ?? '-';
        $email = $mailSetting->email ?? '-';
        $logo = $mailSetting->logo ?? null;
        
        $logoPath = $logo ? Storage::url($logo) : public_path('images/logo.png');
    @endphp
    
    @for ($i = 0; $i < 2; $i++)
    <div class="slip-outer">
        <div class="copy-label">{{ $i == 0 ? 'Arsip Guru' : 'Arsip Administrasi' }}</div>
        <div class="slip-container">
            <div class="header">
                <img src="{{ $logoPath }}" alt="Logo" class="logo">
                <div class="school-info">
                    <h2>{{ $subHeader }}</h2>
                    <h2 style="font-size: 22px; color: #000; margin-top: 2px;">{{ $schoolName }}</h2>
                    <p>{{ $address }}</p>
                    <p>Telp: {{ $phone }} | Email: {{ $email }}</p>
                </div>
            </div>

            <div class="slip-title">
                <h3>SLIP GAJI GURU & STAF</h3>
                <p>Periode: {{ \Carbon\Carbon::parse($payroll->period)->translatedFormat('F Y') }}</p>
            </div>

            <table class="info-table">
                <tr>
                    <td>Nama Guru</td>
                    <td>: {{ $payroll->teacher->name }}</td>
                    <td>Status</td>
                    <td>: {{ $payroll->teacher->employment_status }}</td>
                </tr>
                <tr>
                    <td>NIP/NUPTK</td>
                    <td>: {{ $payroll->teacher->nip ?? '-' }}</td>
                    <td>Jabatan</td>
                    <td>: {{ $payroll->teacher->position }}</td>
                </tr>
            </table>

            <table class="calc-table">
                <thead>
                    <tr>
                        <th>Deskripsi Keterangan</th>
                        <th class="amount">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="section-title">
                        <td colspan="2">PENGHASILAN (A)</td>
                    </tr>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="amount">{{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                    </tr>
                    @if($payroll->details->where('type', 'allowance')->count() > 0)
                    @foreach($payroll->details->where('type', 'allowance') as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td class="amount">{{ number_format($d->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @endif
                    
                    <tr class="section-title">
                        <td colspan="2">POTONGAN (B)</td>
                    </tr>
                    @if($payroll->details->where('type', 'deduction')->count() > 0)
                    @foreach($payroll->details->where('type', 'deduction') as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td class="amount">({{ number_format($d->amount, 0, ',', '.') }})</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td>-</td>
                        <td class="amount">0</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <th style="text-align: right;">TAKE HOME PAY (A - B) :</th>
                        <th class="amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="terbilang-box">
                Terbilang: <strong>{{ terbilang($payroll->net_salary) }} Rupiah</strong>
            </div>

            <div class="footer">
                <div class="signature-box">
                    <p>Penerima,</p>
                    <p class="signature-name">{{ $payroll->teacher->name }}</p>
                </div>
                <div class="signature-box">
                    @php
                        $bendahara = get_bendahara_madrasah();
                    @endphp
                    <p>{{ $setting->city ?? 'Kota' }}, {{ \Carbon\Carbon::parse($payroll->payment_date)->translatedFormat('d F Y') }}<br>Bendahara,</p>
                    <p class="signature-name">{{ $bendahara->name ?? '..........................................' }}</p>
                    <p class="signature-nip">{{ $bendahara ? 'NIP. ' . ($bendahara->nip ?? '-') : 'NIP. ...................................' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endfor
</div>

</body>
</html>
