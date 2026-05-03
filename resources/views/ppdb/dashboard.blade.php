@extends('layouts.ppdb')

@section('title', 'Dashboard PPDB')

@section('content')

    {{-- WELCOME BANNER --}}
    <div class="welcome-banner">
        <h4><i class="fas fa-hand-peace mr-2"></i>Selamat Datang, {{ $user->name }}!</h4>
        @if($ppdbOpen)
            <p>Pendaftaran PPDB Tahun {{ $academicYear->academic_year ?? '' }} sedang dibuka.</p>
        @else
            <p>Informasi pendaftaran PPDB akan ditampilkan di sini.</p>
        @endif
    </div>

    @if(!$ppdbOpen && !$registrant)
        {{-- PPDB BELUM BUKA --}}
        <div class="ppdb-card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Pendaftaran PPDB Belum Dibuka</h5>
                <p class="text-muted">Silakan tunggu informasi pembukaan pendaftaran dari sekolah.</p>
            </div>
        </div>

    @elseif($registrant && $registrant->status == 'sudah_masuk_siswa')
        {{-- DASHBOARD AKADEMIK SISWA --}}
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="ppdb-card text-center py-4">
                    <div class="card-body">
                        <div class="mb-3">
                            @if($student->profile && $student->profile->foto)
                                <img src="{{ asset('storage/' . $student->profile->foto) }}" class="rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px;">
                                    <i class="fas fa-user-graduate fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="font-weight-bold mb-1">{{ $student->nama_lengkap }}</h4>
                        <p class="text-muted mb-3">NISN: {{ $student->nisn }}</p>
                        
                        <div class="badge badge-success px-3 py-2 mb-3">Status: Siswa Aktif</div>
                        
                        {{-- TOMBOL ABSENSI MANDIRI --}}
                        <div id="attendance-section" class="mb-3">
                            @if(!$hasCheckedInToday)
                                @if($isWorkDay && $isCheckInTime && !$isHoliday)
                                    <button type="button" onclick="submitAttendance()" class="btn btn-primary btn-block shadow-sm py-2">
                                        <i class="fas fa-check-circle mr-1"></i> Absen Hari Ini
                                    </button>
                                @else
                                    <div class="alert alert-warning py-2 mb-0 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $attendanceMessage }}
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-success py-2 mb-0 text-sm">
                                    <i class="fas fa-check-double mr-1"></i> Anda Sudah Absen
                                </div>
                            @endif
                        </div>
                        
                        {{-- TOMBOL CETAK KARTU --}}
                        <div class="mb-3">
                            <a href="{{ route('students.card', $student->id) }}" target="_blank" class="btn btn-outline-success btn-block shadow-sm py-2 text-sm font-weight-bold">
                                <i class="fas fa-id-card mr-1"></i> Cetak Kartu Siswa
                            </a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-left">
                            <p class="mb-1 text-sm text-muted">Kelas Saat Ini:</p>
                            <h5 class="font-weight-bold text-primary"><i class="fas fa-school mr-2"></i>{{ $student->kelas_lengkap }}</h5>
                            
                            <p class="mb-1 mt-3 text-sm text-muted">Wali Kelas:</p>
                            <p class="font-weight-bold mb-0"><i class="fas fa-user-tie mr-2"></i>{{ $student->classGroup->homeroomTeacher->name ?? 'Belum Ditentukan' }}</p>
                        </div>
                        
                        <hr>
                        
                        {{-- PRESENSI SUMMARY --}}
                        <div class="row no-gutters mt-3">
                            <div class="col-3 text-center">
                                <div class="text-xs text-muted">Hadir</div>
                                <div class="font-weight-bold text-success">{{ $attendanceStats['H'] }}</div>
                            </div>
                            <div class="col-3 text-center border-left">
                                <div class="text-xs text-muted">Izin</div>
                                <div class="font-weight-bold text-info">{{ $attendanceStats['I'] }}</div>
                            </div>
                            <div class="col-3 text-center border-left">
                                <div class="text-xs text-muted">Sakit</div>
                                <div class="font-weight-bold text-warning">{{ $attendanceStats['S'] }}</div>
                            </div>
                            <div class="col-3 text-center border-left">
                                <div class="text-xs text-muted">Alfa</div>
                                <div class="font-weight-bold text-danger">{{ $attendanceStats['A'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AGENDA SEKOLAH --}}
                <div class="ppdb-card mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="card-title font-weight-bold mb-0"><i class="fas fa-calendar-check mr-2 text-primary"></i> Agenda Terdekat</h6>
                    </div>
                    <div class="card-body p-0">
                        @if($agendas->isEmpty())
                            <div class="p-4 text-center">
                                <p class="text-muted text-sm mb-0">Belum ada agenda terdekat.</p>
                            </div>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($agendas as $agenda)
                                    <li class="list-group-item px-3 py-2 border-0">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 font-weight-bold text-sm">{{ $agenda->title }}</h6>
                                            <small class="text-primary font-weight-bold">{{ \Carbon\Carbon::parse($agenda->start_date)->format('d M') }}</small>
                                        </div>
                                        <p class="mb-0 text-xs text-muted text-truncate">{{ $agenda->description }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="ppdb-card">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title font-weight-bold mb-0"><i class="fas fa-calendar-alt mr-2 text-primary"></i> Jadwal Pelajaran</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($schedules->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-tasks fa-3x text-muted mb-3 opacity-25"></i>
                                <p class="text-muted">Jadwal pelajaran belum tersedia untuk kelas Anda.</p>
                            </div>
                        @else
                            <div class="accordion" id="scheduleAccordion">
                                @php
                                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach($days as $day)
                                    @if(isset($schedules[$day]))
                                        <div class="border-bottom">
                                            <div class="p-3 bg-light d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#collapse{{ $day }}" style="cursor: pointer;">
                                                <h6 class="font-weight-bold mb-0">{{ $day }}</h6>
                                                <span class="badge badge-primary">{{ $schedules[$day]->count() }} Mapel</span>
                                            </div>
                                            <div id="collapse{{ $day }}" class="collapse show" data-parent="#scheduleAccordion">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover mb-0">
                                                        <thead class="bg-white text-xs text-uppercase text-muted">
                                                            <tr>
                                                                <th class="pl-3">Jam</th>
                                                                <th>Mata Pelajaran</th>
                                                                <th>Guru</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($schedules[$day] as $sch)
                                                                <tr>
                                                                    <td class="pl-3 py-2">
                                                                        <span class="text-sm font-weight-bold">{{ $sch->start_time }} - {{ $sch->end_time }}</span>
                                                                    </td>
                                                                    <td class="py-2">
                                                                        <div class="font-weight-bold">{{ $sch->subject->subject_name ?? '-' }}</div>
                                                                    </td>
                                                                    <td class="py-2 text-sm">
                                                                        {{ $sch->teacher->name ?? '-' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif(!$registrant)
        {{-- FORM BIODATA --}}
        @include('ppdb.form-biodata', ['action' => route('ppdb.store_biodata'), 'method' => 'POST'])

    @else
        {{-- STATUS PENDAFTARAN --}}
        @include('ppdb.status')
    @endif

@endsection

@push('scripts')
<script>
    function submitAttendance() {
        Swal.fire({
            title: 'Konfirmasi Kehadiran',
            text: 'Apakah Anda ingin melakukan absensi hadir untuk hari ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            confirmButtonText: 'Ya, Absen Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $.post('{{ route("ppdb.store_attendance") }}', { _token: '{{ csrf_token() }}' })
                    .done(response => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message })
                            .then(() => location.reload());
                    })
                    .fail(xhr => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    });
            }
        });
    }
</script>
@endpush
