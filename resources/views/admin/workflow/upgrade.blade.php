@extends('layouts.app')

@section('title', 'Aktivasi Modul Premium - ' . $moduleData['name'])

@section('content')
<div class="container-fluid position-relative py-4">
    
    <!-- PREMIUM LOCK OR VERIFICATION PAGE -->
    <div class="premium-lock-container">
        
        @if($pendingTransaction)
            <!-- STATUS PENDING / VERIFIKASI PENGIRIMAN -->
            <div class="premium-lock-card shadow-lg text-center mx-auto border border-warning" style="background: rgba(255,255,255,0.95);">
                <div class="mb-4">
                    <div class="verification-pulse-container mx-auto">
                        <div class="verification-pulse"></div>
                        <div class="verification-icon-circle bg-warning text-white">
                            <i class="fas fa-history fa-2x floating-icon"></i>
                        </div>
                    </div>
                </div>
                
                <span class="badge badge-warning text-xs font-weight-bold tracking-wider px-3 py-2 rounded-pill mb-3 uppercase">
                    <i class="fas fa-hourglass-half mr-1"></i> MENUNGGU VERIFIKASI
                </span>
                
                <h2 class="font-weight-bold text-dark mb-2">Pembayaran Sedang Diverifikasi</h2>
                <p class="text-muted mb-4 px-lg-5 text-sm" style="max-width: 620px; margin: 0 auto; line-height: 1.6;">
                    Permintaan aktivasi modul <strong>{{ $moduleData['name'] }}</strong> sedang ditinjau secara manual oleh Pemilik (Owner). Pembayaran Anda sebesar <strong>Rp {{ number_format($pendingTransaction->amount, 0, ',', '.') }}</strong> dengan Invoice <strong>{{ $pendingTransaction->invoice_no }}</strong> sedang dalam proses pencocokan mutasi.
                </p>
                
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-15 bg-light text-left">
                            <span class="text-[10px] text-muted font-weight-bold uppercase d-block mb-1">Detail Pengajuan:</span>
                            <div class="d-flex justify-content-between text-xs text-dark mb-1">
                                <span>Paket Durasi:</span>
                                <strong class="text-primary">
                                    {{ $pendingTransaction->duration == '30' ? 'Bulanan (30 Hari)' : ($pendingTransaction->duration == '365' ? 'Tahunan (365 Hari)' : 'Selamanya (Lifetime)') }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between text-xs text-dark mb-1">
                                <span>Metode Bayar:</span>
                                <strong>{{ $pendingTransaction->payment_method }}</strong>
                            </div>
                            <div class="d-flex justify-content-between text-xs text-dark mb-1">
                                <span>Tanggal Kirim:</span>
                                <strong>{{ $pendingTransaction->created_at->format('d M Y H:i') }} WIB</strong>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ asset($pendingTransaction->transfer_proof) }}" target="_blank" class="btn btn-xs btn-outline-info rounded-pill px-3">
                                    <i class="fas fa-file-invoice-dollar mr-1"></i> Lihat Bukti Transfer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-block">
                    <button type="button" onclick="openPaymentModal()" class="btn btn-warning rounded-pill px-5 font-weight-bold text-white">
                        <i class="fas fa-upload mr-2"></i> UNGGAH ULANG BUKTI
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-link btn-lg text-muted d-block mt-3 text-xs">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @else
            <!-- STANDARD LOCK PAGE -->
            <div class="premium-lock-card shadow-lg text-center mx-auto">
                <div class="lock-glow-container mb-4">
                    <div class="lock-pulse"></div>
                    <div class="lock-circle">
                        <i class="fas fa-lock fa-3x text-warning floating-lock"></i>
                    </div>
                </div>
                
                <span class="badge badge-warning text-xs font-weight-bold tracking-wider px-3 py-2 rounded-pill mb-3 uppercase">
                    <i class="fas fa-crown mr-1"></i> FITUR PREMIUM PRO
                </span>
                
                <h2 class="font-weight-bold text-dark mb-2">{{ $moduleData['name'] }}</h2>
                <p class="text-muted mb-4 px-lg-5 text-sm" style="max-width: 600px; margin: 0 auto;">
                    {{ $moduleData['description'] }} Pilih masa aktif lisensi yang paling sesuai dengan kebutuhan madrasah Anda di bawah ini.
                </p>
                
                @if($rejectedTransaction)
                    <div class="alert alert-danger border-0 rounded-15 mb-4 text-xs mx-auto text-left" style="max-width: 600px;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Pengajuan Sebelumnya Ditolak:</strong> Bukti transfer yang Anda kirimkan sebelumnya tidak valid/ditolak oleh pemilik. Silakan ajukan ulang dengan bukti transfer pembayaran yang benar.
                    </div>
                @endif
                
                <!-- DURATION SELECTOR CARDS -->
                <div class="row justify-content-center mb-4 mx-auto" style="max-width: 700px;">
                    <!-- Monthly -->
                    <div class="col-md-4 mb-3">
                        <div class="duration-card p-3 border rounded-15 text-center cursor-pointer active" onclick="selectDuration('30', {{ $moduleData['price_monthly'] }}, this)">
                            <div class="duration-icon text-primary mb-2"><i class="fas fa-calendar-alt fa-lg"></i></div>
                            <h6 class="font-weight-bold mb-1 text-dark">Bulanan</h6>
                            <span class="text-[10px] text-muted d-block mb-2">Aktif 30 Hari</span>
                            <h6 class="font-weight-bold text-primary mb-0">Rp {{ number_format($moduleData['price_monthly'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <!-- Yearly -->
                    <div class="col-md-4 mb-3">
                        <div class="duration-card p-3 border rounded-15 text-center cursor-pointer" onclick="selectDuration('365', {{ $moduleData['price_yearly'] }}, this)">
                            <span class="badge badge-success text-[8px] position-absolute px-2 py-1 rounded-pill" style="top: -10px; right: 10px;">HEMAT 15%</span>
                            <div class="duration-icon text-success mb-2"><i class="fas fa-calendar-check fa-lg"></i></div>
                            <h6 class="font-weight-bold mb-1 text-dark">Tahunan</h6>
                            <span class="text-[10px] text-muted d-block mb-2">Aktif 365 Hari</span>
                            <h6 class="font-weight-bold text-success mb-0">Rp {{ number_format($moduleData['price_yearly'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    <!-- Lifetime -->
                    <div class="col-md-4 mb-3">
                        <div class="duration-card p-3 border rounded-15 text-center cursor-pointer" onclick="selectDuration('lifetime', {{ $moduleData['price'] }}, this)">
                            <span class="badge badge-warning text-[8px] position-absolute px-2 py-1 rounded-pill" style="top: -10px; right: 10px;">POPULER</span>
                            <div class="duration-icon text-warning mb-2"><i class="fas fa-crown fa-lg"></i></div>
                            <h6 class="font-weight-bold mb-1 text-dark">Selamanya</h6>
                            <span class="text-[10px] text-muted d-block mb-2">Lifetime Access</span>
                            <h6 class="font-weight-bold text-warning mb-0">Rp {{ number_format($moduleData['price'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
                
                <div class="price-box p-3 bg-light rounded-15 mb-4 d-inline-block px-5">
                    <span class="text-[10px] text-muted d-block text-uppercase font-weight-bold tracking-wider mb-1" id="durationLabel">Investasi Paket Bulanan</span>
                    <span class="text-xs text-danger text-strike mr-2 d-none" id="originalPriceContainer"><s>Rp <span id="originalPriceLabel">0</span></s></span>
                    <span class="h4 font-weight-bold text-success mb-0" id="finalPriceLabel">Rp {{ number_format($moduleData['price_monthly'], 0, ',', '.') }}</span>
                </div>
                
                <div class="d-block">
                    <button type="button" onclick="openPaymentModal()" class="btn btn-primary btn-lg rounded-pill px-5 font-weight-bold shadow-primary btn-glow">
                        <i class="fas fa-shopping-cart mr-2"></i> BELI & AKTIFKAN LISENSI
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-link btn-lg text-muted d-block mt-3 text-xs">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- CHECKOUT PAYMENT MODAL WITH MANUAL PROOF UPLOAD -->
<div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-white border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-wallet text-primary mr-2"></i> Gerbang Pembayaran Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closePaymentBtn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="checkoutForm" onsubmit="submitCheckoutForm(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="duration" id="checkout_duration" value="30">
                
                <div class="modal-body p-4">
                    <!-- Invoice Summary -->
                    <div class="p-3 bg-light rounded-15 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-[10px] text-muted font-weight-bold uppercase">Invoice No</span>
                            <span class="text-[10px] text-muted font-weight-bold uppercase">Masa Aktif</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="font-weight-bold mb-0 text-dark">INV/{{ date('Ymd') }}/PRO/{{ rand(10000, 99999) }}</h6>
                            <h6 class="font-weight-bold mb-0 text-primary" id="modalDurationBadge">Bulanan (30 Hari)</h6>
                        </div>
                        <hr class="my-2 border-secondary" style="opacity: 0.1;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs text-muted font-weight-bold">Total Pembayaran:</span>
                            <h5 class="font-weight-bold text-success mb-0" id="modalFinalPrice">Rp {{ number_format($moduleData['price_monthly'], 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    
                    <!-- Coupon Promosi Input -->
                    <div class="mb-4">
                        <label class="text-xs font-weight-bold text-dark mb-1">🎫 Masukkan Kode Kupon (Jika Ada)</label>
                        <div class="input-group">
                            <input type="text" name="coupon_code" id="coupon_input" class="form-control text-uppercase" placeholder="Contoh: CBTGRATIS" style="border-radius: 10px 0 0 10px;">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success font-weight-bold px-3" onclick="applyCoupon()" style="border-radius: 0 10px 10px 0;">Terapkan</button>
                            </div>
                        </div>
                        <div class="mt-1 text-[10px] font-weight-bold d-none" id="couponFeedback"></div>
                    </div>
                    
                    <!-- Payment Methods -->
                    <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-university text-info mr-1"></i> Transfer Bank Resmi Pemilik</h6>
                    <div class="p-3 border rounded-15 bg-light mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs font-weight-bold text-dark">{{ $setting->owner_bank_name ?? 'BANK TRANSFER BCA' }}</span>
                            <span class="badge badge-info px-2 py-1 rounded text-[8px]">Rekening Resmi</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold text-dark mb-0">{{ $setting->owner_bank_account ?? '8392-1209-9021' }}</h5>
                            <button type="button" class="btn btn-xs btn-outline-primary px-2 rounded-pill" onclick="navigator.clipboard.writeText('{{ $setting->owner_bank_account ?? '8392-1209-9021' }}'); Swal.fire('Disalin', 'Nomor rekening berhasil disalin!', 'success')">Salin</button>
                        </div>
                        <div class="text-[9px] text-muted mt-1">Atas Nama: {{ $setting->owner_bank_holder ?? 'PT MARDIK DIGITAL INDONESIA' }}</div>
                    </div>

                    <!-- Payment QRIS -->
                    <div class="text-center mb-4">
                        <div class="d-inline-block p-2 bg-white border rounded-15 shadow-sm">
                            <img src="{{ $setting->owner_qris_path ?? 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https://mtsbustanulhuda.sch.id/payment-' . $module }}" alt="QRIS" class="img-fluid" style="width: 130px; height: 130px; object-fit: contain; border-radius: 8px;">
                            <div class="mt-1 text-[8px] font-weight-bold text-muted text-uppercase">Pindai QRIS Resmi Pemilik</div>
                        </div>
                    </div>
                    
                    <!-- Transfer Proof Upload Form -->
                    <div class="mb-3">
                        <label class="text-xs font-weight-bold text-dark mb-1">📸 Unggah Bukti Transfer Pembayaran</label>
                        <div class="custom-file">
                            <input type="file" name="transfer_proof" id="transfer_proof_input" class="custom-file-input" required onchange="updateFileNameLabel(this)">
                            <label class="custom-file-label" for="transfer_proof_input" id="fileInputLabel">Pilih file bukti transfer...</label>
                        </div>
                        <small class="text-[10px] text-muted mt-1 d-block">Format: JPG, PNG, PDF (Max: 2MB). Pastikan foto bukti transfer terlihat jelas.</small>
                    </div>

                    <!-- Payment Methods Hidden selection -->
                    <input type="hidden" name="payment_method" value="Transfer Bank BCA / QRIS">

                    <div class="alert alert-warning border-0 rounded-15 mb-0 d-flex align-items-start text-[10px] text-dark shadow-none" style="background-color: #fff9db;">
                        <i class="fas fa-info-circle mr-2 mt-1 text-warning"></i>
                        <div>
                            <strong>Simulasi Uji Coba:</strong> Pemilik dapat menyetujui atau menolak permintaan aktivasi ini secara instan di portal Developer Command Center. Unggah gambar apa saja untuk simulasi!
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4 pt-0 justify-content-center">
                    <button type="submit" id="btnSubmitCheckout" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold shadow-primary">
                        <i class="fas fa-paper-plane mr-2"></i> KIRIM BUKTI TRANSFER PEMBAYARAN
                    </button>
                    <!-- Developer simulation bypass button for quick testing -->
                    <button type="button" id="btnSimulateBypass" onclick="simulateInstantBypass()" class="btn btn-xs btn-link text-muted mt-2">
                        <i class="fas fa-magic mr-1"></i> Jalankan Simulasi Aktivasi Instan (Bypass Owner)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pure CSS Canvas-like Dynamic Particles for Celeb Success -->
<div id="particle-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; display: none;"></div>

<style>
    /* PREMIUM GLOW AND LOCK INTERFACE STYLES */
    .premium-lock-container {
        padding: 40px 0;
    }
    
    .premium-lock-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 25px;
        padding: 50px 40px;
        max-width: 760px;
        width: 100%;
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

    /* PENDING VERIFICATION PULSE */
    .verification-pulse-container {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .verification-icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        z-index: 2;
    }

    .verification-pulse {
        position: absolute;
        width: 80px;
        height: 80px;
        background: rgba(245, 158, 11, 0.25);
        border-radius: 50%;
        animation: pulseLock 2s infinite;
        z-index: 1;
    }

    .floating-icon {
        animation: floatLock 3s ease-in-out infinite;
    }

    /* DURATION PACK SELECTORS */
    .duration-card {
        background: #ffffff;
        border: 2px solid rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .duration-card:hover {
        transform: translateY(-4px);
        border-color: rgba(94, 114, 228, 0.3) !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }

    .duration-card.active {
        border-color: #5e72e4 !important;
        background: rgba(94, 114, 228, 0.03);
        box-shadow: 0 10px 25px rgba(94, 114, 228, 0.08);
    }

    .duration-card.active::after {
        content: '\f058';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 8px;
        left: 8px;
        color: #5e72e4;
        font-size: 14px;
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
    .cursor-pointer { cursor: pointer; }
    
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
    let selectedPackage = {
        duration: '30',
        price: {{ $moduleData['price_monthly'] }},
        finalPrice: {{ $moduleData['price_monthly'] }},
        discount: 0,
        couponCode: ''
    };

    function selectDuration(duration, price, element) {
        // Toggle active card
        $('.duration-card').removeClass('active');
        $(element).addClass('active');

        // Update package info
        selectedPackage.duration = duration;
        selectedPackage.price = price;
        
        // Reset coupon on duration change to ensure valid calculation
        selectedPackage.discount = 0;
        selectedPackage.couponCode = '';
        $('#coupon_input').val('');
        $('#couponFeedback').addClass('d-none');
        $('#originalPriceContainer').addClass('d-none');

        // Update labels
        let labelText = 'Investasi Paket Bulanan';
        if (duration === '365') labelText = 'Investasi Paket Tahunan';
        if (duration === 'lifetime') labelText = 'Investasi Paket Selamanya (Lifetime)';
        
        $('#durationLabel').text(labelText);
        
        // Calculate prices
        recalculateFinalPrices();
    }

    function recalculateFinalPrices() {
        selectedPackage.finalPrice = Math.max(0, selectedPackage.price - selectedPackage.discount);
        
        // Render prices
        const formattedFinal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(selectedPackage.finalPrice);
        $('#finalPriceLabel').text(formattedFinal);
        $('#modalFinalPrice').text(formattedFinal);

        // Render modal duration badge
        let durationText = 'Bulanan (30 Hari)';
        if (selectedPackage.duration === '365') durationText = 'Tahunan (365 Hari)';
        if (selectedPackage.duration === 'lifetime') durationText = 'Selamanya (Lifetime)';
        $('#modalDurationBadge').text(durationText);

        // Update hidden inputs
        $('#checkout_duration').val(selectedPackage.duration);
    }

    function applyCoupon() {
        const code = $('#coupon_input').val().trim().toUpperCase();
        if (!code) {
            Swal.fire('Peringatan', 'Masukkan kode kupon terlebih dahulu.', 'warning');
            return;
        }

        const feedback = $('#couponFeedback');
        feedback.removeClass('d-none text-success text-danger').addClass('text-info').html('<i class="fas fa-spinner fa-spin mr-1"></i> Memvalidasi kupon...');

        $.post('{{ route("seller.coupon.validate") }}', {
            _token: '{{ csrf_token() }}',
            code: code,
            price: selectedPackage.price
        })
        .done(res => {
            if (res.success) {
                selectedPackage.discount = res.discount;
                selectedPackage.couponCode = code;
                
                feedback.removeClass('text-info text-danger').addClass('text-success')
                    .html(`<i class="fas fa-check-circle mr-1"></i> Kupon ${code} berhasil diterapkan! Potongan Rp ${new Intl.NumberFormat('id-ID').format(res.discount)}`);
                
                // Show strikethrough original price
                $('#originalPriceContainer').removeClass('d-none');
                $('#originalPriceLabel').text(new Intl.NumberFormat('id-ID').format(selectedPackage.price));

                recalculateFinalPrices();
            } else {
                selectedPackage.discount = 0;
                selectedPackage.couponCode = '';
                feedback.removeClass('text-info text-success').addClass('text-danger').html(`<i class="fas fa-times-circle mr-1"></i> ${res.message}`);
                $('#originalPriceContainer').addClass('d-none');
                recalculateFinalPrices();
            }
        })
        .fail(xhr => {
            selectedPackage.discount = 0;
            selectedPackage.couponCode = '';
            feedback.removeClass('text-info text-success').addClass('text-danger').html('<i class="fas fa-times-circle mr-1"></i> Gagal memvalidasi kupon.');
            $('#originalPriceContainer').addClass('d-none');
            recalculateFinalPrices();
        });
    }

    function updateFileNameLabel(input) {
        const file = input.files[0];
        if (file) {
            $('#fileInputLabel').text(file.name);
        } else {
            $('#fileInputLabel').text('Pilih file bukti transfer...');
        }
    }

    function openPaymentModal() {
        recalculateFinalPrices();
        $('#modal-payment').modal('show');
    }

    function submitCheckoutForm(e) {
        e.preventDefault();
        
        const btn = $('#btnSubmitCheckout');
        const closeBtn = $('#closePaymentBtn');
        const bypassBtn = $('#btnSimulateBypass');
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> MENGIRIM BUKTI PEMBAYARAN...');
        closeBtn.prop('disabled', true);
        bypassBtn.addClass('d-none');

        const formData = new FormData(document.getElementById('checkoutForm'));
        
        $.ajax({
            url: '{{ route("admin.upgrade_module.checkout", $module) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#modal-payment').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Bukti Transfer Terkirim!',
                    text: res.message,
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#2dce89'
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan saat mengunggah bukti transfer.', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i> KIRIM BUKTI TRANSFER PEMBAYARAN');
                closeBtn.prop('disabled', false);
                bypassBtn.removeClass('d-none');
            }
        });
    }

    // Direct Instant simulation bypass (useful for owner or testing)
    function simulateInstantBypass() {
        const btn = $('#btnSimulateBypass');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menjalankan bypass...');

        $.post('{{ route("admin.upgrade_module.activate", $module) }}', {
            _token: '{{ csrf_token() }}'
        })
        .done(res => {
            $('#modal-payment').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Bypass Aktivasi Sukses!',
                text: res.message,
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = '{{ route($moduleData["redirect_route"]) }}';
            });
        })
        .fail(xhr => {
            Swal.fire('Gagal Bypass', xhr.responseJSON?.message || 'Terjadi kesalahan sistem.', 'error');
            btn.prop('disabled', false).html('<i class="fas fa-magic mr-1"></i> Jalankan Simulasi Aktivasi Instan (Bypass Owner)');
        });
    }
</script>
@endpush
