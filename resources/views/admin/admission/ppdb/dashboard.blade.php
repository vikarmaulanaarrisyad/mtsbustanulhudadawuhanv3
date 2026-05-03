@extends($layout)

@section('title', 'Dashboard Analitik PPDB')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item"><a href="{{ route('ppdb.index') }}">PPDB</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    {{-- STATS CARDS --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Pendaftar</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p>Menunggu Verifikasi</p>
                </div>
                <div class="icon"><i class="fas fa-history"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['accepted'] }}</h3>
                    <p>Siswa Diterima</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $stats['moved'] }}</h3>
                    <p>Sudah Masuk Database</p>
                </div>
                <div class="icon"><i class="fas fa-database"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- TREND CHART --}}
        <div class="col-md-8">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title text-bold"><i class="fas fa-chart-line mr-1"></i> Tren Pendaftaran (10 Hari Terakhir)</h3>
                </x-slot>
                <div class="chart">
                    <canvas id="registrationTrend" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </x-card>
        </div>

        {{-- STATUS DISTRIBUTION --}}
        <div class="col-md-4">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title text-bold"><i class="fas fa-chart-pie mr-1"></i> Distribusi Status</h3>
                </x-slot>
                <div class="chart">
                    <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </x-card>
        </div>
    </div>

    <div class="row">
        {{-- TOP SCHOOLS --}}
        <div class="col-md-6">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title text-bold"><i class="fas fa-school mr-1"></i> Top 5 Asal Sekolah</h3>
                </x-slot>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Asal Sekolah</th>
                                <th class="text-center">Jumlah</th>
                                <th width="30%">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($originSchools as $school)
                            @php $percent = ($stats['total'] > 0) ? ($school->total / $stats['total']) * 100 : 0; @endphp
                            <tr>
                                <td class="font-weight-bold">{{ $school->asal_sekolah ?? 'Tidak Diketahui' }}</td>
                                <td class="text-center"><span class="badge badge-info">{{ $school->total }}</span></td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        {{-- GENDER --}}
        <div class="col-md-6">
            <x-card>
                <x-slot name="header">
                    <h3 class="card-title text-bold"><i class="fas fa-venus-mars mr-1"></i> Perbandingan Gender</h3>
                </x-slot>
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <canvas id="genderChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-male text-primary mr-2"></i> Laki-laki</span>
                                <span class="font-weight-bold">{{ $genderData[0] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-female text-danger mr-2"></i> Perempuan</span>
                                <span class="font-weight-bold">{{ $genderData[1] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('AdminLTE') }}/plugins/chart.js/Chart.min.js"></script>
<script>
    $(function () {
        // 1. Trend Chart
        var trendCtx = $('#registrationTrend').get(0).getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendLabels) !!},
                datasets: [{
                    label: 'Pendaftar Baru',
                    backgroundColor: 'rgba(60,141,188,0.1)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: 4,
                    pointBackgroundColor: '#3b8bba',
                    data: {!! json_encode($trendData) !!},
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false },
                scales: {
                    xAxes: [{ gridLines: { display: false } }],
                    yAxes: [{ gridLines: { color: '#f1f1f1' }, ticks: { beginAtZero: true, stepSize: 1 } }]
                }
            }
        });

        // 2. Status Chart
        var statusCtx = $('#statusChart').get(0).getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusLabels) !!},
                datasets: [{
                    data: {!! json_encode($statusData) !!},
                    backgroundColor: ['#f39c12', '#17a2b8', '#28a745', '#343a40', '#6c757d'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { position: 'bottom', labels: { boxWidth: 12 } }
            }
        });

        // 3. Gender Chart
        var genderCtx = $('#genderChart').get(0).getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: {!! json_encode($genderData) !!},
                    backgroundColor: ['#007bff', '#dc3545'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                legend: { display: false }
            }
        });
    });
</script>
@endpush
