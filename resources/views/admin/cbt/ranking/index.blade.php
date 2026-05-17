@extends('layouts.app')
@section('title', 'Peringkat & Rekap Nilai CBT')
@section('subtitle', 'CBT Madrasah Digital')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-trophy mr-2 animate__animated animate__fadeInLeft"></i> 
                            Papan Peringkat & Analisis Nilai
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Lihat prestasi terbaik siswa berdasarkan hasil ujian CBT secara real-time dan transparan.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <i class="fas fa-chart-line fa-8x opacity-2 shadow-icon"></i>
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
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Total Peserta</p>
                        <h2 class="font-weight-bold mb-0 text-primary">{{ $stats['total_students'] }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-primary rounded-circle p-3">
                        <i class="fas fa-users text-primary fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #17a2b8 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Rata-rata Skor</p>
                        <h2 class="font-weight-bold mb-0 text-info">{{ number_format($stats['average_score'], 1) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-info rounded-circle p-3">
                        <i class="fas fa-chart-bar text-info fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-xs mt-3 bg-light">
                    <div class="progress-bar bg-info" style="width: {{ min(($stats['average_score'] / 100) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm info-card overflow-hidden" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-sm font-weight-bold text-uppercase text-muted mb-1">Skor Tertinggi</p>
                        <h2 class="font-weight-bold mb-0 text-warning">{{ number_format($stats['highest_score'], 1) }}</h2>
                    </div>
                    <div class="icon-shape bg-soft-warning rounded-circle p-3">
                        <i class="fas fa-crown text-warning fa-lg"></i>
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
    <!-- LEFT COLUMN: FILTERS -->
    <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInLeft">
        <div class="card shadow-sm border-0 mb-4 premium-card">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-filter mr-2 text-muted"></i> Filter Ranking
                </h5>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('admin.cbt.ranking.index') }}" method="GET" id="filterForm">
                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Ujian / Mata Pelajaran</label>
                        <select name="cbt_exam_id" class="form-control select2 custom-select-premium" onchange="this.form.submit()">
                            <option value="">-- Ranking Global (Semua) --</option>
                            @foreach($exams as $ex)
                                <option value="{{ $ex->id }}" {{ $selectedExam == $ex->id ? 'selected' : '' }}>
                                    {{ $ex->name }} ({{ $ex->bank->subject->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-uppercase text-muted">Kelas</label>
                        <select name="class_group_id" class="form-control select2" onchange="this.form.submit()">
                            <option value="">Semua Kelas</option>
                            @foreach($classGroups as $cg)
                                <option value="{{ $cg->id }}" {{ $selectedClass == $cg->id ? 'selected' : '' }}>
                                    {{ $cg->class_group }} {{ $cg->sub_class_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-indigo btn-block shadow-sm font-weight-bold py-2 btn-premium mr-2">
                            <i class="fas fa-search mr-2"></i> TERAPKAN
                        </button>
                        <a href="{{ route('admin.cbt.ranking.index') }}" class="btn btn-outline-secondary rounded-lg px-3">
                            <i class="fas fa-sync"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4 premium-card border-left-success-thick">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div class="icon-shape bg-soft-success rounded-circle">
                            <i class="fas fa-lightbulb text-success"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="font-weight-bold mb-1">Tips Navigasi</h6>
                        <p class="text-xs text-muted mb-0">Klik tombol PDF untuk melihat hasil jawaban detail siswa secara mendalam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DATA TABLE -->
    <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInRight">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark">
                            @if($selectedExam)
                                Hasil Peringkat: {{ $exams->find($selectedExam)->name }}
                            @else
                                Papan Peringkat Global
                            @endif
                        </h4>
                        <p class="text-muted text-sm mb-0">Diurutkan berdasarkan skor tertinggi</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="rankingTable">
                        <thead class="bg-light-info text-uppercase">
                            <tr>
                                <th width="70px" class="text-center py-3">Rank</th>
                                <th>Identitas Siswa</th>
                                <th class="text-center">Kelas</th>
                                @if(!$selectedExam)
                                    <th class="text-center">Ujian</th>
                                @endif
                                <th class="text-center" width="120px">Skor</th>
                                <th class="text-center" width="100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rankings as $index => $rank)
                                @php $pos = $index + 1; @endphp
                                <tr>
                                    <td class="text-center">
                                        @if($pos == 1)
                                            <div class="rank-medal gold animate__animated animate__heartBeat animate__infinite">1</div>
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
                                            <div class="avatar-sm mr-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 40px; height: 40px; border: 2px solid #fff;">
                                                @if(isset($rank->student->profile_photo_path))
                                                    <img src="{{ Storage::url($rank->student->profile_photo_path) }}" class="rounded-circle w-100 h-100 shadow-xs">
                                                @else
                                                    <i class="fas fa-user"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark" style="font-size: 1rem;">{{ $rank->nama_lengkap ?? $rank->student->nama_lengkap }}</div>
                                                <div class="text-xs text-muted">NISN: {{ $rank->nisn ?? $rank->student->nisn }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-indigo shadow-xs px-2 py-1">
                                            @if(isset($rank->class_group))
                                                {{ $rank->class_group }} {{ $rank->sub_class_group }}
                                            @else
                                                {{ $rank->student->classGroup->class_group ?? '' }} {{ $rank->student->classGroup->sub_class_group ?? '-' }}
                                            @endif
                                        </span>
                                    </td>
                                    @if(!$selectedExam)
                                        <td class="text-center">
                                            <span class="badge badge-soft-dark font-weight-bold">{{ $rank->exams_count }} Kali</span>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        <h5 class="mb-0 font-weight-bold text-indigo">
                                            {{ number_format($rank->total_score ?? $rank->final_score, 1) }}
                                        </h5>
                                    </td>
                                    <td class="text-center">
                                        @if($selectedExam)
                                            <a href="{{ route('admin.cbt.exam.export-student-pdf', $rank->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-xs" data-toggle="tooltip" title="Download PDF Hasil">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-light rounded-pill px-3" disabled><i class="fas fa-ellipsis-h"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-50">
                                            <i class="fas fa-trophy fa-4x mb-3 text-muted"></i>
                                            <h5 class="font-weight-bold text-muted">Belum Ada Data Nilai</h5>
                                            <p class="text-sm">Pastikan siswa sudah menyelesaikan ujian agar masuk peringkat.</p>
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
    .bg-gradient-indigo { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important; }
    .bg-indigo { background-color: #4e73df !important; color: white !important; }
    .btn-indigo { background: #4e73df; color: #fff; border: none; }
    .btn-indigo:hover { background: #2e59d9; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4); }
    .text-indigo { color: #4e73df !important; }
    .badge-light-indigo { background-color: #eef2ff; color: #4e73df; border: 1px solid #e0e7ff; }
    
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
    #rankingTable { border-collapse: separate; border-spacing: 0 10px; padding: 0 15px; }
    #rankingTable tbody tr { 
        background: #fff; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s ease;
        border-radius: 12px;
    }
    #rankingTable tbody tr:hover { 
        background: #f8fbff; 
        box-shadow: 0 6px 15px rgba(0,0,0,0.06); 
        transform: scale(1.002);
    }
    #rankingTable td { border: none; padding: 1.2rem 0.75rem; vertical-align: middle; }
    #rankingTable td:first-child { border-radius: 12px 0 0 12px; }
    #rankingTable td:last-child { border-radius: 0 12px 12px 0; }
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
    .badge-soft-dark { background-color: #f8f9fa; color: #343a40; border: 1px solid #dee2e6; }
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

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
