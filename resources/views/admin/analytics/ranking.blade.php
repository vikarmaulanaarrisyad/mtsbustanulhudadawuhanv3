@extends($layout)

@section('title', 'Ranking Siswa')
@section('subtitle', 'Analisis Akademik')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- PREMIUM HEADER BANNER (Penempatan Rombel style, upgraded) -->
<div class="row" style="font-family: 'Outfit', sans-serif;">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative animate__animated animate__fadeIn" style="border-radius: 20px;">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-black mb-1" style="font-weight: 800; font-size: 2.2rem; letter-spacing: -0.5px;">
                            <i class="fas fa-trophy mr-2 animate__animated animate__bounceIn"></i> 
                            Ranking Prestasi Akademik
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light" style="font-size: 1.1rem;">
                            Analisis mendalam terhadap capaian nilai rata-rata dan tingkat kehadiran siswa per rombongan belajar.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block position-relative" style="z-index: 3;">
                        <i class="fas fa-award fa-8x text-white opacity-2 shadow-icon floating-award"></i>
                    </div>
                </div>
            </div>
            <!-- Decorative Background Glow & Circles -->
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATISTICS WIDGETS (Penempatan Rombel style, upgraded) -->
<div class="row mb-4 animate__animated animate__fadeInUp" style="font-family: 'Outfit', sans-serif;">
    <!-- WIDGET 1 -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm info-card-premium overflow-hidden h-100" style="border-left: 5px solid #6366f1 !important; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-uppercase text-muted tracking-wider mb-2">Siswa Terpilih</p>
                        <h2 class="font-weight-black mb-0 text-indigo" style="font-size: 2.2rem; font-weight: 900;">{{ $stats['total_students'] }}</h2>
                    </div>
                    <div class="icon-shape-premium bg-soft-indigo rounded-circle">
                        <i class="fas fa-user-graduate text-indigo fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar bg-gradient-indigo" style="width: 100%; border-radius: 3px;"></div>
                </div>
                <small class="text-xs text-muted font-weight-bold mt-2 d-inline-block">Total siswa dalam kelas aktif</small>
            </div>
        </div>
    </div>
    <!-- WIDGET 2 -->
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm info-card-premium overflow-hidden h-100" style="border-left: 5px solid #10b981 !important; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-uppercase text-muted tracking-wider mb-2">Rata-rata Kelas</p>
                        <h2 class="font-weight-black mb-0 text-emerald" style="font-size: 2.2rem; font-weight: 900;">{{ number_format($stats['average_class_score'], 1) }}</h2>
                    </div>
                    <div class="icon-shape-premium bg-soft-emerald rounded-circle">
                        <i class="fas fa-chart-line text-emerald fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar bg-emerald" style="width: {{ min(($stats['average_class_score'] / 100) * 100, 100) }}%; border-radius: 3px;"></div>
                </div>
                <small class="text-xs text-muted font-weight-bold mt-2 d-inline-block">Akumulasi prestasi belajar kelas</small>
            </div>
        </div>
    </div>
    <!-- WIDGET 3 -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card-premium overflow-hidden h-100" style="border-left: 5px solid #f59e0b !important; border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-uppercase text-muted tracking-wider mb-2">Skor Tertinggi</p>
                        <h2 class="font-weight-black mb-0 text-warning" style="font-size: 2.2rem; font-weight: 900;">{{ number_format($stats['highest_score'], 1) }}</h2>
                    </div>
                    <div class="icon-shape-premium bg-soft-warning rounded-circle">
                        <i class="fas fa-trophy text-warning fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light" style="height: 6px; border-radius: 3px;">
                    <div class="progress-bar bg-warning" style="width: {{ min(($stats['highest_score'] / 100) * 100, 100) }}%; border-radius: 3px;"></div>
                </div>
                <small class="text-xs text-muted font-weight-bold mt-2 d-inline-block">Peringkat prestasi puncak (Rank 1)</small>
            </div>
        </div>
    </div>
</div>

