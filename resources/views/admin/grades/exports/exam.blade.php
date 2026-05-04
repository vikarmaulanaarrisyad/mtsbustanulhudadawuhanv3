<table>
    <thead>
        <tr>
            <th rowspan="2" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">URT.</th>
            <th colspan="2" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NOMOR</th>
            <th rowspan="2" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NAMA PESERTA</th>
            @foreach($subjects as $index => $gs)
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">
                    {{ chr(65 + ($index % 26)) }}. {{ strtoupper($gs->subject->name) }}
                </th>
            @endforeach
        </tr>
        <tr>
            <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NISN (ID SISWA)</th>
            <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NIS (INDUK)</th>
            @foreach($subjects as $gs)
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">NILAI UM</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($students as $index => $student)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ $student->nisn }}</td>
                <td style="border: 1px solid #000;">{{ $student->nis }}</td>
                <td style="border: 1px solid #000;">{{ $student->nama_lengkap }}</td>
                @foreach($subjects as $gs)
                    @php
                        $score = $grades->where('student_id', $student->id)->where('subject_id', $gs->subject_id)->first()->score ?? 0;
                    @endphp
                    <td style="border: 1px solid #000; text-align: center;">{{ $score }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
