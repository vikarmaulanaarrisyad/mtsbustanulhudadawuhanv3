@extends($layout)

@section('title', 'Laporan Keuangan SPP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-info overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-chart-bar mr-2"></i> Laporan Keuangan SPP
                        </h2>
                        <p class="mb-0 opacity-8 text-lg">Rekapitulasi pendapatan iuran bulanan siswa secara periodik.</p>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-body p-4">
                <h6 class="text-xs font-weight-bold text-muted uppercase mb-3">Filter Laporan</h6>
                <form action="{{ route('admin.spp.report') }}" method="GET">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold">Tahun</label>
                        <select name="year" class="form-control rounded-pill border-2" onchange="this.form.submit()">
                            @for($y=date('Y'); $y>=date('Y')-5; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
                <hr>
                <div class="text-center py-4">
                    <h5 class="text-muted mb-1">Total Pendapatan {{ $year }}</h5>
                    <h2 class="font-weight-bold text-info">Rp {{ number_format(array_sum($chartData), 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 premium-card h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="m-0 font-weight-bold text-info">Grafik Pendapatan Bulanan</h6>
            </div>
            <div class="card-body">
                <canvas id="sppChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-info { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }
    .bg-circle-1 { position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -100px; right: -50px; z-index: 0; }
    .premium-card { border-radius: 20px; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('sppChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData),
                backgroundColor: 'rgba(54, 185, 204, 0.2)',
                borderColor: 'rgba(54, 185, 204, 1)',
                borderWidth: 2,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