<div class="row" style="font-family: 'Outfit', sans-serif;">
    <!-- LEFT COLUMN: FILTERS & EXPORT (Penempatan Rombel style, upgraded) -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft mb-4">
        <!-- STEP 1: CONFIGURATION -->
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-4 border-bottom-0">
                <h5 class="card-title font-weight-black text-dark mb-0" style="font-weight: 800; font-size: 1.15rem;">
                    <span class="step-badge mr-2">1</span> Konfigurasi Laporan
                </h5>
            </div>
            <div class="card-body pt-0">
                <form action="" method="GET">
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-2">Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-control select2 custom-select-premium" required>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-uppercase text-muted mb-2">Rombongan Belajar</label>
                        <select name="class_group_id" class="form-control select2" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" {{ $class_group_id == $cg->id ? 'selected' : '' }}>{{ $cg->kelas_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-indigo btn-block shadow-sm font-weight-bold py-3 btn-premium">
                        <i class="fas fa-sync-alt mr-2"></i> TAMPILKAN RANKING
                    </button>
                </form>
            </div>
        </div>

        @if(count($rankings))
        <!-- STEP 2: EXPORT ACTIONS -->
        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-header bg-white py-4 border-bottom-0">
                <h5 class="card-title font-weight-black text-success mb-0" style="font-weight: 800; font-size: 1.15rem;">
                    <span class="step-badge bg-success mr-2">2</span> Ekspor Data
                </h5>
            </div>
            <div class="card-body pt-0">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.analytics.ranking.export', ['class_group_id' => $class_group_id, 'academic_year_id' => $academic_year_id]) }}" class="btn btn-outline-success btn-block rounded-lg font-weight-bold py-2 shadow-xs" style="border-radius: 10px; border-width: 2px;">
                            <i class="fas fa-file-excel mr-2 text-lg"></i> EXCEL
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-danger btn-block rounded-lg font-weight-bold py-2 shadow-xs" style="border-radius: 10px; border-width: 2px;">
                            <i class="fas fa-file-pdf mr-2 text-lg"></i> PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- MAIN DATA TABLE (Penempatan Rombel style, upgraded) -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight mb-4">
        <div class="card shadow-sm border-0 premium-card h-100">
            <div class="card-header bg-white py-4 px-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <div>
                    <h4 class="mb-1 font-weight-black text-dark" style="font-weight: 800; font-size: 1.35rem; letter-spacing: -0.3px;">
                        @if($class_group_id)
                            <i class="fas fa-list-ol text-indigo mr-2"></i> Hasil Peringkat Kelas
                        @else
                            <i class="fas fa-award text-indigo mr-2"></i> Data Peringkat Siswa
                        @endif
                    </h4>
                    <p class="text-muted text-sm mb-0">Dihitung berdasarkan nilai rata-rata seluruh mata pelajaran.</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableRanking" style="width: 100%;">
                        <thead class="bg-light-info text-uppercase text-xs font-weight-bold">
                            <tr>
                                <th width="80px" class="text-center py-3">Rank</th>
                                <th>Identitas Siswa</th>
                                <th class="text-center" width="130px">Kehadiran</th>
                                <th class="text-center" width="130px">Rata-rata</th>
                                <th class="text-center" width="140px">Predikat</th>
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
                                            <div class="rank-medal silver animate__animated animate__bounceIn" style="animation-delay: 50ms">2</div>
                                        @elseif($pos == 3)
                                            <div class="rank-medal bronze animate__animated animate__bounceIn" style="animation-delay: 100ms">3</div>
                                        @else
                                            <span class="font-weight-black text-muted" style="font-size: 1.1rem;">{{ $pos }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center py-1">
                                            <div class="avatar-sm mr-3 bg-soft-indigo rounded-circle d-flex align-items-center justify-content-center text-indigo shadow-sm font-weight-bold" style="width: 44px; height: 44px; border: 2.5px solid #fff; font-size: 1.1rem;">
                                                {{ substr($r['student']->nama_lengkap, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-black text-dark" style="font-size: 1.05rem; font-weight: 700;">{{ $r['student']->nama_lengkap }}</div>
                                                <div class="text-xs text-muted font-weight-bold">NIS: {{ $r['student']->nis ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center px-2">
                                            <div class="progress progress-xs w-100 bg-light mb-2" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-emerald" style="width: {{ $r['attendance_rate'] }}%; border-radius: 3px;"></div>
                                            </div>
                                            <small class="font-weight-bold text-emerald" style="font-size: 0.8rem;">{{ $r['attendance_rate'] }}%</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="mb-0 font-weight-black text-indigo" style="font-weight: 800; font-size: 1.25rem;">{{ $r['avg_grade'] }}</h5>
                                        <small class="text-xs text-muted font-weight-bold">Total: {{ number_format($r['total_score']) }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($r['avg_grade'] >= 85)
                                            <span class="badge badge-soft-success py-2 px-3 rounded-pill font-weight-bold" style="font-size: 0.75rem;">Sangat Baik</span>
                                        @elseif($r['avg_grade'] >= 75)
                                            <span class="badge badge-soft-primary py-2 px-3 rounded-pill font-weight-bold" style="font-size: 0.75rem;">Baik</span>
                                        @else
                                            <span class="badge badge-soft-warning py-2 px-3 rounded-pill font-weight-bold" style="font-size: 0.75rem;">Cukup</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="opacity-50 my-4">
                                            <i class="fas fa-search-minus fa-4x mb-3 text-muted"></i>
                                            <h5 class="font-weight-bold text-muted" style="font-size: 1.15rem;">Data Tidak Ditemukan</h5>
                                            <p class="text-sm px-5 text-muted">Silakan pilih Rombongan Belajar dan Tahun Pelajaran untuk melihat analisis ranking prestasi siswa.</p>
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
    /* Premium Themes & Effects (Aligning with placements UI) */
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
    }
    .opacity-8 { opacity: 0.8; }
    .opacity-2 { opacity: 0.2; }
    .shadow-icon { filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.25)); }
    
    /* Decorative Background Shapes */
    .bg-circle-1, .bg-circle-2 {
        position: absolute; background: rgba(255,255,255,0.08); border-radius: 50%; z-index: 1;
    }
    .bg-circle-1 { width: 320px; height: 320px; top: -110px; right: -60px; }
    .bg-circle-2 { width: 160px; height: 160px; bottom: -60px; left: 8%; }

    /* Card Styling */
    .premium-card {
        border-radius: 20px !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.06) !important;
    }
    .border-left-success-thick { border-left: 5px solid #10b981 !important; }

    /* Numbered Steps Badge (Penempatan Rombel style) */
    .step-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 50%;
        background: #6366f1; color: #fff; font-size: 13px; font-weight: 800;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
    }
    
    /* Table Styling (Exact Penempatan Rombel Spacious Layout) */
    #tableRanking { border-collapse: separate; border-spacing: 0 12px; padding: 0 15px; }
    #tableRanking tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.015); 
        transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
        border-radius: 16px;
    }
    #tableRanking tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.07); 
        transform: scale(1.006) translateY(-2px);
    }
    #tableRanking td { border: none; padding: 1.35rem 0.75rem; vertical-align: middle; }
    #tableRanking td:first-child { border-radius: 16px 0 0 16px; }
    #tableRanking td:last-child { border-radius: 0 16px 16px 0; }
    .bg-light-info { background: #f4f6fc; color: #4f46e5; font-size: 0.75rem; font-weight: 800; letter-spacing: 1.2px; }

    /* Medals */
    .rank-medal {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto; font-weight: 900; color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 1rem;
    }
    .gold { background: linear-gradient(135deg, #facc15 0%, #ca8a04 100%); }
    .silver { background: linear-gradient(135deg, #cbd5e1 0%, #64748b 100%); }
    .bronze { background: linear-gradient(135deg, #fb923c 0%, #c2410c 100%); }

    /* Soft UI Components */
    .info-card-premium {
        border: 1px solid rgba(0,0,0,0.04) !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.02) !important;
        transition: all 0.3s ease;
    }
    .info-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.05) !important;
    }
    .icon-shape-premium {
        width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .info-card-premium:hover .icon-shape-premium {
        transform: scale(1.1);
    }
    .bg-soft-warning { background: #fffbeb; }
    .bg-soft-emerald { background: #ecfdf5; }
    .bg-soft-indigo { background: #e0e7ff; }
    .btn-premium { border-radius: 12px; letter-spacing: 0.5px; transition: all 0.3s ease; }
    .btn-premium:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(99, 102, 241, 0.2); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    .btn-indigo {
        background-color: #6366f1;
        border-color: #6366f1;
        color: #fff;
    }
    .btn-indigo:hover {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: #fff;
    }

    .text-indigo { color: #6366f1 !important; }
    .text-emerald { color: #10b981 !important; }
    
    .bg-gradient-indigo {
        background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%) !important;
    }
    .bg-emerald {
        background-color: #10b981 !important;
    }

    .badge-soft-success { background-color: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-soft-primary { background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .badge-soft-warning { background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

    /* floating effect */
    .floating-award {
        animation: float 4s ease-in-out infinite;
    }
    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(3deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
</style>
@endsection

@include('includes.select2')

@push('scripts')
<script>
    $(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endpush
