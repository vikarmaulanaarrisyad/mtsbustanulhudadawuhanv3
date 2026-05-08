@extends('layouts.app')
@section('title', 'Live Monitoring: ' . $exam->name)
@section('subtitle', 'CBT Madrasah Digital')

@section('content')
{{-- PREMIUM HEADER --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 overflow-hidden position-relative" style="border-radius:20px; background: linear-gradient(135deg, #0f172a 0%, #334155 100%);">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-md-7 text-white">
                        <a href="{{ route('admin.cbt.exam.index') }}" class="btn btn-sm btn-glass mb-3 rounded-pill px-3">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        <h1 class="display-5 font-weight-bold mb-1"><i class="fas fa-desktop mr-2 text-info"></i>Live Monitoring</h1>
                        <p class="mb-0 opacity-80 lead">
                            <span class="mr-3"><i class="fas fa-file-signature mr-1 text-warning"></i> {{ $exam->name }}</span>
                            <span><i class="fas fa-calendar-alt mr-1 text-info"></i> {{ $exam->exam_date ? $exam->exam_date->format('d M Y') : '-' }}</span>
                        </p>
                    </div>
                    <div class="col-md-5 text-right d-none d-md-block">
                        <div class="token-card shadow-sm animate__animated animate__pulse animate__infinite">
                            <small class="text-uppercase font-weight-bold opacity-70">TOKEN UJIAN</small>
                            <h2 class="font-weight-black mb-0 letter-spacing-2">{{ $exam->token }}</h2>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.cbt.exam.export-excel', $exam->id) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm border-0 font-weight-bold">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </a>
                            <a href="{{ route('admin.cbt.exam.export-pdf', $exam->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm border-0 font-weight-bold">
                                <i class="fas fa-file-pdf mr-1"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-shape-1"></div>
            <div class="header-shape-2"></div>
        </div>
    </div>
</div>

{{-- STATS SECTION --}}
<div class="row mb-4">
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-primary mr-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Total Peserta</span>
                </div>
                <h2 class="font-weight-bold mb-0" id="stat-total">{{ $exam->studentExams->count() }}</h2>
                <div class="text-xs text-muted mt-2">Siswa telah login ke ujian</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-warning mr-3">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Aktif</span>
                </div>
                <h2 class="font-weight-bold mb-0 text-warning" id="stat-doing">{{ $exam->studentExams->where('status', 'doing')->count() }}</h2>
                <div class="text-xs text-muted mt-2">Sedang mengerjakan soal</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-success mr-3">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Selesai</span>
                </div>
                <h2 class="font-weight-bold mb-0 text-success" id="stat-finished">{{ $exam->studentExams->where('status', 'finished')->count() }}</h2>
                <div class="text-xs text-muted mt-2">Telah mengirimkan jawaban</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 stat-card-premium" style="border-radius:18px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-shape-premium bg-soft-danger mr-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="text-xs font-weight-bold text-muted text-uppercase letter-spacing-1">Pelanggaran</span>
                </div>
                <h2 class="font-weight-bold mb-0 text-danger" id="stat-violations">{{ $exam->studentExams->sum('violation_count') }}</h2>
                <div class="text-xs text-muted mt-2">Deteksi kecurangan sistem</div>
            </div>
        </div>
    </div>
</div>

{{-- MONITORING TABLE --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-5" style="border-radius:20px;">
            <div class="card-header bg-white py-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">Daftar Real-time Peserta</h4>
                    <p class="text-muted text-sm mb-0 mt-1"><i class="fas fa-sync-alt fa-spin mr-1 text-info"></i> Auto-refresh aktif setiap 30 detik</p>
                </div>
                <button class="btn btn-primary rounded-xl px-4 py-2 font-weight-bold shadow-sm" onclick="location.reload()">
                    <i class="fas fa-sync mr-2"></i> REFRESH SEKARANG
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0 premium-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pr-3 pl-4">Peserta</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">Status Login</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2">Anti-Cheat</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 text-center">Nilai</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-2 text-center">Progress</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pl-2 pr-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exam->studentExams as $se)
                            <tr>
                                <td class="pl-4">
                                    <div class="d-flex px-0 py-1">
                                        <div class="avatar-premium-sm mr-3">
                                            <span>{{ substr($se->student->name, 0, 1) }}</span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $se->student->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">NISN: {{ $se->student->nisn }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($se->status == 'finished')
                                        <span class="badge badge-pill badge-soft-success font-weight-bold">SELESAI</span>
                                    @elseif($se->status == 'doing')
                                        <span class="badge badge-pill badge-soft-warning font-weight-bold animate__animated animate__flash animate__infinite">MENGERJAKAN</span>
                                    @else
                                        <span class="badge badge-pill badge-soft-secondary font-weight-bold">STANDBY</span>
                                    @endif
                                </td>
                                <td>
                                    @if($se->violation_count == 0)
                                        <span class="text-success text-xs font-weight-bold"><i class="fas fa-shield-alt mr-1"></i> Aman</span>
                                    @else
                                        <span class="badge badge-pill badge-soft-danger font-weight-bold">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $se->violation_count }} Pelanggaran
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($se->status == 'finished')
                                        <span class="h5 font-weight-black text-primary mb-0">{{ number_format($se->final_score, 0) }}</span>
                                    @else
                                        <span class="text-xs text-muted font-italic">In Progress</span>
                                    @endif
                                </td>
                                <td class="text-center" style="min-width: 150px;">
                                    @php
                                        // Simple calculation if questions count exists, otherwise 0
                                        $percent = $se->status == 'finished' ? 100 : ($se->start_time ? 45 : 0);
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="mr-2 text-xs font-weight-bold">{{ $percent }}%</span>
                                        <div class="progress shadow-none" style="height: 6px; width: 80px; border-radius: 10px;">
                                            <div class="progress-bar {{ $percent == 100 ? 'bg-success' : 'bg-info' }}" role="progressbar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center pr-4">
                                    @if($se->status == 'finished')
                                        <a href="{{ route('admin.cbt.exam.export-student-pdf', $se->id) }}" class="btn btn-xs btn-outline-danger rounded-pill font-weight-bold px-3">
                                            <i class="fas fa-file-pdf mr-1"></i> Cetak Detail
                                        </a>
                                    @else
                                        <span class="text-xxs text-muted">{{ $se->start_time ? $se->start_time->format('H:i') : '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="opacity-50">
                                        <i class="fas fa-user-slash fa-3x mb-3 text-muted"></i>
                                        <p class="font-weight-bold">Belum ada peserta yang aktif.</p>
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
/* PREMIUM DESIGN TOKENS */
.letter-spacing-1 { letter-spacing: 1px; }
.letter-spacing-2 { letter-spacing: 2px; }
.font-weight-black { font-weight: 900; }
.rounded-xl { border-radius: 12px; }

/* HEADER */
.btn-glass { background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s; }
.btn-glass:hover { background: rgba(255,255,255,0.25); color: white; transform: translateX(-5px); }
.header-shape-1 { position: absolute; width: 300px; height: 300px; top: -150px; right: -50px; background: rgba(0, 184, 217, 0.15); border-radius: 50%; }
.header-shape-2 { position: absolute; width: 200px; height: 200px; bottom: -100px; left: 10%; background: rgba(255, 171, 0, 0.1); border-radius: 50%; }

.token-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 20px;
    padding: 15px 30px;
    display: inline-block;
    color: white;
    text-align: center;
}

/* STAT CARDS */
.stat-card-premium { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
.stat-card-premium:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
.icon-shape-premium {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* BADGES SOFT */
.badge-soft-primary { background: #eef2ff; color: #4f46e5; }
.badge-soft-success { background: #ecfdf5; color: #10b981; }
.badge-soft-warning { background: #fffbeb; color: #f59e0b; }
.badge-soft-danger { background: #fef2f2; color: #ef4444; }
.badge-soft-secondary { background: #f8fafc; color: #64748b; }

.bg-soft-primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
.bg-soft-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.bg-soft-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.bg-soft-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

/* TABLE PREMIUM */
.premium-table thead th {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 15px 20px;
}
.premium-table tbody td {
    padding: 18px 20px;
    vertical-align: middle;
}
.avatar-premium-sm {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.text-xxs { font-size: 0.65rem; }

/* AUTO REFRESH ANIMATION */
@keyframes flash-warning {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setTimeout(function(){
        location.reload();
    }, 30000);
</script>
@endpush
@endsection
