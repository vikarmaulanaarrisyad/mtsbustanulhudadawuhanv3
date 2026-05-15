<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pembayaran SPP - {{ $student->nama_lengkap }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .card-container { width: 100%; max-width: 800px; margin: 0 auto; border: 2px solid #000; padding: 20px; box-sizing: border-box; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .header img { position: absolute; left: 0; top: 0; width: 70px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10px; }
        
        .student-info { margin-bottom: 20px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 2px 0; }
        
        .payment-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .payment-table th, .payment-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .payment-table th { bg-color: #f2f2f2; }
        
        .footer { margin-top: 30px; }
        .footer table { width: 100%; }
        .footer .signature { text-align: center; width: 200px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .card-container { border: 1px solid #ccc; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ddd; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 20px; cursor: pointer; background: #007bff; color: #fff; border: none; border-radius: 4px;">CETAK KARTU</button>
        <p style="margin: 5px 0 0; font-size: 11px; color: #666;">Gunakan kertas A4 Portrait</p>
    </div>

    <div class="card-container">
        <div class="header">
            @if($setting && $setting->path_image)
                <img src="{{ asset('storage/' . $setting->path_image) }}" alt="Logo">
            @endif
            <h2 style="font-size: 18px; margin-bottom: 5px;">{{ $setting->company_name ?? 'MADRASAH TSANAWIYAH' }}</h2>
            <h1 style="font-size: 22px; margin-bottom: 5px; text-transform: uppercase;">KARTU PEMBAYARAN SPP</h1>
            <p style="font-size: 11px;">{{ $setting->address ?? '' }} {{ $setting->city ?? '' }} {{ $setting->postal_code ?? '' }}</p>
            <p style="font-size: 11px;">Telp: {{ $setting->phone ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
            <p style="font-size: 11px;">NSM: {{ $setting->nsm ?? '-' }} | NPSN: {{ $setting->npsn ?? '-' }}</p>
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td width="120">NAMA SISWA</td>
                    <td width="10">:</td>
                    <td style="font-weight: bold; border-bottom: 1px dotted #000;">{{ $student->nama_lengkap }}</td>
                    <td width="50"></td>
                    <td width="100">KELAS</td>
                    <td width="10">:</td>
                    <td style="font-weight: bold; border-bottom: 1px dotted #000;">{{ $student->kelas_lengkap }}</td>
                </tr>
                <tr>
                    <td>NIS / NISN</td>
                    <td>:</td>
                    <td style="border-bottom: 1px dotted #000;">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
                    <td></td>
                    <td>TH. PELAJARAN</td>
                    <td>:</td>
                    <td style="border-bottom: 1px dotted #000;">{{ $student->classGroup->academicYear->academic_year ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <table class="payment-table">
            <thead>
                <tr>
                    <th width="30">NO</th>
                    <th>BULAN / PERIODE</th>
                    <th>JUMLAH TAGIHAN</th>
                    <th>TGL BAYAR</th>
                    <th>PARAF / PENERIMA</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $months = [
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni'
                    ];
                @endphp
                @foreach($months as $m => $name)
                    @php
                        $billing = $student->sppBillings->where('month', $m)->first();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="text-align: left;">{{ $name }}</td>
                        <td>{{ $billing ? 'Rp ' . number_format($billing->amount, 0, ',', '.') : '-' }}</td>
                        <td>{{ $billing && $billing->payments->count() > 0 ? $billing->payments->last()->payment_date : '-' }}</td>
                        <td>{{ $billing && $billing->payments->count() > 0 ? $billing->payments->last()->receiver->name : '-' }}</td>
                        <td style="font-weight: bold;">
                            @if($billing)
                                @if($billing->status == 'Paid') LUNAS
                                @elseif($billing->status == 'Partial') CICIL
                                @else -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <table>
                <tr>
                    <td></td>
                    <td class="signature">
                        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                        <br><br><br>
                        <p><b>Bendahara Sekolah</b></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
