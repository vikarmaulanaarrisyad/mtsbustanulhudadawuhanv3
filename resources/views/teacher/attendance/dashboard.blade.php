@extends('layouts.app')

@section('title', 'Presensi Harian')
@section('subtitle', 'Guru & Staf')

@section('content')
<div class="row">
    <div class="col-md-6">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-clock mr-1"></i> Waktu Presensi</h3>
            </x-slot>

            <div class="text-center py-4">
                <h1 id="currentClock" class="display-4 font-weight-bold">00:00:00</h1>
                <p class="text-muted">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                
                @if($holiday)
                    <div class="alert alert-info">
                        <h5><i class="fas fa-calendar-times"></i> Hari Libur</h5>
                        {{ $holiday->name }}
                    </div>
                @elseif($isWeekend)
                    <div class="alert alert-secondary">
                        <h5><i class="fas fa-couch"></i> Akhir Pekan</h5>
                        Bukan hari kerja aktif.
                    </div>
                @else
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" onclick="doCheckIn()" class="btn btn-lg btn-success btn-block py-3" {{ $canCheckIn ? '' : 'disabled' }}>
                                    <i class="fas fa-sign-in-alt fa-2x mb-2"></i><br>
                                    PRESENSI MASUK
                                </button>
                                <small class="text-muted">Batas: {{ $setting->check_in_start }} - {{ $setting->check_in_end }}</small>
                            </div>
                            <div class="col-6">
                                <button type="button" onclick="doCheckOut()" class="btn btn-lg btn-warning btn-block py-3" {{ $canCheckOut ? '' : 'disabled' }}>
                                    <i class="fas fa-sign-out-alt fa-2x mb-2"></i><br>
                                    PRESENSI PULANG
                                </button>
                                <small class="text-muted">Batas: {{ $setting->check_out_start }} - {{ $setting->check_out_end }}</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </x-card>
    </div>

    <div class="col-md-6">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-calendar-day mr-1"></i> Jadwal Mengajar Hari Ini</h3>
            </x-slot>
            
            @php
                $todaySchedule = \App\Models\ClassSchedule::with(['subject', 'classGroup'])
                    ->where('teacher_id', $teacher->id)
                    ->where('day', \Carbon\Carbon::now()->dayOfWeekIso)
                    ->orderBy('start_time')
                    ->get();
            @endphp

            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todaySchedule as $js)
                        <tr>
                            <td><span class="badge badge-info">{{ $js->start_time }} - {{ $js->end_time }}</span></td>
                            <td>{{ $js->subject->name }}</td>
                            <td>{{ $js->classGroup->class_group }} {{ $js->classGroup->sub_class_group }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Tidak ada jadwal mengajar hari ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Status Presensi Hari Ini</h3>
            </x-slot>
            
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>Jam Masuk</b> <a class="float-right text-success font-weight-bold">{{ $attendance->check_in ?? '--:--' }}</a>
                </li>
                <li class="list-group-item">
                    <b>Jam Pulang</b> <a class="float-right text-warning font-weight-bold">{{ $attendance->check_out ?? '--:--' }}</a>
                </li>
                <li class="list-group-item">
                    <b>Status</b> <a class="float-right"><span class="badge badge-{{ $attendance->status_color ?? 'secondary' }}">{{ $attendance->status_label ?? 'Belum Presensi' }}</span></a>
                </li>
                <li class="list-group-item">
                    <b>IP Address</b> <a class="float-right text-muted text-sm">{{ $attendance->check_in_ip ?? '-' }}</a>
                </li>
            </ul>
        </x-card>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Histori Presensi Bulan Ini</h3>
            </x-slot>
            
            @php
                $history = \App\Models\Attendance::where('teacher_id', $teacher->id)
                    ->whereMonth('date', date('m'))
                    ->whereYear('date', date('Y'))
                    ->orderBy('date', 'desc')
                    ->get();
            @endphp

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td>{{ $h->date->translatedFormat('d F Y') }}</td>
                        <td>{{ $h->check_in ?? '-' }}</td>
                        <td>{{ $h->check_out ?? '-' }}</td>
                        <td><span class="badge badge-{{ $h->status_color }}">{{ $h->status_label }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Belum ada data bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        let now = new Date();
        let h = String(now.getHours()).padStart(2, '0');
        let m = String(now.getMinutes()).padStart(2, '0');
        let s = String(now.getSeconds()).padStart(2, '0');
        $('#currentClock').text(h + ":" + m + ":" + s);
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    function doCheckIn() {
        Swal.fire({
            title: 'Kirim Presensi Masuk?',
            text: 'Pastikan Anda sudah berada di lokasi kerja.',
            icon: 'question', showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("teacher.attendance.check-in") }}', { _token: '{{ csrf_token() }}' })
                .done(response => {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message }).then(() => location.reload());
                });
            }
        });
    }

    function doCheckOut() {
        Swal.fire({
            title: 'Kirim Presensi Pulang?',
            icon: 'question', showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("teacher.attendance.check-out") }}', { _token: '{{ csrf_token() }}' })
                .done(response => {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message }).then(() => location.reload());
                });
            }
        });
    }
</script>
@endpush
