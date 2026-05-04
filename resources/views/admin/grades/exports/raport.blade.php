<table>
    <thead>
        <tr>
            <th rowspan="3" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">URT.</th>
            <th colspan="2" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NOMOR</th>
            <th rowspan="3" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NAMA PESERTA</th>
            @foreach($subjects as $index => $gs)
                <th colspan="6" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">
                    {{ chr(65 + ($index % 26)) }}. {{ strtoupper($gs->subject->name) }}
                </th>
            @endforeach
        </tr>
        <tr>
            <th rowspan="2" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NISN (ID SISWA)</th>
            <th rowspan="2" style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center; vertical-align: middle;">NIS (INDUK)</th>
            @foreach($subjects as $gs)
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">7</th>
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">8</th>
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">9</th>
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">10</th>
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">11</th>
                <th style="background-color: #8e44ad; color: white; border: 1px solid #000; text-align: center;">12</th>
            @endforeach
        </tr>
        <tr>
            <!-- Row 3 labels for semesters if needed, following the numbers in screenshot -->
            @foreach($subjects as $gs)
                @for($i=6; $i<=11; $i++)
                    <th style="background-color: #ecf0f1; border: 1px solid #000; text-align: center;">{{ $i }}</th>
                @endfor
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
                        $subjectGrades = $grades->where('student_id', $student->id)->where('subject_id', $gs->subject_id);
                        $total = 0;
                        $count = 0;
                    @endphp
                    @foreach($classLevels as $cl)
                        @foreach([1, 2] as $sem)
                            @php
                                $score = $subjectGrades->where('class_level', $cl)->where('semester', $sem)->first()->score ?? 0;
                                $total += $score;
                                if($score > 0) $count++;
                            @endphp
                            <td style="border: 1px solid #000; text-align: center;">{{ $score }}</td>
                        @endforeach
                    @endforeach
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
