<table>
    <thead>
        <tr>
            <th colspan="{{ $daysInMonth + 7 }}" style="text-align: center; font-weight: bold; font-size: 14pt;">
                REKAPITULASI ABSENSI SISWA BULANAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ $daysInMonth + 7 }}" style="text-align: center; font-weight: bold; font-size: 12pt;">
                {{ strtoupper($setting->company_name ?? 'MTS BUSTANUL HUDA DAWUHAN') }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="2">Kelas</th>
            <th colspan="5">: {{ $classGroup->kelas_lengkap }}</th>
            <th colspan="3">Bulan / Tahun</th>
            <th colspan="5">: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F') }} / {{ $year }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th rowspan="2" style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">NO</th>
            <th rowspan="2" style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">NAMA LENGKAP SISWA</th>
            <th colspan="{{ $daysInMonth }}" style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">TANGGAL</th>
            <th colspan="5" style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">REKAP</th>
        </tr>
        <tr>
            @for($i = 1; $i <= $daysInMonth; $i++)
                <th style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">{{ $i }}</th>
            @endfor
            <th style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">H</th>
            <th style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">S</th>
            <th style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">I</th>
            <th style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">A</th>
            <th style="border: 1px solid #000; text-align: center; background-color: #f2f2f2; font-weight: bold;">T</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $index => $student)
            @php
                $rekap = ['present' => 0, 'sick' => 0, 'permit' => 0, 'absent' => 0, 'late' => 0];
                $studentAttendances = $attendances->get($student->id, collect());
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000; font-weight: bold;">{{ strtoupper($student->nama_lengkap) }}</td>
                @for($i = 1; $i <= $daysInMonth; $i++)
                    @php
                        $att = $studentAttendances->get($i)?->first();
                        $symbol = '';
                        $bgColor = '';
                        if ($att) {
                            if ($att->status == 'present') { $symbol = 'H'; $rekap['present']++; }
                            elseif ($att->status == 'sick') { $symbol = 'S'; $rekap['sick']++; }
                            elseif ($att->status == 'permit') { $symbol = 'I'; $rekap['permit']++; }
                            elseif ($att->status == 'absent') { $symbol = 'A'; $rekap['absent']++; }
                            elseif ($att->status == 'late') { $symbol = 'T'; $rekap['late']++; }
                        }
                        
                        $dateObj = \Carbon\Carbon::createFromDate($year, $month, $i);
                        $isSunday = $dateObj->isSunday();
                        $isHoliday = isset($holidays[$i]);
                        
                        if ($isSunday) $bgColor = '#fca5a5';
                        elseif ($isHoliday) $bgColor = '#fde047';
                    @endphp
                    <td style="border: 1px solid #000; text-align: center; background-color: {{ $bgColor }};">
                        {{ (!$isSunday && !$isHoliday) ? $symbol : '' }}
                    </td>
                @endfor
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $rekap['present'] }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $rekap['sick'] }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $rekap['permit'] }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $rekap['absent'] }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $rekap['late'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
