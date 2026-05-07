@extends($layout)

@section('title', 'Monitoring Presensi Guru (Live)')

@section('content')
<!-- PREMIUM HEADER BANNER -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 mb-4 bg-gradient-indigo overflow-hidden position-relative" style="border-radius: 15px;">
            <div class="card-body p-4 position-relative" style="z-index: 1;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-white">
                        <h2 class="font-weight-bold mb-1">
                            <i class="fas fa-satellite-dish mr-2 animate__animated animate__pulse animate__infinite"></i> 
                            Monitoring Presensi Real-Time
                        </h2>
                        <p class="mb-0 opacity-8 text-lg font-weight-light">
                            Pantau kehadiran guru dan staf hari ini, {{ date('d F Y') }}. Data diperbarui otomatis secara berkala.
                        </p>
                    </div>
                    <div class="col-md-4 text-right d-none d-md-block">
                        <div class="live-indicator-wrapper">
                            <span class="live-badge">LIVE</span>
                            <div class="pulse-ring"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-circle-1"></div>
            <div class="bg-circle-2"></div>
        </div>
    </div>
</div>

<!-- STATS CARDS -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 premium-card h-100 bg-white">
            <div class="card-body p-4 text-center">
                <div class="avatar-lg bg-soft-primary rounded-circle mx-auto mb-3 flex items-center justify-center" style="width:60px; height:60px;">
                    <i class="fas fa-users text-primary fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-dark mb-0">{{ $stats['total'] }}</h3>
                <p class="text-muted text-xs font-weight-bold uppercase mb-0">Total Guru/Staf</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 premium-card h-100 bg-white border-bottom-success-thick">
            <div class="card-body p-4 text-center">
                <div class="avatar-lg bg-soft-success rounded-circle mx-auto mb-3 flex items-center justify-center" style="width:60px; height:60px;">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-success mb-0">{{ $stats['present'] }}</h3>
                <p class="text-muted text-xs font-weight-bold uppercase mb-0">Hadir Tepat Waktu</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 premium-card h-100 bg-white border-bottom-warning-thick">
            <div class="card-body p-4 text-center">
                <div class="avatar-lg bg-soft-warning rounded-circle mx-auto mb-3 flex items-center justify-center" style="width:60px; height:60px;">
                    <i class="fas fa-clock text-warning fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-warning mb-0">{{ $stats['late'] }}</h3>
                <p class="text-muted text-xs font-weight-bold uppercase mb-0">Terlambat</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 premium-card h-100 bg-white border-bottom-danger-thick">
            <div class="card-body p-4 text-center">
                <div class="avatar-lg bg-soft-danger rounded-circle mx-auto mb-3 flex items-center justify-center" style="width:60px; height:60px;">
                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-danger mb-0">{{ $stats['absent'] }}</h3>
                <p class="text-muted text-xs font-weight-bold uppercase mb-0">Belum Absen</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 premium-card">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">Daftar Kehadiran Hari Ini</h5>
                <span class="badge badge-soft-indigo p-2 px-3 rounded-pill">
                    <i class="fas fa-sync fa-spin mr-2"></i> Auto-refresh aktif
                </span>
            </div>
            <div class="card-body p-4 bg-light-soft">
                <div class="row" id="teacher-grid">
                    @foreach($teachers as $teacher)
                        @php
                            $att = $attendances->get($teacher->id);
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card border-0 shadow-xs h-100 teacher-card {{ $att ? 'present-border' : 'absent-border' }}">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-sm mr-3">
                                            @if($att && $att->image_in)
                                                <img src="{{ Storage::url($att->image_in) }}" class="rounded-lg shadow-sm" style="width:50px; height:50px; object-fit: cover;">
                                            @elseif($teacher->user && $teacher->user->profile_photo_path)
                                                <img src="{{ Storage::url($teacher->user->profile_photo_path) }}" class="rounded-lg shadow-sm" style="width:50px; height:50px; object-fit: cover;">
                                            @else
                                                <div class="rounded-lg bg-soft-secondary d-flex align-items-center justify-content-center text-secondary font-weight-bold" style="width:50px; height:50px;">
                                                    {{ substr($teacher->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h6 class="font-weight-bold text-dark mb-0 text-truncate">{{ $teacher->name }}</h6>
                                            <small class="text-muted text-xs">{{ $teacher->position->name ?? 'Staf' }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="attendance-info p-2 rounded-lg bg-light border border-light-dark">
                                        @if($att)
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-xs font-weight-bold text-muted">MASUK:</span>
                                                <span class="text-xs font-weight-bold text-indigo">{{ $att->check_in }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-xs font-weight-bold text-muted">STATUS:</span>
                                                <span class="badge badge-{{ $att->status == 'present' ? 'success' : 'warning' }} text-[10px] py-0 px-2 rounded-pill uppercase">
                                                    {{ $att->status == 'present' ? 'TEPAT WAKTU' : 'TERLAMBAT' }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-center py-2">
                                                <span class="badge badge-soft-danger text-[10px] py-1 px-3 rounded-pill uppercase font-weight-bold">
                                                    BELUM ABSEN
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Design Elements */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
    .bg-soft-indigo { background: #eef2ff; color: #6366f1; }
    .bg-soft-primary { background: #e0f2fe; }
    .bg-soft-success { background: #dcfce7; }
    .bg-soft-warning { background: #fef3c7; }
    .bg-soft-danger { background: #fee2e2; }
    .bg-soft-secondary { background: #f1f5f9; }
    
    .border-bottom-success-thick { border-bottom: 4px solid #10b981 !important; }
    .border-bottom-warning-thick { border-bottom: 4px solid #f59e0b !important; }
    .border-bottom-danger-thick { border-bottom: 4px solid #ef4444 !important; }
    
    .premium-card { border-radius: 15px; overflow: hidden; }
    .shadow-xs { box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .bg-light-soft { background: #f8fafc; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    
    .teacher-card { transition: all 0.3s ease; border: 1px solid #f1f5f9; }
    .teacher-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
    .present-border { border-left: 4px solid #10b981; }
    .absent-border { border-left: 4px solid #ef4444; opacity: 0.8; }
    
    .bg-circle-1, .bg-circle-2 { position: absolute; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0; }
    .bg-circle-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .bg-circle-2 { width: 150px; height: 150px; bottom: -50px; left: 10%; }

    /* Live Indicator */
    .live-indicator-wrapper { position: relative; display: inline-block; padding: 20px; }
    .live-badge { 
        background: #ef4444; color: #fff; padding: 5px 15px; border-radius: 20px; 
        font-weight: 900; letter-spacing: 2px; font-size: 14px; position: relative; z-index: 2;
    }
    .pulse-ring {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 100%; height: 100%; background: rgba(239, 68, 68, 0.4); border-radius: 30px;
        z-index: 1; animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(0.8); opacity: 1; }
        100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
    }
</style>

@push('scripts')
<script>
    // Auto refresh every 30 seconds
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection
