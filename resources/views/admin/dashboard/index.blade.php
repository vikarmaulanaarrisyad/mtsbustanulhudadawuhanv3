@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $studentsCount }}</h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('students.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $teachersCount }}</h3>
                <p>Guru & Staf</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('teachers.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning text-white">
            <div class="inner">
                <h3>{{ $classesCount }}</h3>
                <p>Rombel / Kelas</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('class-groups.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $subjectsCount }}</h3>
                <p>Mata Pelajaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('subjects.index') }}" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

<div class="row">
    <div class="col-md-12">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Tren Kehadiran (7 Hari Terakhir)</h3>
            </x-slot>
            <div class="chart">
                <canvas id="attendanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </x-card>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-history mr-1"></i> Scan Presensi Siswa Terakhir (Hari Ini)</h3>
            </x-slot>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $ra)
                        <tr>
                            <td>{{ $ra->time }}</td>
                            <td>{{ $ra->student->nama_lengkap }}</td>
                            <td>{{ $ra->classGroup->kelas_lengkap }}</td>
                            <td>
                                <span class="badge badge-{{ $ra->status_color }}">{{ $ra->status_label }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada aktivitas presensi hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-slot name="footer">
                <a href="{{ route('student-attendances.index') }}" class="btn btn-sm btn-link text-primary">Lihat Semua Log <i class="fas fa-chevron-right ml-1"></i></a>
            </x-slot>
        </x-card>
    </div>

    <div class="col-md-4">
        <!-- Info Boxes -->
        <div class="info-box mb-3 bg-primary">
            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kehadiran Guru Hari Ini</span>
                <span class="info-box-number">{{ $teacherAttendanceCount }} / {{ $teachersCount }}</span>
            </div>
        </div>

        <div class="info-box mb-3 bg-indigo">
            <span class="info-box-icon"><i class="fas fa-id-badge"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Kehadiran Siswa Hari Ini</span>
                <span class="info-box-number">{{ $studentAttendanceCount }} / {{ $studentsCount }}</span>
            </div>
        </div>

        <x-card>
            <x-slot name="header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi Sistem</h3>
            </x-slot>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <b>Tahun Pelajaran:</b> <span class="float-right text-success">{{ $academicYear->academic_year ?? '-' }}</span>
                </li>
                <li class="list-group-item">
                    <b>Semester:</b> <span class="float-right">{{ $academicYear->semester->name ?? '-' }}</span>
                </li>
                <li class="list-group-item">
                    <b>Konten Website:</b> <span class="float-right text-primary">{{ $postsCount }} Post</span>
                </li>
            </ul>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($attendanceTrend['labels']) !!},
                datasets: [
                    {
                        label: 'Siswa',
                        backgroundColor: 'rgba(102, 16, 242, 0.2)',
                        borderColor: 'rgba(102, 16, 242, 1)',
                        data: {!! json_encode($attendanceTrend['students']) !!},
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Guru',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        data: {!! json_encode($attendanceTrend['teachers']) !!},
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
