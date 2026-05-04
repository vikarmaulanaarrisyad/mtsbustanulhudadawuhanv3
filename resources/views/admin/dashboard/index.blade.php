@extends($layout)

@section('title', 'Dashboard Admin')

@section('content')
<!-- PREMIUM WELCOME BANNER -->
<div class="row animate__animated animate__fadeIn">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-premium overflow-hidden position-relative" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h1 class="font-weight-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                        <p class="mb-4 opacity-8 text-lg font-weight-light">
                            Dashboard pusat kendali operasional Madrasah. Pantau data akademik, kehadiran, dan administrasi dalam satu tampilan modern.
                        </p>
                        <div class="d-flex flex-wrap">
                            <div class="mr-4 mb-2">
                                <span class="d-block small opacity-70">Tahun Pelajaran</span>
                                <span class="font-weight-bold"><i class="fas fa-calendar-alt mr-1"></i> {{ $academicYear->academic_year ?? '-' }}</span>
                            </div>
                            <div class="mr-4 mb-2">
                                <span class="d-block small opacity-70">Semester</span>
                                <span class="font-weight-bold"><i class="fas fa-clock mr-1"></i> {{ $academicYear->semester->semester_name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <img src="https://illustrations.popsy.co/white/working-from-home.svg" alt="Illustration" class="img-fluid" style="max-height: 180px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2));">
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
        </div>
    </div>
</div>

<!-- PREMIUM STATS CARDS -->
<div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
    <div class="col-lg-3 col-sm-6">
        <div class="premium-stat-card glass-blue">
            <div class="inner">
                <h3>{{ $studentsCount }}</h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="icon-circle shadow-blue">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('students.index') }}" class="card-link">Detail <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="premium-stat-card glass-teal">
            <div class="inner">
                <h3>{{ $teachersCount }}</h3>
                <p>Guru & Staf</p>
            </div>
            <div class="icon-circle shadow-teal">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('teachers.index') }}" class="card-link">Detail <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="premium-stat-card glass-orange">
            <div class="inner">
                <h3>{{ $classesCount }}</h3>
                <p>Rombel Kelas</p>
            </div>
            <div class="icon-circle shadow-orange">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('class-groups.index') }}" class="card-link">Detail <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="premium-stat-card glass-red">
            <div class="inner">
                <h3>{{ $subjectsCount }}</h3>
                <p>Mata Pelajaran</p>
            </div>
            <div class="icon-circle shadow-red">
                <i class="fas fa-book"></i>
            </div>
            <a href="{{ route('subjects.index') }}" class="card-link">Detail <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8 animate__animated animate__fadeInLeft" style="animation-delay: 0.2s;">
        <!-- ATTENDANCE CHART -->
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-chart-line mr-2 text-primary"></i> Tren Kehadiran Siswa & Guru
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="chart" style="height: 300px;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- RECENT ATTENDANCE TABLE -->
        <div class="card shadow-sm border-0 rounded-20">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-clock mr-2 text-info"></i> Log Presensi Terbaru (Hari Ini)
                </h5>
                <a href="{{ route('student-attendances.index') }}" class="btn btn-sm btn-soft-info rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="recentAttendanceTable">
                        <thead class="bg-light-soft text-uppercase small font-weight-bold">
                            <tr>
                                <th class="pl-4 py-3">Waktu</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th class="text-center pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendances as $ra)
                            <tr>
                                <td class="pl-4 font-weight-bold text-primary">{{ $ra->time }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs mr-2 bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold" style="width:30px;height:30px;font-size:10px;">{{ substr($ra->student->nama_lengkap, 0, 1) }}</div>
                                        <span>{{ $ra->student->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td><span class="badge badge-light border">{{ $ra->classGroup->kelas_lengkap }}</span></td>
                                <td class="text-center pr-4">
                                    <span class="badge badge-{{ $ra->status_color }} badge-pill px-3 py-1 shadow-xs">{{ $ra->status_label }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-clipboard-list fa-3x text-light mb-3 d-block"></i>
                                    <span class="text-muted">Belum ada aktivitas presensi hari ini.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
        <!-- DAILY SUMMARY BOXES -->
        <div class="card shadow-sm border-0 rounded-20 mb-4 bg-gradient-blue text-white overflow-hidden position-relative p-4">
            <div class="position-relative" style="z-index: 1;">
                <h6 class="text-uppercase small font-weight-bold mb-3 opacity-8">Kehadiran Guru</h6>
                <div class="d-flex align-items-end mb-2">
                    <h2 class="font-weight-bold mb-0 mr-2">{{ $teacherAttendanceCount }}</h2>
                    <span class="opacity-70 mb-1">/ {{ $teachersCount }} hadir</span>
                </div>
                <div class="progress progress-sm bg-white-20 rounded-pill mb-2">
                    <div class="progress-bar bg-white" style="width: {{ $teachersCount > 0 ? ($teacherAttendanceCount/$teachersCount)*100 : 0 }}%"></div>
                </div>
                <small class="opacity-70">Log harian Guru & Tenaga Kependidikan</small>
            </div>
            <i class="fas fa-user-check position-absolute opacity-1" style="right: -10px; bottom: -10px; font-size: 80px;"></i>
        </div>

        <div class="card shadow-sm border-0 rounded-20 mb-4 bg-gradient-indigo text-white overflow-hidden position-relative p-4">
            <div class="position-relative" style="z-index: 1;">
                <h6 class="text-uppercase small font-weight-bold mb-3 opacity-8">Kehadiran Siswa</h6>
                <div class="d-flex align-items-end mb-2">
                    <h2 class="font-weight-bold mb-0 mr-2">{{ $studentAttendanceCount }}</h2>
                    <span class="opacity-70 mb-1">/ {{ $studentsCount }} hadir</span>
                </div>
                <div class="progress progress-sm bg-white-20 rounded-pill mb-2">
                    <div class="progress-bar bg-white" style="width: {{ $studentsCount > 0 ? ($studentAttendanceCount/$studentsCount)*100 : 0 }}%"></div>
                </div>
                <small class="opacity-70">Pantauan real-time melalui QR Scan</small>
            </div>
            <i class="fas fa-fingerprint position-absolute opacity-1" style="right: -10px; bottom: -10px; font-size: 80px;"></i>
        </div>

        <!-- QUICK INFO -->
        <div class="card shadow-sm border-0 rounded-20">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title font-weight-bold mb-0 text-dark">Informasi Sistem</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush rounded-20">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="fas fa-bookmark mr-2 text-primary"></i> Tahun Pelajaran</span>
                        <span class="badge badge-soft-primary px-3">{{ $academicYear->academic_year ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="fas fa-clock mr-2 text-success"></i> Semester</span>
                        <span class="font-weight-bold">{{ $academicYear->semester->semester_name ?? '-' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted"><i class="fas fa-globe mr-2 text-info"></i> Konten Web</span>
                        <span class="font-weight-bold text-primary">{{ $postsCount }} Post</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    /* CUSTOM DASHBOARD STYLES */
    .bg-gradient-premium { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important; }
    .bg-gradient-blue { background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%) !important; }
    .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
    .bg-white-20 { background: rgba(255,255,255,0.2); }
    .rounded-20 { border-radius: 20px !important; }
    .opacity-70 { opacity: 0.7; }
    .opacity-8 { opacity: 0.8; }
    .opacity-1 { opacity: 0.1; }

    .bg-shape-1 { position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -100px; right: -50px; }
    .bg-shape-2 { position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; bottom: -50px; left: 10%; }

    /* PREMIUM STAT CARDS */
    .premium-stat-card {
        padding: 25px;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .premium-stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    
    .premium-stat-card .inner h3 { font-size: 2rem; font-weight: 800; margin-bottom: 5px; color: #1e293b; }
    .premium-stat-card .inner p { color: #64748b; font-weight: 600; font-size: 0.9rem; margin-bottom: 15px; }
    
    .icon-circle {
        position: absolute; top: 20px; right: 20px;
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    
    .card-link { color: #94a3b8; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-decoration: none !important; }
    .card-link:hover { color: #4f46e5; }

    /* GLASS COLORS */
    .glass-blue { border-bottom: 4px solid #3b82f6; } .shadow-blue { background: #eff6ff; color: #3b82f6; }
    .glass-teal { border-bottom: 4px solid #10b981; } .shadow-teal { background: #ecfdf5; color: #10b981; }
    .glass-orange { border-bottom: 4px solid #f59e0b; } .shadow-orange { background: #fffbeb; color: #f59e0b; }
    .glass-red { border-bottom: 4px solid #ef4444; } .shadow-red { background: #fef2f2; color: #ef4444; }

    /* TABLE STYLES */
    #recentAttendanceTable tbody tr { transition: all 0.2s; }
    #recentAttendanceTable tbody tr:hover { background: #f8fafc; }
    .bg-light-soft { background: #f1f5f9; }
    .btn-soft-info { background: #e0f2fe; color: #0369a1; font-weight: 700; border: none; }
    .btn-soft-info:hover { background: #bae6fd; }

    .avatar-xs { font-size: 12px; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .badge-pill { border-radius: 50rem; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function () {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Gradient fill for chart
        let gradientSiswa = ctx.createLinearGradient(0, 0, 0, 300);
        gradientSiswa.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        gradientSiswa.addColorStop(1, 'rgba(79, 70, 229, 0)');

        let gradientGuru = ctx.createLinearGradient(0, 0, 0, 300);
        gradientGuru.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradientGuru.addColorStop(1, 'rgba(16, 185, 129, 0)');

        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($attendanceTrend['labels']) !!},
                datasets: [
                    {
                        label: 'Siswa',
                        backgroundColor: gradientSiswa,
                        borderColor: '#4f46e5',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointHoverRadius: 5,
                        data: {!! json_encode($attendanceTrend['students']) !!},
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Guru',
                        backgroundColor: gradientGuru,
                        borderColor: '#10b981',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointHoverRadius: 5,
                        data: {!! json_encode($attendanceTrend['teachers']) !!},
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true, font: { weight: 'bold' } }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 14 },
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#94a3b8' },
                        grid: { borderDash: [5, 5], color: '#e2e8f0' }
                    }
                }
            }
        });
    });
</script>
@endpush
