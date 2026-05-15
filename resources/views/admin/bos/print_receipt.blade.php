<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi BOS - {{ $exp->receipt_number }}</title>
    <style>
        @page {
            size: 210mm 330mm;
            margin: 15mm;
        }
        body { font-family: 'Times New Roman', serif; padding: 0; color: #000; background: #fff; }
        .receipt-container { 
            width: 180mm; 
            min-height: 290mm;
            border: 2px solid #000; 
            padding: 30px; 
            position: relative;
            margin: 0 auto;
        }
        .title { text-align: center; font-size: 1.4rem; font-weight: bold; text-decoration: underline; margin-bottom: 40px; }
        
        .header-meta { position: absolute; right: 50px; top: 80px; font-size: 1rem; }
        .header-meta table td { padding: 2px 5px; }

        .content-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .content-table td { padding: 8px 5px; vertical-align: top; font-size: 1.1rem; }
        .content-table td:first-child { width: 200px; }
        .content-table td:nth-child(2) { width: 20px; }

        .amount-box { border: 1px solid #000; padding: 10px 20px; display: inline-block; font-weight: bold; font-size: 1.2rem; margin-top: 30px; }
        .terbilang { font-style: italic; font-weight: bold; }

        .footer-signatures { margin-top: 50px; width: 100%; }
        .footer-signatures td { text-align: center; vertical-align: bottom; height: 120px; width: 33%; }
        .signature-name { font-weight: bold; text-decoration: underline; }
        
        @media print {
            body { padding: 0; }
            .receipt-container { border: 1px solid #000; }
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <div class="title">KWITANSI / BUKTI PEMBAYARAN</div>

        <div class="header-meta">
            <table>
                <tr>
                    <td>Tahun Anggaran</td>
                    <td>:</td>
                    <td>{{ $exp->academicYear->academic_year }}</td>
                </tr>
                <tr>
                    <td>Nomor Bukti</td>
                    <td>:</td>
                    <td>{{ $exp->receipt_number }}</td>
                </tr>
            </table>
        </div>

        <table class="content-table">
            <tr>
                <td>Sudah terima dari</td>
                <td>:</td>
                <td>Kepala Madrasah</td>
            </tr>
            <tr>
                <td>Madrasah</td>
                <td>:</td>
                <td>{{ $setting->company_name }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $setting->address }}</td>
            </tr>
            <tr>
                <td>Jumlah uang</td>
                <td>:</td>
                <td><span style="font-weight: bold;">Rp. {{ number_format($exp->amount, 0, ',', '.') }},-</span></td>
            </tr>
            <tr>
                <td>Terbilang</td>
                <td>:</td>
                <td class="terbilang">{{ $terbilang }}</td>
            </tr>
            <tr>
                <td>Untuk pembayaran</td>
                <td>:</td>
                <td>{{ $exp->description }}</td>
            </tr>
            <tr>
                <td>Sumber dana</td>
                <td>:</td>
                <td>Dana BOS Jenjang {{ $exp->level }} - {{ $exp->category }}</td>
            </tr>
        </table>

        <table class="footer-signatures">
            <tr>
                <td>
                    Kepala Madrasah,<br><br><br><br><br>
                    <span class="signature-name">{{ $setting->owner_name ?? '..........................' }}</span>
                </td>
                <td>
                    Penerima uang,<br><br><br><br><br>
                    <span class="signature-name">{{ $exp->receiver ?? '..........................' }}</span>
                </td>
                <td>
                    Lunas dibayar tanggal {{ date('d/m/Y', strtotime($exp->realized_at)) }}<br>
                    Bendahara Madrasah,<br><br><br><br><br>
                    <span class="signature-name">{{ $setting->treasurer_name ?? '..........................' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #28a745; color: #fff; border: none; cursor: pointer; border-radius: 5px;">Cetak Kwitansi</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: #fff; border: none; cursor: pointer; border-radius: 5px; margin-left: 10px;">Tutup</button>
    </div>
</body>
</html>
