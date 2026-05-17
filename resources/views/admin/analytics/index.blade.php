@extends($layout)

@section('title', 'Dashboard Statistik Lanjutan')
@section('subtitle', 'Laporan')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- DASHBOARD HEADER -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom animate__animated animate__fadeIn" style="font-family: 'Outfit', sans-serif;">
    <div>
        <h2 class="font-weight-black text-dark mb-1" style="font-size: 2.2rem; font-weight: 800; letter-spacing: -0.5px;">
            <i class="fas fa-chart-line text-success mr-2"></i> Laporan & Analitik Lanjutan
        </h2>
        <p class="text-muted mb-0" style="font-size: 1.05rem;">Pantau tren kehadiran, prestasi nilai, dan performa akademis madrasah secara real-time.</p>
    </div>
    <div class="mt-3 mt-md-0 d-flex align-items-center">
        <span class="mr-3 text-muted font-weight-bold" style="font-size: 0.9rem;"><i class="fas fa-calendar-alt mr-1 text-success"></i> Filter Tahun Ajaran:</span>
        <form action="" id="year-filter-form" class="m-0">
            <select name="academic_year_id" class="form-control font-weight-bold text-success border-success rounded-pill px-4 shadow-sm" style="height: 42px; border-width: 2px; cursor: pointer; transition: all 0.3s;" onchange="this.form.submit()">
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<!-- TOP STATS CARDS -->
<div class="row animate__animated animate__fadeIn" style="font-family: 'Outfit', sans-serif;">
    <!-- CARD 1 -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 premium-card-stat bg-gradient-indigo text-white h-100">
            <div class="card-glow"></div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                    <div>
                        <span class="badge px-3 py-1 rounded-pill mb-2 text-uppercase font-weight-bold" style="background: rgba(255, 255, 255, 0.15); font-size: 0.7rem; letter-spacing: 1px;">DATABASE SISWA</span>
                        <h6 class="text-uppercase opacity-8 mb-2 font-weight-bold" style="font-size: 0.8rem;">Total Siswa Aktif</h6>
                        <h2 class="font-weight-black mb-0 tracking-tight" style="font-size: 2.8rem; font-weight: 900;">{{ number_format($stats['total_students']) }}</h2>
                        <span class="text-xs opacity-7 font-weight-bold mt-2 d-inline-block"><i class="fas fa-info-circle mr-1"></i> Terdaftar aktif di sistem</span>
                    </div>
                    <div class="icon-box-glowing bg-white-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CARD 2 -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 premium-card-stat bg-gradient-emerald text-white h-100">
            <div class="card-glow"></div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                    <div>
                        <span class="badge px-3 py-1 rounded-pill mb-2 text-uppercase font-weight-bold" style="background: rgba(255, 255, 255, 0.15); font-size: 0.7rem; letter-spacing: 1px;">PRESENSI KELAS</span>
                        <h6 class="text-uppercase opacity-8 mb-2 font-weight-bold" style="font-size: 0.8rem;">Rata-rata Kehadiran</h6>
                        <h2 class="font-weight-black mb-0 tracking-tight" style="font-size: 2.8rem; font-weight: 900;">{{ $stats['avg_attendance'] }}%</h2>
                        <span class="text-xs opacity-7 font-weight-bold mt-2 d-inline-block"><i class="fas fa-check-circle mr-1"></i> Akumulasi kehadiran semester ini</span>
                    </div>
                    <div class="icon-box-glowing bg-white-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CARD 3 -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 premium-card-stat bg-gradient-amber text-white h-100">
            <div class="card-glow"></div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
                    <div>
                        <span class="badge px-3 py-1 rounded-pill mb-2 text-uppercase font-weight-bold" style="background: rgba(255, 255, 255, 0.15); font-size: 0.7rem; letter-spacing: 1px;">PRESTASI AKADEMIK</span>
                        <h6 class="text-uppercase opacity-8 mb-2 font-weight-bold" style="font-size: 0.8rem;">Rata-rata Nilai (GPA)</h6>
                        <h2 class="font-weight-black mb-0 tracking-tight" style="font-size: 2.8rem; font-weight: 900;">{{ $stats['avg_grade'] }}</h2>
                        <span class="text-xs opacity-7 font-weight-bold mt-2 d-inline-block"><i class="fas fa-star mr-1"></i> Rata-rata nilai rapor madrasah</span>
                    </div>
                    <div class="icon-box-glowing bg-white-15 rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-medal fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2" style="font-family: 'Outfit', sans-serif;">
    <!-- ATTENDANCE TREND CHART -->
    <div class="col-lg-8 animate__animated animate__fadeInLeft mb-4">
        <div class="card shadow-sm border-0 premium-card-chart h-100">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <div>
                    <h5 class="card-title font-weight-black text-dark mb-1" style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.3px;">
                        <i class="fas fa-chart-area text-success mr-2"></i> Tren Kehadiran Siswa
                    </h5>
                    <p class="text-muted mb-0 small">Analisis fluktuasi absensi bulanan untuk tahun ajaran terpilih.</p>
                </div>
                <div class="chart-badges mt-3 mt-sm-0">
                    <span class="badge px-3 py-2 rounded-pill font-weight-bold" style="background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 0.75rem;"><i class="fas fa-circle mr-1"></i> Hadir</span>
                    <span class="badge px-3 py-2 rounded-pill font-weight-bold mx-1" style="background: rgba(255, 193, 7, 0.1); color: #ffc107; font-size: 0.75rem;"><i class="fas fa-circle mr-1"></i> Izin/Sakit</span>
                    <span class="badge px-3 py-2 rounded-pill font-weight-bold" style="background: rgba(244, 63, 94, 0.1); color: #f43f5e; font-size: 0.75rem;"><i class="fas fa-circle mr-1"></i> Alpa</span>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div style="position: relative; height: 350px;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- GRADE DISTRIBUTION CHART -->
    <div class="col-lg-4 animate__animated animate__fadeInRight mb-4">
        <div class="card shadow-sm border-0 premium-card-chart h-100">
            <div class="card-header bg-white border-0 py-4 px-4">
                <h5 class="card-title font-weight-black text-dark mb-1" style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.3px;">
                    <i class="fas fa-chart-pie text-success mr-2"></i> Distribusi Nilai Akademik
                </h5>
                <p class="text-muted mb-0 small">Peta sebaran nilai ujian / tugas siswa.</p>
            </div>
            <div class="card-body px-4 pb-4 d-flex flex-column justify-content-between">
                <div style="position: relative; height: 260px;" class="d-flex align-items-center justify-content-center">
                    <div class="position-relative" style="width: 240px; height: 240px;">
                        <canvas id="gradeChart"></canvas>
                        <div class="position-absolute d-flex flex-column align-items-center justify-content-center" style="top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; z-index: 10;">
                            <span class="text-uppercase tracking-wider font-weight-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1.5px;">Distribusi</span>
                            <span class="font-weight-black text-dark" style="font-size: 1.8rem; font-weight: 900;" id="totalGradesCount">{{ $stats['total_students'] }}</span>
                            <span class="text-xs text-muted font-weight-bold">Siswa</span>
                        </div>
                    </div>
                </div>
                <div id="grade-legend" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Cards Styling */
    .premium-card-stat {
        border-radius: 24px !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        position: relative;
    }
    .premium-card-stat:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px rgba(0, 0, 0, 0.15) !important;
    }
    .premium-card-chart {
        border-radius: 24px !important;
        border: 1px solid rgba(0, 0, 0, 0.05) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.3s ease;
    }
    .premium-card-chart:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06) !important;
    }

    /* Glowing circle under card */
    .card-glow {
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        top: -40px;
        right: -40px;
        filter: blur(20px);
        transition: all 0.5s ease;
    }
    .premium-card-stat:hover .card-glow {
        transform: scale(1.4);
        background: rgba(255, 255, 255, 0.22);
    }

    /* Glowing Icon Box */
    .icon-box-glowing {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        transition: all 0.4s ease;
    }
    .premium-card-stat:hover .icon-box-glowing {
        transform: rotate(12deg) scale(1.1);
        background: rgba(255, 255, 255, 0.25);
    }

    /* Gradients */
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.25);
    }
    .bg-gradient-emerald {
        background: linear-gradient(135deg, #0ea5e9 0%, #10b981 100%) !important;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.25);
    }
    .bg-gradient-amber {
        background: linear-gradient(135deg, #f43f5e 0%, #f59e0b 100%) !important;
        box-shadow: 0 10px 25px rgba(244, 63, 94, 0.25);
    }

    /* Glass utility overrides */
    .bg-white-15 { background: rgba(255, 255, 255, 0.15) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-7 { opacity: 0.7; }
    .font-weight-black { font-weight: 900 !important; }
    
    /* Interactive select drop */
    #year-filter-form select:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25) !important;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        // Attendance Chart
        $.get("{{ route('admin.analytics.attendance-trends') }}", {academic_year_id: '{{ $selectedYearId }}'}, function(res) {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            // Create linear gradients for fill
            const presentGradient = ctx.createLinearGradient(0, 0, 0, 300);
            presentGradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
            presentGradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            const permitGradient = ctx.createLinearGradient(0, 0, 0, 300);
            permitGradient.addColorStop(0, 'rgba(255, 193, 7, 0.25)');
            permitGradient.addColorStop(1, 'rgba(255, 193, 7, 0.0)');

            const absentGradient = ctx.createLinearGradient(0, 0, 0, 300);
            absentGradient.addColorStop(0, 'rgba(244, 63, 94, 0.25)');
            absentGradient.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: res.labels,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: res.present,
                            borderColor: '#10b981',
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            backgroundColor: presentGradient,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Izin/Sakit',
                            data: res.permit,
                            borderColor: '#ffc107',
                            borderWidth: 3,
                            pointBackgroundColor: '#ffc107',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            backgroundColor: permitGradient,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Alpa',
                            data: res.absent,
                            borderColor: '#f43f5e',
                            borderWidth: 3,
                            pointBackgroundColor: '#f43f5e',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            backgroundColor: absentGradient,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { size: 14, weight: 'bold', family: 'Outfit, sans-serif' },
                            bodyFont: { size: 13, family: 'Outfit, sans-serif' },
                            padding: 12,
                            cornerRadius: 12,
                            boxPadding: 6,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(226, 232, 240, 0.7)',
                                borderDash: [5, 5],
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Outfit, sans-serif', size: 11, weight: '500' }
                            }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: {
                                color: '#64748b',
                                font: { family: 'Outfit, sans-serif', size: 11, weight: '500' }
                            }
                        }
                    }
                }
            });
        });

        // Grade Distribution Chart
        $.get("{{ route('admin.analytics.grade-distribution') }}", {academic_year_id: '{{ $selectedYearId }}'}, function(res) {
            const ctx = document.getElementById('gradeChart').getContext('2d');
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f43f5e'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: res.map(i => i.bracket),
                    datasets: [{
                        data: res.map(i => i.count),
                        backgroundColor: colors,
                        borderWidth: 4,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleFont: { size: 13, weight: 'bold', family: 'Outfit, sans-serif' },
                            bodyFont: { size: 13, family: 'Outfit, sans-serif' },
                            padding: 12,
                            cornerRadius: 12,
                            boxPadding: 6,
                            usePointStyle: true
                        }
                    }
                }
            });
            
            // Build modern custom interactive legend
            let legendHtml = '<div class="row">';
            let totalSum = 0;
            res.forEach(item => totalSum += item.count);
            $('#totalGradesCount').text(totalSum.toLocaleString());

            res.forEach((item, index) => {
                const percent = totalSum > 0 ? Math.round((item.count / totalSum) * 100) : 0;
                legendHtml += `
                    <div class="col-6 mb-3 animate__animated animate__fadeInUp" style="animation-delay: ${index * 60}ms">
                        <div class="p-3 border bg-light h-100 d-flex flex-column justify-content-between legend-item-card" style="border-radius: 16px; border-color: rgba(0,0,0,0.06) !important; transition: all 0.3s ease;">
                            <div class="d-flex align-items-center mb-1">
                                <span style="background-color: ${colors[index]}; width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px;"></span>
                                <span class="text-dark small font-weight-bold">${item.bracket}</span>
                            </div>
                            <div class="d-flex align-items-baseline justify-content-between mt-2">
                                <span class="font-weight-black text-dark" style="font-size: 1.3rem; font-weight: 800;">${item.count} <span class="text-muted" style="font-size: 0.75rem; font-weight: 500;">Siswa</span></span>
                                <span class="badge badge-pill font-weight-bold px-2 py-1" style="background: rgba(16, 185, 129, 0.08); color: #10b981; font-size: 0.75rem;">${percent}%</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            legendHtml += '</div>';
            $('#grade-legend').html(legendHtml);
        });
    });
</script>
@endpush
