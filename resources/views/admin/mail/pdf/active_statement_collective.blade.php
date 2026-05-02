@extends('admin.mail.pdf.layout', ['title' => 'Surat Keterangan Siswa Aktif (Kolektif)'])

@section('main-content')
    <div class="mail-title">
        <h3>SURAT KETERANGAN AKTIF BELAJAR</h3>
        <p>Nomor: {{ $statement->letter_number }}</p>
    </div>

    <div class="content">
        @php $general = \App\Models\Setting::first(); @endphp
        <p>Yang bertanda tangan di bawah ini Kepala Madrasah {{ $setting->school_name }}, {{ $general->city ?? '...' }},
            Provinsi {{ $general->province ?? '...' }}, dengan ini menerangkan bahwa siswa yang namanya tercantum di bawah
            ini adalah benar-benar siswa Madrasah {{ $setting->school_name }} yang aktif belajar:</p>

        <table class="table-content"
            style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border: 1px solid black;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 5px;">No</th>
                    <th style="border: 1px solid black; padding: 5px;">Nama Lengkap</th>
                    <th style="border: 1px solid black; padding: 5px;">NIS / NISN</th>
                    <th style="border: 1px solid black; padding: 5px;">L/P</th>
                    <th style="border: 1px solid black; padding: 5px;">Kelas</th>
                    <th style="border: 1px solid black; padding: 5px;">Tahun Pelajaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($statement->students as $index => $student)
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $student->nama_lengkap }}</td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $student->nis }} / {{ $student->nisn ?? '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $student->jenis_kelamin }}
                        </td>
                        <td style="border: 1px solid black; padding: 5px;">{{ $student->kelas_lengkap }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;">
                            {{ $student->academicYear->academic_year ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Adalah benar-benar siswa Madrasah {{ $setting->school_name }} yang aktif belajar pada Tahun Pelajaran tersebut
            di atas.</p>

        <p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya, yaitu
            untuk: <strong>{{ $statement->purpose }}</strong>.</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <p>{{ $general->city ?? 'Dawuhan' }},
                {{ tanggal_indonesia($statement->letter_date) }}<br>{{ $statement->signer_position ?? 'Kepala Madrasah' }},
            </p>
            <div class="signature-space"></div>
            <p><strong><u>{{ $statement->signer_name ?? ($general->owner_name ?? 'KEPALA MADRASAH') }}</u></strong><br>
                NIP. {{ $statement->signer_nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
@endsection
