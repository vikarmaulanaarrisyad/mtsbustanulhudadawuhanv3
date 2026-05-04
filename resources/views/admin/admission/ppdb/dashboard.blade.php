@extends($layout)

@section('title', 'Dashboard Analitik PPDB')

@section('content')
<!-- PREMIUM HEADER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo-dark overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-chart-pie mr-2 animate__animated animate__fadeInLeft"></i> 
                            Analitik PPDB Madrasah
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Wawasan mendalam mengenai tren pendaftaran, distribusi demografis, dan efektivitas jalur penerimaan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-rocket fa-8x opacity-1 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <div class="bg-shape-1"></div>
            <div class="bg-shape-2"></div>
        </div>
    </div>
</div>

{{-- STATS CARDS --}}
<div class="row animate__animated animate__fadeInUp">
    <div class="col-lg-3 col-6">
        <div class="ppdb-glass-card bg-info-grad">
            <div class="inner">
                <h3 class="font-weight-bold mb-0 text-white">{{ $stats['total'] }}</h3>
                <p class="mb-0 text-white opacity-8">Total Pendaftar</p>
            </div>
            <div class="icon-box"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="ppdb-glass-card bg-warning-grad">
            <div class="inner">
                <h3 class="font-weight-bold mb-0 text-white">{{ $stats['pending'] }}</h3>
                <p class="mb-0 text-white opacity-8">Pending Verifikasi</p>
            </div>
            <div class="icon-box"><i class="fas fa-history"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="ppdb-glass-card bg-success-grad">
            <div class="inner">
                <h3 class="font-weight-bold mb-0 text-white">{{ $stats['accepted'] }}</h3>
                <p class="mb-0 text-white opacity-8">Siswa Diterima</p>
            </div>
            <div class="icon-box"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="ppdb-glass-card bg-primary-grad">
            <div class="inner">
                <h3 class="font-weight-bold mb-0 text-white">{{ $stats['moved'] }}</h3>
                <p class="mb-0 text-white opacity-8">Induk Siswa</p>
            </div>
            <div class="icon-box"><i class="fas fa-database"></i></div>
        </div>
    </div>
</div>

<div class="row">
    {{-- TREND CHART --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-chart-line mr-2 text-indigo"></i> Tren Pendaftaran (10 Hari Terakhir)
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="chart" style="height: 280px;">
                    <canvas id="registrationTrend"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS DISTRIBUTION --}}
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-chart-pie mr-2 text-orange"></i> Distribusi Status
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="chart d-flex align-items-center justify-content-center" style="height: 280px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- TOP SCHOOLS --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-school mr-2 text-primary"></i> Top 5 Asal Sekolah
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light-indigo text-uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Asal Sekolah</th>
                                <th class="text-center py-3">Jumlah</th>
                                <th width="35%" class="py-3 pr-4">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($originSchools as $school)
                            @php $percent = ($stats['total'] > 0) ? ($school->total / $stats['total']) * 100 : 0; @endphp
                            <tr>
                                <td class="px-4 font-weight-bold text-dark">{{ $school->asal_sekolah ?? 'Tidak Diketahui' }}</td>
                                <td class="text-center"><span class="badge badge-soft-indigo px-3 py-2 rounded-pill font-weight-bold">{{ $school->total }}</span></td>
                                <td class="pr-4">
                                    <div class="progress progress-xs shadow-none bg-light" style="height: 6px; border-radius: 10px;">
                                        <div class="progress-bar bg-gradient-indigo" style="width: {{ $percent }}%; border-radius: 10px;"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if(count($originSchools) == 0)
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada data sekolah</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- GENDER --}}
    <div class="col-md-6">
        <div class="card shadow-sm border-0 rounded-20 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title font-weight-bold mb-0 text-dark">
                    <i class="fas fa-venus-mars mr-2 text-danger"></i> Perbandingan Gender
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center">
                        <div style="height: 180px; width: 180px; margin: 0 auto;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light-soft rounded-20 shadow-sm border">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div><i class="fas fa-male text-primary mr-2 fa-lg"></i> <span class="font-weight-bold text-muted text-sm">Laki-laki</span></div>
                                <span class="h5 font-weight-bold text-primary mb-0">{{ $genderData[0] }}</span>
                            </div>
                            <div class="progress progress-xs bg-light mb-4" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: {{ ($genderData[0] + $genderData[1] > 0) ? ($genderData[0] / ($genderData[0] + $genderData[1])) * 100 : 0 }}%"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div><i class="fas fa-female text-danger mr-2 fa-lg"></i> <span class="font-weight-bold text-muted text-sm">Perempuan</span></div>
                                <span class="h5 font-weight-bold text-danger mb-0">{{ $genderData[1] }}</span>
                            </div>
                            <div class="progress progress-xs bg-light" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: {{ ($genderData[0] + $genderData[1] > 0) ? ($genderData[1] / ($genderData[0] + $genderData[1])) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* PREMIUM DASHBOARD STYLES */
    .bg-gradient-indigo-dark { background: linear-gradient(135deg, #4338ca 0%, #1e1b4b 100%) !important; }
    .bg-gradient-indigo { background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%) !important; }
    .bg-shape-1 { position: absolute; width: 300px; height: 300px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; top: -100px; right: -50px; }
    .bg-shape-2 { position: absolute; width: 150px; height: 150px; background: rgba(99, 102, 241, 0.05); border-radius: 50%; bottom: -30px; left: 10%; }
    
    .ppdb-glass-card {
        padding: 25px; border-radius: 20px; position: relative; overflow: hidden; margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: all 0.3s ease;
    }
    .ppdb-glass-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
    .ppdb-glass-card .inner { position: relative; z-index: 2; }
    .ppdb-glass-card .icon-box { 
        position: absolute; right: -15px; bottom: -15px; font-size: 70px; color: rgba(255,255,255,0.2); z-index: 1;
    }

    .bg-info-grad { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .bg-warning-grad { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .bg-success-grad { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .bg-primary-grad { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); }

    .rounded-20 { border-radius: 20px; }
    .bg-light-indigo { background: #f8fafc; color: #64748b; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px; }
    .badge-soft-indigo { background: #eef2ff; color: #4338ca; }
    .bg-light-soft { background: #fdfdfd; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        // 1. Trend Chart
        var trendCtx = document.getElementById('registrationTrend').getContext('2d');
        let trendGrad = trendCtx.createLinearGradient(0, 0, 0, 300);
        trendGrad.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        trendGrad.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendLabels) !!},
                datasets: [{
                    label: 'Pendaftar Baru',
                    borderColor: '#4f46e5',
                    backgroundColor: trendGrad,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    data: {!! json_encode($trendData) !!},
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                    y: { grid: { borderDash: [5,5] }, beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        // 2. Status Chart
        var statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusLabels) !!},
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: ['#f59e0b', '#06b6d4', '#10b981', '#334155', '#94a3b8'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                cutout: '75%',
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            boxWidth: 10, 
                            padding: 20, 
                            usePointStyle: true,
                            font: { size: 11, weight: 'bold' } 
                        } 
                    } 
                }
            }
        });

        // 3. Gender Chart
        var genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: {!! json_encode($genderData) !!},
                    backgroundColor: ['#3b82f6', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endpush
