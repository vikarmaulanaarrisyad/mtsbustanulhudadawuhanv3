@extends('admin.mail.pdf.layout', ['title' => 'Surat Tugas'])

@section('main-content')
    <div class="mail-title">
        <h3>SURAT TUGAS</h3>
        <p>Nomor: {{ $letter->letter_number }}</p>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name }}, menugaskan kepada:</p>
        
        <table class="table-bordered" style="width: 100%; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th width="5%">No</th>
                    <th>Nama / NIP</th>
                    <th>Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letter->teachers as $index => $teacher)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $teacher->name }}</strong><br>
                        NIP. {{ $teacher->nip ?? '-' }}
                    </td>
                    <td>{{ $teacher->position ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width: 100%; margin-left: 10px; margin-bottom: 20px;">
            <tr><td width="25%" valign="top">Untuk Melaksanakan</td><td width="2%" valign="top">:</td><td>{{ $letter->purpose }}</td></tr>
            <tr><td>Tempat Tujuan</td><td>:</td><td>{{ $letter->destination }}</td></tr>
            <tr><td>Waktu Pelaksanaan</td><td>:</td><td>
                {{ \Carbon\Carbon::parse($letter->departure_date)->translatedFormat('d F Y') }}
                @if($letter->return_date)
                    s.d {{ \Carbon\Carbon::parse($letter->return_date)->translatedFormat('d F Y') }}
                @endif
            </td></tr>
            @if($letter->budget_source)
            <tr><td>Sumber Anggaran</td><td>:</td><td>{{ $letter->budget_source }}</td></tr>
            @endif
        </table>

        <p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan setelah selesai melaksanakan tugas agar segera melaporkan hasilnya kepada pimpinan.</p>
    </div>

    <div class="signature">
        @php $general = \App\Models\Setting::first(); @endphp
        <div class="signature-box">
            <p>{{ $general->city ?? 'Dawuhan' }}, {{ \Carbon\Carbon::parse($letter->letter_date)->translatedFormat('d F Y') }}<br>{{ $letter->signer_position ?? 'Kepala Madrasah' }},</p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $letter->signer_name ?? 'KEPALA MADRASAH' }}</u></strong><br>
            NIP. {{ $letter->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
