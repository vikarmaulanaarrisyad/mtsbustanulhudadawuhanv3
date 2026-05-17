@extends('admin.mail.pdf.layout', ['title' => 'Kartu Tabungan Siswa - ' . $student->nama_lengkap])

@section('main-content')
    <style>
        .skl-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .skl-header h3 {
            margin: 0;
            text-decoration: underline;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .student-info {
            margin-bottom: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 3px 0;
            font-size: 11pt;
            font-family: 'Times New Roman', Times, serif;
        }
        .student-info .dotted-border {
            border-bottom: 1px dotted #000;
            font-weight: bold;
        }
        
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-family: 'Times New Roman', Times, serif;
        }
        .payment-table th, .payment-table td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
            font-size: 10pt;
        }
        .payment-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .footer-container {
            margin-top: 30px;
            width: 100%;
        }
        .footer-right {
            float: right;
            width: 250px;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
        }
        .footer-right p {
            margin: 0;
        }
    </style>

    <div class="skl-header">
        <h3>KARTU TABUNGAN SISWA</h3>
    </div>

    <table class="student-info">
        <tr>
            <td width="120">Nama Siswa</td>
            <td width="15">:</td>
            <td class="dotted-border">{{ $student->nama_lengkap }}</td>
            <td width="40"></td>
            <td width="100">Kelas</td>
            <td width="15">:</td>
            <td class="dotted-border">{{ $student->classGroup->kelas_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td class="dotted-border">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</td>
            <td></td>
            <td>Th. Pelajaran</td>
            <td>:</td>
            <td class="dotted-border">{{ $student->classGroup->academicYear->academic_year ?? '-' }}</td>
        </tr>
    </table>

    <table class="payment-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="20%">TANGGAL</th>
                <th width="20%">NO. REF</th>
                <th width="18%">DEBET (SETOR)</th>
                <th width="18%">KREDIT (TARIK)</th>
                <th width="19%">SALDO</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ tanggal_indonesia($t->created_at->format('Y-m-d')) }}</td>
                    <td>{{ $t->reference_no }}</td>
                    <td>{{ $t->type == 'debit' ? 'Rp ' . number_format($t->amount, 0, ',', '.') : '-' }}</td>
                    <td>{{ $t->type == 'credit' ? 'Rp ' . number_format($t->amount, 0, ',', '.') : '-' }}</td>
                    <td style="font-weight: bold;">Rp {{ number_format($t->current_balance, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 20px; color: #999; text-align: center;">Belum ada riwayat transaksi tabungan</td>
                </tr>
            @endforelse
            @if($transactions->count() > 0)
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="3" style="text-align: right;">TOTAL AKHIR SALDO:</td>
                    <td colspan="2" style="text-align: left; padding-left: 15px; font-size: 11pt; color: #155724;">
                        Rp {{ number_format($student->savings->balance ?? 0, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-container">
        <table style="width: 100%; border: none; margin-top: 20px; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
            <tr>
                <td style="width: 50%; text-align: center; border: none; padding: 0;" valign="top">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 0; font-weight: bold;">Wali Kelas</p>
                    <div style="height: 60px;"></div>
                    <p style="margin: 0;"><strong><u>{{ $student->classGroup->homeroomTeacher->name ?? '............................................' }}</u></strong></p>
                    @if(isset($student->classGroup->homeroomTeacher->nip))
                        <p style="margin: 2px 0 0; font-size: 10pt;">NIP. {{ $student->classGroup->homeroomTeacher->nip }}</p>
                    @endif
                </td>
                <td style="width: 50%; text-align: center; border: none; padding: 0;" valign="top">
                    @php
                        $city = \App\Models\Setting::first()->city ?? 'Kota';
                        $bendahara = get_bendahara_madrasah();
                    @endphp
                    <p style="margin: 0;">{{ $city }}, {{ tanggal_indonesia(date('Y-m-d')) }}</p>
                    <p style="margin: 0; font-weight: bold;">Bendahara Madrasah,</p>
                    <div style="height: 60px;"></div>
                    <p style="margin: 0;"><strong><u>{{ $bendahara->name ?? '............................................' }}</u></strong></p>
                    @if(isset($bendahara->nip))
                        <p style="margin: 2px 0 0; font-size: 10pt;">NIP. {{ $bendahara->nip }}</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
@endsection
