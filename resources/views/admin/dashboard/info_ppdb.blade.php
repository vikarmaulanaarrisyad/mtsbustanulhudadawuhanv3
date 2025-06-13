<table class="table table-bordered table-striped">
    <thead class="table-{{ $statusPendaftaran == 'Dibuka' ? 'success' : 'secondary' }}">
        <tr>
            <th colspan="6" class="text-center">INFORMASI PENERIMAAN PESERTA DIDIK BARU</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <i class="bi bi-arrow-repeat text-danger me-2"></i> Tahun Pelajaran Aktif
            </td>
            <td class="text-center">:</td>
            <td>
                {{ $academicYear->academic_year }} {{ $academicYear->semester->semester_name }}
            </td>
            <td>
                <i class="bi bi-calendar-check text-danger me-2"></i> Tahun Penerimaan Peserta Didik Baru
            </td>
            <td class="text-center">:</td>
            <td>
                {{ $academicYear->academic_year }} {{ $academicYear->semester->semester_name }}
            </td>
        </tr>
        <tr>
            <td>
                <i class="bi bi-diagram-3 text-danger me-2"></i> Gelombang Pendaftaran
            </td>
            <td class="text-center">:</td>
            <td>Gelombang 1</td>
            <td>
                <i class="bi bi-info-circle text-danger me-2"></i> Status Penerimaan Peserta Didik Baru
            </td>
            <td class="text-center">:</td>
            <td><span
                    class="badge bg-{{ $statusPendaftaran == 'Dibuka' ? 'success' : 'secondary' }}">{{ $statusPendaftaran }}</span>
            </td>

        </tr>
        <tr>
            <td>
                <i class="bi bi-calendar-event text-danger me-2"></i> Tanggal Mulai Pendaftaran
            </td>
            <td class="text-center">:</td>
            <td>
                @if ($studentAdmission)
                    {{ tanggal_indonesia($studentAdmission->admission_start_date) }}
                @else
                    <a href="{{ route('student-admissions.index') }}">
                        <i>Belum diatur</i>
                    </a>
                @endif
            </td>
            <td>
                <i class="bi bi-calendar-x text-danger me-2"></i> Tanggal Selesai Pendaftaran
            </td>
            <td class="text-center">:</td>
            <td>
                @if ($studentAdmission)
                    {{ tanggal_indonesia($studentAdmission->admission_end_date) }}
                @else
                    <a href="{{ route('student-admissions.index') }}">
                        <i>Belum diatur</i>
                    </a>
                @endif
            </td>
        </tr>
    </tbody>
</table>
