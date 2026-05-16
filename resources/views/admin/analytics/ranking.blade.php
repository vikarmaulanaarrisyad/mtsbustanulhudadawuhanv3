@extends($layout)

@section('title', 'Ranking Siswa')
@section('subtitle', 'Analisis Akademik')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-primary overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-medal mr-2 animate__animated animate__fadeInLeft"></i> 
                            Ranking Prestasi Akademik
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Analisis mendalam terhadap capaian nilai rata-rata dan tingkat kehadiran siswa per rombongan belajar.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-award fa-8x opacity-2 shadow-icon"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS -->
<div class="row mb-4 animate__animated animate__fadeInUp">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #4e73df !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Siswa Terpilih</p>
                        <h2 class="font-weight-bold mb-0 text-primary">{{ $stats['total_students'] }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-user-graduate text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #36b9cc !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Rata-rata Kelas</p>
                        <h2 class="font-weight-bold mb-0 text-info">{{ number_format($stats['average_class_score'], 1) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-info rounded-circle p-3">
                        <i class="fas fa-chart-line text-info fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-info" style="width: {{ min(($stats['average_class_score'] / 100) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #f6c23e !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Skor Tertinggi</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ number_format($stats['highest_score'], 1) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-trophy text-warning fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- LEFT COLUMN: FILTERS & EXPORT -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-filter mr-2 text-muted"></i> Konfigurasi Laporan
                </h5>
            </div>
            <div class="card-body pt-0">
                <form action="" method="GET">
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-control select2 custom-select-premium" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Rombongan Belajar</label>
                        <select name="class_group_id" class="form-control select2" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" {{ $class_group_id == $cg->id ? 'selected' : '' }}>{{ $cg->kelas_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2 btn-premium">
                        <i class="fas fa-sync-alt mr-2"></i> TAMPILKAN RANKING
                    </button>
                </form>
            </div>
        </div>

        @if(count($rankings))
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-success mb-0">
                    <i class="fas fa-file-export mr-2"></i> Ekspor Data
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.analytics.ranking.export', ['class_group_id' => $class_group_id, 'academic_year_id' => $academic_year_id]) }}" class="btn btn-outline-success btn-block rounded-lg font-weight-bold">
                            <i class="fas fa-file-excel mr-2"></i> EXCEL
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-danger btn-block rounded-lg font-weight-bold shadow-xs">
                            <i class="fas fa-file-pdf mr-2"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">
                            @if($class_group_id)
                                Hasil Peringkat Kelas
                            @else
                                Data Peringkat
                            @endif
                        </h4>
                        <p class="text-muted text-sm mb-0">Dihitung berdasarkan nilai rata-rata mata pelajaran.</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableRanking">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="70px" class="text-center py-3">Rank</th>
                                <th>Identitas Siswa</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center" width="120px">Rata-rata</th>
                                <th class="text-center" width="130px">Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rankings as $index => $r)
                                @php $pos = $index + 1; @endphp
                                <tr>
                                    <td class="text-center">
                                        @if($pos == 1)
                                            <div class="rank-medal gold animate__animated animate__bounceIn">1</div>
                                        @elseif($pos == 2)
                                            <div class="rank-medal silver">2</div>
                                        @elseif($pos == 3)
                                            <div class="rank-medal bronze">3</div>
                                        @else
                                            <span class="font-weight-bold text-muted">{{ $pos }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center py-1">
                                            <div class="avatar-sm mr-3 bg-soft-primary rounded-circle d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 40px; height: 40px; border: 2px solid #fff;">
                                                {{ substr($r['student']->nama_lengkap, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark" style="font-size: 1rem;">{{ $r['student']->nama_lengkap }}</div>
                                                <div class="text-xs text-muted">NIS: {{ $r['student']->nis ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="progress progress-xs w-75 bg-light mb-1">
                                                <div class="progress-bar bg-success" style="width: {{ $r['attendance_rate'] }}%"></div>
                                            </div>
                                            <small class="font-weight-bold text-success">{{ $r['attendance_rate'] }}%</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="mb-0 font-weight-bold text-primary">{{ $r['avg_grade'] }}</h5>
                                        <small class="text-xs text-muted">Total: {{ number_format($r['total_score']) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($r['avg_grade'] >= 85)
                                            <span class="badge badge-soft-success py-2 px-3 rounded-pill">Sangat Baik</span>
                                        @elseif($r['avg_grade'] >= 75)
                                            <span class="badge badge-soft-primary py-2 px-3 rounded-pill">Baik</span>
                                        @else
                                            <span class="badge badge-soft-warning py-2 px-3 rounded-pill">Cukup</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="opacity-50">
                                            <i class="fas fa-search-minus fa-4x mb-3 text-muted"></i>
                                            <h5 class="font-weight-bold text-muted">Data Tidak Ditemukan</h5>
                                            <p class="text-sm px-5">Pilih Rombongan Belajar dan Tahun Pelajaran untuk melihat analisis ranking prestasi siswa.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Themes & Effects */
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;
    }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Card Styling */
    .premium-card { border-radius: 15px; overflow: hidden; transition: all 0.3s ease; }
    .premium-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    .border-left-success-thick { border-left: 5px solid #28a745 !important; }

    /* Table Styling */
    #tableRanking { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #tableRanking tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #tableRanking tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
    }
    #tableRanking td { border: none; padding: 1.25rem 0.75rem; vertical-align: middle; }
    #tableRanking td:first-child { border-radius: 12px 0 0 12px; }
    #tableRanking td:last-child { border-radius: 0 12px 12px 0; }
    .bg-light-info { background: #f0f7f9; color: #507b8f; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; }

    /* Medals */
    .rank-medal {
        width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto; font-weight: 900; color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .gold { background: linear-gradient(135deg, #facc15 0%, #ca8a04 100%); }
    .silver { background: linear-gradient(135deg, #cbd5e1 0%, #64748b 100%); }
    .bronze { background: linear-gradient(135deg, #fb923c 0%, #c2410c 100%); }

    /* Soft UI Components */
    .icon-shape { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
    .bg-soft-warning { background: #fff8e1; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-primary { background: #e3f2fd; }
    .bg-soft-info { background: #e0f7fa; }
    .btn-premium { border-radius: 10px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .badge-soft-success { background-color: #dcfce7; color: #15803d; border: 1px solid #bcf0cc; }
    .badge-soft-primary { background-color: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
    .badge-soft-warning { background-color: #fef9c3; color: #a16207; border: 1px solid #fef08a; }
</style>
@endsection

@include('includes.select2')

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    $(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endpush
