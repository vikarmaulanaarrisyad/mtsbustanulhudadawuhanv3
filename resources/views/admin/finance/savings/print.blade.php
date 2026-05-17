<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Tabungan Siswa - {{ $student->nama_lengkap }}</title>
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
        .payment-table th { background-color: #f2f2f2; }
        
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
        <button onclick="window.print()" style="padding: 8px 20px; cursor: pointer; background: #28a745; color: #fff; border: none; border-radius: 4px; font-weight: bold;">CETAK KARTU TABUNGAN</button>
        <p style="margin: 5px 0 0; font-size: 11px; color: #666;">Gunakan kertas A4 Portrait</p>
    </div>

    <div class="card-container">
        <div class="header">
            @if($setting && $setting->path_image)
                <img src="{{ asset('storage/' . $setting->path_image) }}" alt="Logo">
            @endif
            <h2 style="font-size: 18px; margin-bottom: 5px;">{{ $setting->company_name ?? 'MADRASAH TSANAWIYAH' }}</h2>
            <h1 style="font-size: 22px; margin-bottom: 5px; text-transform: uppercase;">KARTU TABUNGAN SISWA</h1>
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
                    <td style="font-weight: bold; border-bottom: 1px dotted #000;">{{ $student->classGroup->kelas_lengkap ?? '-' }}</td>
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
                    <th width="120">TANGGAL</th>
                    <th>NO. REF</th>
                    <th>DEBET (SETOR)</th>
                    <th>KREDIT (TARIK)</th>
                    <th>SALDO</th>
                    <th>PETUGAS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $t->created_at->translatedFormat('d/m/Y H:i') }}</td>
                        <td>{{ $t->reference_no }}</td>
                        <td>{{ $t->type == 'debit' ? 'Rp ' . number_format($t->amount, 0, ',', '.') : '-' }}</td>
                        <td>{{ $t->type == 'credit' ? 'Rp ' . number_format($t->amount, 0, ',', '.') : '-' }}</td>
                        <td style="font-weight: bold;">Rp {{ number_format($t->current_balance, 0, ',', '.') }}</td>
                        <td>{{ $t->creator->name ?? 'System' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 20px; color: #999; text-align: center;">Belum ada riwayat transaksi tabungan</td>
                    </tr>
                @endforelse
                @if($transactions->count() > 0)
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="3" style="text-align: right;">TOTAL AKHIR SALDO:</td>
                        <td colspan="3" style="text-align: left; padding-left: 15px; font-size: 13px; color: #155724;">
                            Rp {{ number_format($student->savings->balance ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                @endif
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
