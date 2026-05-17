@extends('layouts.app')

@section('title', 'Peta Jalan Admin (Workflow)')

@section('content')
<div class="container-fluid position-relative">
    
    @php
        $isProActive = $setting->is_workflow_pro_active ?? false;
    @endphp

    @if(!$isProActive)
    <!-- PREMIUM LOCK OVERLAY -->
    <div class="premium-lock-overlay">
        <div class="premium-lock-card shadow-lg text-center">
            <div class="lock-glow-container mb-4">
                <div class="lock-pulse"></div>
                <div class="lock-circle">
                    <i class="fas fa-lock fa-3x text-warning floating-lock"></i>
                </div>
            </div>
            
            <span class="badge badge-warning text-xs font-weight-bold tracking-wider px-3 py-2 rounded-pill mb-3 uppercase">
                <i class="fas fa-crown mr-1"></i> MODUL PREMIUM PRO
            </span>
            
            <h2 class="font-weight-bold text-dark mb-2">Peta Jalan Admin (Workflow)</h2>
            <p class="text-muted mb-4 px-lg-5 text-sm" style="max-width: 600px; margin: 0 auto;">
                Satu-satunya modul akselerasi tata kelola madrasah terintegrasi untuk melacak, mengelola, dan memantau seluruh alur operasional sekolah di setiap semester secara berurutan dan terstruktur.
            </p>
            
            <div class="row text-left justify-content-center mb-4" style="max-width: 650px; margin: 0 auto 25px auto;">
                <div class="col-md-6 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <span class="text-xs font-weight-bold text-dark">Panduan Alur Kerja Terstruktur</span>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <span class="text-xs font-weight-bold text-dark">Integrasi Satu-Klik Ke Fitur Utama</span>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <span class="text-xs font-weight-bold text-dark">Monitoring Kesiapan Operasional</span>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <span class="text-xs font-weight-bold text-dark">Lisensi Lifetime & Update Selamanya</span>
                    </div>
                </div>
            </div>
            
            <div class="price-box p-3 bg-light rounded-15 mb-4 d-inline-block px-5">
                <span class="text-[10px] text-muted d-block text-uppercase font-weight-bold tracking-wider mb-1">Investasi Satu Kali (Lifetime)</span>
                <span class="text-xs text-danger text-strike mr-2"><s>Rp 249.000</s></span>
                <span class="h4 font-weight-bold text-success mb-0">Rp {{ number_format($setting->workflow_price ?? 99000, 0, ',', '.') }}</span>
            </div>
            
            <div class="d-block">
                <button type="button" onclick="openPaymentModal()" class="btn btn-primary btn-lg rounded-pill px-5 font-weight-bold shadow-primary btn-glow">
                    <i class="fas fa-shopping-cart mr-2"></i> BELI & AKTIFKAN SEKARANG
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MAIN WORKFLOW CONTENT -->
    <div class="{{ !$isProActive ? 'premium-blurred-content' : '' }}">
        <div class="row">
            <div class="col-12">
                <div class="card bg-gradient-primary border-0 shadow-lg mb-4">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-4 text-white font-weight-bold mb-3">Peta Jalan Administrasi</h1>
                                <p class="lead text-white-50">Panduan langkah demi langkah untuk mengelola operasional madrasah secara berurutan di setiap semester.</p>
                                
                                @if($isProActive)
                                <div class="mt-3">
                                    <span class="badge badge-success px-3 py-2 rounded-pill font-weight-bold shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> MODUL PRO AKTIF
                                    </span>
                                    <button onclick="resetActivation()" class="btn btn-xs btn-outline-light rounded-pill ml-3 px-3 py-1 font-weight-bold">
                                        <i class="fas fa-redo-alt mr-1"></i> Kunci Kembali (Simulasi)
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="col-lg-4 text-right d-none d-lg-block">
                                <i class="fas fa-map-marked-alt fa-10x text-white-50 opacity-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- SEMESTER 1 -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <div class="icon-shape bg-soft-primary text-primary rounded-circle mr-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">Semester 1 (Ganjil)</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-one-side">
                            @foreach($semester1 as $index => $item)
                            <div class="timeline-block mb-4">
                                <span class="timeline-step badge-{{ $item['color'] }}">
                                    {{ $index + 1 }}
                                </span>
                                <div class="timeline-content card shadow-none border bg-light-{{ $item['color'] }} p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-{{ $item['color'] }} font-weight-bold mb-0">
                                            <i class="{{ $item['icon'] }} mr-2"></i> {{ $item['title'] }}
                                        </h6>
                                        @if($isProActive && Route::has($item['route']))
                                        <a href="{{ route($item['route']) }}" class="btn btn-xs btn-{{ $item['color'] }} rounded-pill px-3">
                                            Kerjakan <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                        @endif
                                    </div>
                                    <p class="text-xs text-dark mb-0">{{ $item['description'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEMESTER 2 -->
            <div class="col-xl-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <div class="icon-shape bg-soft-success text-success rounded-circle mr-3">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">Semester 2 (Genap)</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-one-side">
                            @foreach($semester2 as $index => $item)
                            <div class="timeline-block mb-4">
                                <span class="timeline-step badge-{{ $item['color'] }}">
                                    {{ $index + 1 }}
                                </span>
                                <div class="timeline-content card shadow-none border bg-light-{{ $item['color'] }} p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-{{ $item['color'] }} font-weight-bold mb-0">
                                            <i class="{{ $item['icon'] }} mr-2"></i> {{ $item['title'] }}
                                        </h6>
                                        @if($isProActive && Route::has($item['route']))
                                        <a href="{{ route($item['route']) }}" class="btn btn-xs btn-{{ $item['color'] }} rounded-pill px-3">
                                            Kerjakan <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                        @endif
                                    </div>
                                    <p class="text-xs text-dark mb-0">{{ $item['description'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MOCK PAYMENT MODAL -->
<div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-wallet text-primary mr-2"></i> Gerbang Pembayaran Instan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closePaymentBtn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4" id="paymentModalBody">
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-15">
                    <div>
                        <span class="text-[10px] text-muted font-weight-bold uppercase d-block">Nomor Invoice</span>
                        <h6 class="font-weight-bold mb-0 text-dark" id="invoiceNumber">INV/{{ date('Ymd') }}/PRO/{{ rand(10000, 99999) }}</h6>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-muted font-weight-bold uppercase d-block">Total Bayar</span>
                        <h6 class="font-weight-bold mb-0 text-success">Rp {{ number_format($setting->workflow_price ?? 99000, 0, ',', '.') }}</h6>
                    </div>
                </div>
                
                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-qrcode text-warning mr-1"></i> Metode 1: QRIS / E-Wallet</h6>
                <div class="text-center mb-4">
                    <!-- A beautiful placeholder for QRIS -->
                    <div class="d-inline-block p-3 bg-white border rounded-15 shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https://mtsbustanulhuda.sch.id/payment-simulation-workflow-premium-activation-code-mtsbh01" alt="QRIS" class="img-fluid" style="width: 160px; height: 160px;">
                        <div class="mt-2 text-[9px] font-weight-bold text-muted text-uppercase tracking-wider">Pindai Dengan GoPay, OVO, Dana, LinkAja, Mobile Banking</div>
                    </div>
                </div>
                
                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-university text-info mr-1"></i> Metode 2: Transfer Virtual Account</h6>
                <div class="p-3 border rounded-15 bg-light mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-muted">Bank Transfer BCA</span>
                        <span class="badge badge-info px-2 py-1 rounded">Virtual Account</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bold text-dark mb-0">8392 + {{ rand(10000000, 99999999) }}</h5>
                        <button class="btn btn-xs btn-outline-primary px-2 rounded-pill" onclick="Swal.fire('Disalin', 'Nomor Virtual Account berhasil disalin!', 'success')">Salin</button>
                    </div>
                </div>
                
                <div class="alert alert-warning border-0 rounded-15 mb-0 d-flex align-items-start text-xs text-dark shadow-none" style="background-color: #fff9db;">
                    <i class="fas fa-info-circle mr-2 mt-1 fa-lg text-warning"></i>
                    <div>
                        <strong>Simulasi Demo:</strong> Untuk keperluan demonstrasi/pengujian modul Peta Jalan Admin Premium, Anda tidak perlu mengeluarkan uang sungguhan! Silakan klik tombol simulasi sukses di bawah ini.
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 p-4 pt-0 justify-content-center">
                <button type="button" id="btnSimulateSuccess" onclick="simulatePayment()" class="btn btn-success btn-block rounded-pill py-3 font-weight-bold shadow-success">
                    <i class="fas fa-check-double mr-2"></i> SIMULASIKAN PEMBAYARAN SUKSES
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pure CSS Canvas-like Dynamic Particles for Celeb Success -->
<div id="particle-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; display: none;"></div>

<style>
    /* PREMIUM GLOW AND LOCK INTERFACE STYLES */
    .premium-lock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .premium-lock-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 25px;
        padding: 50px 40px;
        max-width: 750px;
        width: 100%;
        margin-top: 50px;
    }
    
    .premium-blurred-content {
        filter: blur(8px) grayscale(30%);
        opacity: 0.35;
        pointer-events: none;
        user-select: none;
        -webkit-user-select: none;
    }
    
    .lock-glow-container {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .lock-circle {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(217, 119, 6, 0.4);
        z-index: 2;
    }
    
    .lock-pulse {
        position: absolute;
        width: 100px;
        height: 100px;
        background: rgba(245, 158, 11, 0.3);
        border-radius: 50%;
        animation: pulseLock 2s infinite;
        z-index: 1;
    }
    
    .floating-lock {
        color: #ffffff !important;
        animation: floatLock 3s ease-in-out infinite;
    }
    
    .btn-glow {
        box-shadow: 0 0 20px rgba(94, 114, 228, 0.6);
        animation: buttonGlow 2.5s infinite;
        transition: all 0.3s ease;
    }
    
    .btn-glow:hover {
        transform: scale(1.05);
        box-shadow: 0 0 30px rgba(94, 114, 228, 0.9);
    }
    
    .rounded-15 { border-radius: 15px !important; }
    
    .shadow-primary { box-shadow: 0 7px 14px rgba(94, 114, 228, 0.25), 0 3px 6px rgba(0, 0, 0, 0.08); }
    .shadow-success { box-shadow: 0 7px 14px rgba(45, 206, 137, 0.25), 0 3px 6px rgba(0, 0, 0, 0.08); }
    
    .text-strike { text-decoration: line-through; }
    
    /* ANIMATIONS */
    @keyframes pulseLock {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.2); opacity: 0; }
        100% { transform: scale(0.95); opacity: 0.8; }
    }
    
    @keyframes floatLock {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }
    
    @keyframes buttonGlow {
        0% { box-shadow: 0 0 10px rgba(94, 114, 228, 0.5); }
        50% { box-shadow: 0 0 25px rgba(94, 114, 228, 0.8); }
        100% { box-shadow: 0 0 10px rgba(94, 114, 228, 0.5); }
    }
    
    /* WORKFLOW ORIGINAL STYLES */
    .bg-gradient-primary {
        background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bg-soft-primary { background-color: rgba(94, 114, 228, 0.1); }
    .bg-soft-success { background-color: rgba(45, 206, 137, 0.1); }
    
    .timeline { position: relative; }
    .timeline:before {
        content: "";
        position: absolute;
        top: 0;
        left: 1rem;
        height: 100%;
        border-right: 2px dashed #e9ecef;
    }
    .timeline-block { position: relative; padding-left: 3rem; }
    .timeline-step {
        position: absolute;
        left: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        z-index: 1;
        font-weight: bold;
    }
    
    .bg-light-primary { background-color: #f6f9ff; }
    .bg-light-success { background-color: #f6fff9; }
    .bg-light-info { background-color: #f6fbff; }
    .bg-light-warning { background-color: #fffaf6; }
    .bg-light-danger { background-color: #fff6f6; }
    .bg-light-secondary { background-color: #fcfcfc; }
    .bg-light-dark { background-color: #f8f9fa; }
    
    .opacity-2 { opacity: 0.2; }
    
    /* Success Confetti CSS Particles */
    .particle {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        animation: particleDrop 3s ease-out forwards;
    }
    
    @keyframes particleDrop {
        0% { transform: translateY(-50px) rotate(0deg); opacity: 1; }
        100% { transform: translateY(105vh) rotate(720deg); opacity: 0; }
    }
</style>

@endsection

@push('scripts')
<script>
    // Audio synthesis of a highly professional celebratory success chime using Web Audio API
    function playSuccessChime() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            
            // Double tone chime (pleasant chord)
            const playTone = (freq, delay, duration) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + delay);
                
                gain.gain.setValueAtTime(0, ctx.currentTime + delay);
                gain.gain.linearRampToValueAtTime(0.3, ctx.currentTime + delay + 0.05);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay + duration);
                
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + delay);
                osc.stop(ctx.currentTime + delay + duration);
            };
            
            // Success chord C major pentatonic (C5, E5, G5, C6)
            playTone(523.25, 0, 1.2);     // C5
            playTone(659.25, 0.08, 1.2);  // E5
            playTone(783.99, 0.16, 1.2);  // G5
            playTone(1046.50, 0.24, 1.5); // C6
        } catch (e) {
            console.error("Web Audio API Chime error:", e);
        }
    }
    
    // CSS-based Particle Confetti Emitter
    function emitConfetti() {
        const container = document.getElementById('particle-container');
        container.innerHTML = '';
        container.style.display = 'block';
        
        const colors = ['#f59e0b', '#10b981', '#3b82f6', '#ec4899', '#8b5cf6', '#ef4444', '#06b6d4'];
        
        for (let i = 0; i < 150; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            const color = colors[Math.floor(Math.random() * colors.length)];
            const size = Math.random() * 8 + 6;
            const left = Math.random() * 100;
            const delay = Math.random() * 2;
            const duration = Math.random() * 2 + 2;
            
            particle.style.backgroundColor = color;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = left + '%';
            particle.style.animationDelay = delay + 's';
            particle.style.animationDuration = duration + 's';
            particle.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
            
            container.appendChild(particle);
        }
        
        setTimeout(() => {
            container.style.display = 'none';
            container.innerHTML = '';
        }, 5000);
    }

    function openPaymentModal() {
        $('#modal-payment').modal('show');
    }

    function simulatePayment() {
        const btn = document.getElementById('btnSimulateSuccess');
        const closeBtn = document.getElementById('closePaymentBtn');
        
        btn.disabled = true;
        closeBtn.disabled = true;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> VERIFIKASI PEMBAYARAN...';
        
        // Dynamic verified step loading
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> PEMBAYARAN BERHASIL!';
            btn.className = 'btn btn-success btn-block rounded-pill py-3 font-weight-bold shadow-success';
            
            // Play dynamic synthesized sound chime
            playSuccessChime();
            // Emit confetti
            emitConfetti();
            
            setTimeout(() => {
                // Post payment success verification to active route
                $.post('{{ route("admin.workflow.activate") }}', {
                    _token: '{{ csrf_token() }}'
                })
                .done(res => {
                    $('#modal-payment').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Aktivasi Berhasil!',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .fail(xhr => {
                    Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan sistem', 'error');
                    btn.disabled = false;
                    closeBtn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-double mr-2"></i> SIMULASIKAN PEMBAYARAN SUKSES';
                });
            }, 1000);
        }, 2000);
    }

    function resetActivation() {
        Swal.fire({
            title: 'Kunci Modul?',
            text: "Modul Peta Jalan Admin akan dikunci kembali ke status PRO berbayar untuk keperluan pengujian simulasi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#4b5563',
            confirmButtonText: 'Ya, Kunci Kembali',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("admin.workflow.reset") }}', {
                    _token: '{{ csrf_token() }}'
                })
                .done(res => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Dikunci Kembali',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .fail(xhr => {
                    Swal.fire('Gagal', 'Terjadi kesalahan sistem saat mengunci modul', 'error');
                });
            }
        });
    }
</script>
@endpush
