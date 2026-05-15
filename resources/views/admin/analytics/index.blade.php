@extends($layout)

@section('title', 'Dashboard Statistik Lanjutan')
@section('subtitle', 'Laporan')

@section('content')
<!-- TOP STATS CARDS -->
<div class="row animate__animated animate__fadeIn">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 premium-card bg-gradient-info text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase opacity-8 mb-2">Total Siswa Aktif</h6>
                        <h2 class="font-weight-bold mb-0">{{ number_format($stats['total_students']) }}</h2>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 premium-card bg-gradient-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase opacity-8 mb-2">Rata-rata Kehadiran</h6>
                        <h2 class="font-weight-bold mb-0">{{ $stats['avg_attendance'] }}%</h2>
                    </div>
                    <i class="fas fa-calendar-check fa-3x opacity-3"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 premium-card bg-gradient-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase opacity-8 mb-2">Rata-rata Nilai (GPA)</h6>
                        <h2 class="font-weight-bold mb-0">{{ $stats['avg_grade'] }}</h2>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- ATTENDANCE TREND CHART -->
    <div class="col-lg-8 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold text-dark mb-0">Tren Kehadiran Siswa</h5>
                    <form action="" id="year-filter-form">
                        <select name="academic_year_id" class="form-control form-control-sm rounded-pill" onchange="this.form.submit()">
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $selectedYearId == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- GRADE DISTRIBUTION CHART -->
    <div class="col-lg-4 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title font-weight-bold text-dark mb-0">Distribusi Nilai</h5>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" height="300"></canvas>
                <div id="grade-legend" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .premium-card { border-radius: 20px; overflow: hidden; }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important; }
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0069d9 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-3 { opacity: 0.3; }
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
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: res.labels,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: res.present,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Alpa',
                            data: res.absent,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });

        // Grade Distribution Chart
        $.get("{{ route('admin.analytics.grade-distribution') }}", {academic_year_id: '{{ $selectedYearId }}'}, function(res) {
            const ctx = document.getElementById('gradeChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: res.map(i => i.bracket),
                    datasets: [{
                        data: res.map(i => i.count),
                        backgroundColor: ['#28a745', '#007bff', '#ffc107', '#fd7e14', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
            
            // Build legend
            let legendHtml = '<div class="row text-center">';
            res.forEach((item, index) => {
                const colors = ['#28a745', '#007bff', '#ffc107', '#fd7e14', '#dc3545'];
                legendHtml += `
                    <div class="col-6 mb-2">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="badge" style="background-color: ${colors[index]}; width: 12px; height: 12px; border-radius: 50%; margin-right: 5px;"></span>
                            <span class="text-xs font-weight-bold text-muted">${item.bracket}: ${item.count}</span>
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
