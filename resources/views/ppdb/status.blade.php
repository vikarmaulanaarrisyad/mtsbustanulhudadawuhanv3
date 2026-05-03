@php
    $docTypes = \App\Models\PpdbRegistrant::DOCUMENT_TYPES;
    $optionalTypes = ['kip']; // Berkas opsional, tidak wajib
    $requiredCount = count(array_diff_key($docTypes, array_flip($optionalTypes)));
    $uploadedRequired = $registrant->documents->whereNotIn('document_type', $optionalTypes)->count();
    $uploadedCount = $registrant->documents->count(); // Total semua (termasuk opsional)
    $isAllUploaded = $uploadedRequired >= $requiredCount;
@endphp

{{-- STEPPER PENDAFTARAN --}}
<div class="ppdb-card mb-4">
    <div class="card-body">
        <div class="registration-stepper">
            {{-- STEP 1: IDENTITAS --}}
            <div class="step active">
                <div class="step-num"><i class="fas fa-check"></i></div>
                <div class="step-text">Identitas Diri</div>
            </div>
            <div class="step-line active"></div>

            {{-- STEP 2: UNGGAH BERKAS --}}
            <div class="step active">
                <div class="step-num {{ $isAllUploaded ? 'bg-success' : '' }}">
                    @if($isAllUploaded)
                        <i class="fas fa-check"></i>
                    @else
                        2
                    @endif
                </div>
                <div class="step-text {{ $isAllUploaded ? 'text-success' : '' }}">Unggah Berkas</div>
            </div>
            <div class="step-line {{ $isAllUploaded ? 'active' : '' }}"></div>

            {{-- STEP 3: SELESAI --}}
            <div class="step {{ $isAllUploaded ? 'active wait-verif' : '' }}">
                <div class="step-num">
                    @if($isAllUploaded && $registrant->status === 'pending')
                        <i class="fas fa-clock fa-spin-slow"></i>
                    @else
                        3
                    @endif
                </div>
                <div class="step-text">Menunggu Verifikasi</div>
            </div>
        </div>
    </div>
</div>

<style>
    .fa-spin-slow {
        animation: fa-spin 3s infinite linear;
    }
    .registration-stepper .step.wait-verif .step-num {
        background: #f59e0b; /* Warna emas/warning */
        color: white;
        box-shadow: 0 0 0 1px #f59e0b;
        animation: pulse-yellow 2s infinite;
    }
    .registration-stepper .step.wait-verif .step-text {
        color: #f59e0b;
        font-weight: 700;
    }
    @keyframes pulse-yellow {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    .registration-stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 0;
    }
    .registration-stepper .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
    }
    .registration-stepper .step-num {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
        transition: all 0.3s;
        border: 4px solid #fff;
        box-shadow: 0 0 0 1px #e2e8f0;
        font-size: 0.9rem;
    }
    .registration-stepper .step-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
    }
    .registration-stepper .step-line {
        flex: 0.5;
        height: 2px;
        background: #e2e8f0;
        margin-top: -22px;
        max-width: 100px;
    }
    .registration-stepper .step.active .step-num {
        background: #10b981;
        color: white;
        box-shadow: 0 0 0 1px #10b981;
    }
    .registration-stepper .step.active .step-text {
        color: #10b981;
    }
    .registration-stepper .step-line.active {
        background: #10b981;
    }
</style>

{{-- STATUS CARD & TOMBOL CETAK HANYA MUNCUL JIKA BERKAS LENGKAP --}}
@if($isAllUploaded)
    {{-- STATUS CARD --}}
    <div class="status-card {{ $registrant->status }} mb-4">
        @if($registrant->status === 'pending')
            <i class="fas fa-history fa-2x mb-3"></i>
        @elseif($registrant->status === 'berkas_lengkap')
            <i class="fas fa-folder-open fa-2x mb-3"></i>
        @elseif($registrant->status === 'berkas_tidak_lengkap')
            <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
        @elseif($registrant->status === 'diterima')
            <i class="fas fa-check-double fa-2x mb-3"></i>
        @elseif($registrant->status === 'daftar_ulang' || $registrant->status === 'daftar_ulang_terverifikasi')
            <i class="fas fa-user-check fa-2x mb-3"></i>
        @elseif($registrant->status === 'cadangan')
            <i class="fas fa-clock fa-2x mb-3"></i>
        @elseif($registrant->status === 'ditolak')
            <i class="fas fa-times-circle fa-2x mb-3"></i>
        @endif
        <h3 class="mb-1">{{ $registrant->public_status_label }}</h3>
        <p class="mb-0 font-weight-normal opacity-75">Nomor Registrasi: <span class="font-weight-bold">{{ $registrant->registration_number }}</span></p>
    </div>

    {{-- TOMBOL CETAK --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="ppdb-card h-100 mb-0 border-left-success">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success-light p-3 rounded-circle mr-3">
                        <i class="fas fa-id-card fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold mb-1">Bukti Pendaftaran</h6>
                        <p class="small text-muted mb-2">Gunakan sebagai kartu tanda pengenal pendaftar.</p>
                        <a href="{{ route('ppdb.print_registration') }}" class="btn btn-sm btn-success px-3 shadow-sm">
                            <i class="fas fa-download mr-1"></i> Unduh Kartu
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="ppdb-card h-100 mb-0 border-left-primary">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary-light p-3 rounded-circle mr-3">
                        <i class="fas fa-file-signature fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold mb-1">Lembar Verifikasi</h6>
                        <p class="small text-muted mb-2">Bawa saat verifikasi berkas fisik di sekolah.</p>
                        <a href="{{ route('ppdb.print_verification') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                            <i class="fas fa-print mr-1"></i> Cetak Lembar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($registrant->status === 'diterima' && $isAnnouncementActive)
        <div class="ppdb-card border-left-success shadow-sm mb-4 animate__animated animate__pulse animate__infinite animate__slow">
            <div class="card-body">
                <div class="d-md-flex align-items-center justify-content-between">
                    <div class="mb-3 mb-md-0">
                        <h5 class="font-weight-bold text-success mb-1"><i class="fas fa-award mr-2"></i>Selamat! Anda Dinyatakan Lulus</h5>
                        <p class="mb-0 text-muted">Silakan unduh Surat Keterangan Kelulusan (SK) resmi Anda di sini.</p>
                    </div>
                    <a href="{{ route('ppdb.print_letter', $registrant->id) }}" class="btn btn-success btn-lg px-4 shadow">
                        <i class="fas fa-file-pdf mr-2"></i> Cetak SK Kelulusan
                    </a>
                </div>
            </div>
        </div>

        {{-- RE-REGISTRATION FORM --}}
        <div class="ppdb-card border-top border-primary shadow-sm mb-4" style="border-top-width: 4px !important;">
            <div class="card-header bg-white">
                <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-file-invoice-dollar mr-2"></i> Konfirmasi Daftar Ulang</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Silakan lakukan pembayaran biaya daftar ulang sesuai rincian di bawah ini untuk proses validasi akhir.</p>
                
                @if($paymentItems->count() > 0)
                    <div class="billing-invoice mb-4 shadow-sm border rounded-lg overflow-hidden">
                        <div class="billing-header bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                            <span class="text-uppercase font-weight-bold small text-muted letter-spacing-1">Rincian Tagihan</span>
                            <span class="badge badge-primary px-3">Daftar Ulang</span>
                        </div>
                        <div class="billing-body p-0">
                            @php $totalAmount = 0; @endphp
                            @foreach($paymentItems as $item)
                                <div class="billing-item d-flex align-items-center justify-content-between p-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="billing-icon bg-primary-light text-primary rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-check-circle small"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $item->item_name }}</div>
                                            @if($item->description)
                                                <div class="text-xs text-muted">{{ $item->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right font-weight-bold text-dark">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </div>
                                </div>
                                @php $totalAmount += $item->amount; @endphp
                            @endforeach
                        </div>
                        <div class="billing-footer bg-primary text-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-uppercase text-xs opacity-75 font-weight-bold mb-1">Total yang harus dibayar</div>
                                    <h3 class="font-weight-bold mb-0">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h3>
                                </div>
                                <i class="fas fa-file-invoice-dollar fa-3x opacity-25"></i>
                            </div>
                        </div>
                    </div>

                    <style>
                        .letter-spacing-1 { letter-spacing: 1px; }
                        .billing-invoice { background: #fff; }
                        .billing-item:hover { background-color: #f8fafc; transition: 0.3s; }
                        .bg-primary-light { background-color: rgba(0, 123, 255, 0.1); }
                        .text-xs { font-size: 0.75rem; }
                        .rounded-lg { border-radius: 12px !important; }
                    </style>
                @endif
                
                @php
                    $setting = \App\Models\Setting::first();
                    $clientKey = $setting->midtrans_client_key ?? '';
                    $isProduction = $setting->midtrans_is_production ?? false;
                    $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
                @endphp

                @if($registrant->payment_method === 'midtrans' && $registrant->midtrans_snap_token && in_array($registrant->payment_status, ['unpaid', 'pending']))
                    <div class="alert alert-warning border-0 shadow-sm p-4 rounded-xl" style="background: #fff; border-left: 5px solid #ffc107 !important;">
                        <h6 class="font-weight-bold text-warning mb-2"><i class="fas fa-exclamation-circle mr-2"></i> Menunggu Pembayaran</h6>
                        <p class="mb-3 text-muted">Silakan klik tombol di bawah ini untuk menyelesaikan pembayaran Anda melalui gateway aman Midtrans.</p>
                        <button id="pay-button" class="btn btn-warning shadow-sm font-weight-bold px-4">
                            <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                        </button>
                    </div>

                    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
                    <script type="text/javascript">
                        document.getElementById('pay-button').onclick = function(){
                            snap.pay('{{ $registrant->midtrans_snap_token }}', {
                                onSuccess: function(result){
                                    Swal.fire('Berhasil!', 'Pembayaran Anda telah berhasil diproses.', 'success').then(() => {
                                        window.location.reload();
                                    });
                                },
                                onPending: function(result){
                                    Swal.fire('Menunggu', 'Menunggu pembayaran Anda.', 'info').then(() => {
                                        window.location.reload();
                                    });
                                },
                                onError: function(result){
                                    Swal.fire('Gagal!', 'Pembayaran gagal.', 'error');
                                },
                                onClose: function(){
                                    Swal.fire('Perhatian', 'Anda menutup popup tanpa menyelesaikan pembayaran', 'warning');
                                }
                            });
                        };
                    </script>
                @else
                    <form action="{{ route('ppdb.confirm_re_registration') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Pilih Metode Pembayaran:</label>
                            <div class="row mt-2">
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-radio p-3 border rounded shadow-sm text-center payment-option h-100">
                                        <input type="radio" id="method_transfer" name="payment_method" class="custom-control-input" value="transfer" checked onchange="togglePaymentForm()">
                                        <label class="custom-control-label w-100 d-block cursor-pointer" for="method_transfer">
                                            <i class="fas fa-university fa-2x text-primary mb-2 d-block"></i>
                                            <span class="font-weight-bold d-block">Transfer Manual</span>
                                            <small class="text-muted d-block mt-1">Upload bukti transfer</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-radio p-3 border rounded shadow-sm text-center payment-option h-100">
                                        <input type="radio" id="method_tunai" name="payment_method" class="custom-control-input" value="tunai" onchange="togglePaymentForm()">
                                        <label class="custom-control-label w-100 d-block cursor-pointer" for="method_tunai">
                                            <i class="fas fa-money-bill-wave fa-2x text-success mb-2 d-block"></i>
                                            <span class="font-weight-bold d-block">Bayar Tunai</span>
                                            <small class="text-muted d-block mt-1">Bayar langsung di sekolah</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="custom-control custom-radio p-3 border rounded shadow-sm text-center payment-option h-100">
                                        <input type="radio" id="method_midtrans" name="payment_method" class="custom-control-input" value="midtrans" onchange="togglePaymentForm()">
                                        <label class="custom-control-label w-100 d-block cursor-pointer" for="method_midtrans">
                                            <i class="fas fa-credit-card fa-2x text-warning mb-2 d-block"></i>
                                            <span class="font-weight-bold d-block">Otomatis (Midtrans)</span>
                                            <small class="text-muted d-block mt-1">VA, E-Wallet, QRIS</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="transfer_section" class="payment-section p-3 bg-light rounded border mb-3">
                            @if($setting->bank_name && $setting->bank_account_number)
                            <div class="alert alert-info border-0 shadow-sm p-3 mb-3 rounded" style="background-color: #e8f4fd; border-left: 4px solid #007bff !important;">
                                <h6 class="font-weight-bold text-primary mb-2"><i class="fas fa-university mr-1"></i> Informasi Rekening Transfer:</h6>
                                <p class="mb-1 text-dark">Bank: <strong>{{ $setting->bank_name }}</strong></p>
                                <p class="mb-1 text-dark">No. Rekening: <strong class="text-primary" style="font-size: 1.1em; letter-spacing: 1px;">{{ $setting->bank_account_number }}</strong></p>
                                @if($setting->bank_account_name)
                                <p class="mb-0 text-dark">Atas Nama: <strong>{{ $setting->bank_account_name }}</strong></p>
                                @endif
                                <hr class="my-2 border-info" style="opacity: 0.3;">
                                <small class="text-muted">Total Tagihan: <strong class="text-danger">Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong></small>
                            </div>
                            @endif

                            <div class="form-group mb-0">
                                <label class="font-weight-bold small">Unggah Bukti Pembayaran (JPG/PNG, Max 5MB):</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof">
                                    <label class="custom-file-label" for="payment_proof">Pilih File...</label>
                                    @error('payment_proof')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="tunai_section" class="payment-section p-3 bg-light rounded border mb-3" style="display:none;">
                            <div class="d-flex align-items-start text-success">
                                <i class="fas fa-info-circle mt-1 mr-2"></i>
                                <p class="mb-0 text-sm">Dengan memilih metode Tunai, status Anda akan berubah menjadi Menunggu Verifikasi. Silakan datang ke sekolah untuk melakukan pembayaran tunai ke panitia.</p>
                            </div>
                        </div>
                        
                        <div id="midtrans_section" class="payment-section p-3 bg-light rounded border mb-3" style="display:none;">
                            <div class="d-flex align-items-start text-warning">
                                <i class="fas fa-info-circle mt-1 mr-2"></i>
                                <p class="mb-0 text-sm">Pembayaran otomatis diverifikasi 24/7. Anda akan diarahkan untuk memilih metode pembayaran melalui sistem Midtrans setelah menekan tombol Lanjut Pembayaran.</p>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" id="submit_btn" class="btn btn-primary shadow-sm px-4">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Konfirmasi
                            </button>
                        </div>
                    </form>
                    
                    <style>
                        .payment-option { cursor: pointer; transition: all 0.2s; }
                        .payment-option:hover { border-color: #007bff !important; background-color: #f8f9fa; }
                        .payment-option .custom-control-label::before,
                        .payment-option .custom-control-label::after { display: none !important; }
                        .payment-option input:checked + label { color: #007bff; }
                        .payment-option input:checked { border-color: #007bff; }
                        .payment-option:has(input:checked) { border-color: #007bff !important; border-width: 2px !important; background-color: #f0f7ff; }
                    </style>
                    <script>
                        function togglePaymentForm() {
                            let method = document.querySelector('input[name="payment_method"]:checked').value;
                            document.querySelectorAll('.payment-section').forEach(el => el.style.display = 'none');
                            document.getElementById(method + '_section').style.display = 'block';
                            
                            let proofInput = document.getElementById('payment_proof');
                            let btn = document.getElementById('submit_btn');
                            
                            if (method === 'transfer') {
                                proofInput.setAttribute('required', 'required');
                                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Konfirmasi';
                            } else if (method === 'tunai') {
                                proofInput.removeAttribute('required');
                                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Konfirmasi Tunai';
                            } else if (method === 'midtrans') {
                                proofInput.removeAttribute('required');
                                btn.innerHTML = '<i class="fas fa-credit-card mr-2"></i> Lanjut Pembayaran';
                            }
                        }
                        
                        // Initialize state on load
                        document.addEventListener('DOMContentLoaded', function() {
                            togglePaymentForm();
                        });
                    </script>
                @endif
            </div>
        </div>
    @elseif($registrant->status === 'daftar_ulang')
        @if($registrant->payment_method === 'midtrans' && in_array($registrant->payment_status, ['unpaid', 'pending']))
            <div class="alert alert-warning border-0 shadow-sm p-4 rounded-xl mb-4" style="background: #fff; border-left: 5px solid #ffc107 !important;">
                <h6 class="font-weight-bold text-warning mb-2"><i class="fas fa-exclamation-circle mr-2"></i> Menunggu Pembayaran Midtrans</h6>
                <p class="mb-3 text-muted">Silakan klik tombol di bawah ini untuk menyelesaikan atau melihat instruksi pembayaran Anda melalui gateway aman Midtrans.</p>
                <div class="d-flex flex-wrap" style="gap:10px;">
                    <button id="pay-button" class="btn btn-warning shadow-sm font-weight-bold px-4">
                        <i class="fas fa-credit-card mr-2"></i> Lanjutkan Pembayaran
                    </button>
                    <a href="{{ route('ppdb.print_payment') }}" class="btn btn-outline-warning shadow-sm px-3">
                        <i class="fas fa-file-invoice mr-1"></i> Unduh Invoice
                    </a>
                </div>
            </div>
            
            @php
                $setting = \App\Models\Setting::first();
                $clientKey = $setting->midtrans_client_key ?? '';
                $isProduction = $setting->midtrans_is_production ?? false;
                $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
            @endphp
            <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
            <script type="text/javascript">
                document.getElementById('pay-button').onclick = function(){
                    snap.pay('{{ $registrant->midtrans_snap_token }}', {
                        onSuccess: function(result){
                            Swal.fire({ title: 'Memverifikasi...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                            fetch('{{ route('ppdb.verify_midtrans') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(res => res.json()).then(data => {
                                Swal.fire('Berhasil!', 'Pembayaran Anda telah berhasil diproses.', 'success').then(() => { window.location.reload(); });
                            }).catch(() => {
                                Swal.fire('Info', 'Pembayaran diterima, memproses sinkronisasi data...', 'info').then(() => { window.location.reload(); });
                            });
                        },
                        onPending: function(result){
                            Swal.fire('Menunggu', 'Menunggu pembayaran Anda.', 'info').then(() => { window.location.reload(); });
                        },
                        onError: function(result){
                            Swal.fire('Gagal!', 'Pembayaran gagal.', 'error');
                        },
                        onClose: function(){
                            Swal.fire('Perhatian', 'Anda menutup popup tanpa menyelesaikan pembayaran', 'warning');
                        }
                    });
                };
            </script>
        @else
            <div class="alert alert-info border-0 shadow-sm mb-4 p-4 rounded-xl" style="background: #fff; border-left: 5px solid #007bff !important;">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-light p-3 rounded-circle mr-3">
                        <i class="fas fa-clock fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-primary mb-1">Daftar Ulang Sedang Diverifikasi</h6>
                        <p class="mb-0 text-muted">Instruksi pembayaran Anda telah kami terima dan sedang dalam proses verifikasi oleh panitia. Mohon tunggu informasi selanjutnya.</p>
                        <div class="mt-2">
                            <a href="{{ route('ppdb.print_payment') }}" class="btn btn-primary btn-sm shadow-sm px-3">
                                <i class="fas fa-file-invoice mr-1"></i> Unduh Bukti Pembayaran Sementara
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @elseif($registrant->status === 'daftar_ulang_terverifikasi')
        <div class="alert alert-success border-0 shadow-sm mb-4 p-4 rounded-xl" style="background: #fff; border-left: 5px solid #28a745 !important;">
            <div class="d-flex align-items-center">
                <div class="bg-success-light p-3 rounded-circle mr-3">
                    <i class="fas fa-user-check fa-2x text-success"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-success mb-1">Daftar Ulang Terverifikasi</h6>
                    <p class="mb-0 text-muted">Selamat! Pembayaran daftar ulang Anda telah diverifikasi. Anda kini resmi menjadi bagian dari keluarga besar kami. Silakan tunggu informasi pembagian kelas dan jadwal masuk.</p>
                    <div class="mt-2 d-flex flex-wrap" style="gap:10px;">
                        <a href="{{ route('ppdb.print_payment') }}" class="btn btn-outline-success btn-sm px-3">
                            <i class="fas fa-file-invoice mr-1"></i> Kwitansi Lunas
                        </a>
                        <a href="{{ route('ppdb.print_re_registration') }}" class="btn btn-success btn-sm shadow-sm px-3">
                            <i class="fas fa-download mr-1"></i> Unduh Bukti Daftar Ulang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif($registrant->status === 'berkas_lengkap')
        {{-- STATUS: BERKAS LENGKAP & TERVERIFIKASI --}}
        <div class="ppdb-status-card success-theme animate-in">
            <div class="card-glow"></div>
            <div class="d-flex align-items-center position-relative">
                <div class="status-icon-wrapper">
                    <i class="fas fa-file-invoice text-success"></i>
                    <div class="icon-pulse"></div>
                </div>
                <div class="flex-grow-1">
                    <div class="status-badge-mini mb-2">Pendaftaran Terverifikasi</div>
                    <h5 class="status-title mb-1">Alhamdulillah, Berkas Lengkap!</h5>
                    <p class="status-desc mb-3">Seluruh dokumen Anda telah diperiksa oleh panitia dan dinyatakan valid. Saat ini Anda sedang dalam tahap seleksi.</p>
                    
                    <div class="announcement-glass">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-calendar-star mr-2 text-success"></i>
                            <span class="announcement-label">Jadwal Pengumuman</span>
                        </div>
                        <div class="announcement-date">
                            @if($registrant->admissionPhase && $registrant->admissionPhase->announcement_date)
                                {{ $registrant->admissionPhase->announcement_date->format('d F Y') }}
                            @elseif(isset($admission) && $admission->announcement_date)
                                {{ $admission->announcement_date->format('d F Y') }}
                            @else
                                <span class="text-muted italic" style="font-size: 0.9rem;">Segera Diumumkan</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($registrant->status === 'diterima' && !$isAnnouncementActive)
        {{-- STATUS: DITERIMA TAPI BELUM PENGUMUMAN --}}
        <div class="ppdb-status-card info-theme animate-in">
            <div class="card-glow"></div>
            <div class="d-flex align-items-center position-relative">
                <div class="status-icon-wrapper">
                    <i class="fas fa-hourglass-half text-info"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="status-badge-mini mb-2" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-color: rgba(59, 130, 246, 0.2);">Tahap Seleksi</div>
                    <h5 class="status-title mb-1">Hasil Seleksi Sedang Diproses</h5>
                    <p class="status-desc mb-3">Panitia sedang melakukan finalisasi data. Hasil seleksi akhir akan dibuka secara serentak pada:</p>
                    
                    <div class="announcement-glass" style="background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.1);">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-clock mr-2 text-info"></i>
                            <span class="announcement-label" style="color: #3b82f6;">Waktu Pengumuman</span>
                        </div>
                        <div class="announcement-date">
                            @if($registrant->admissionPhase && $registrant->admissionPhase->announcement_date)
                                {{ $registrant->admissionPhase->announcement_date->format('d F Y') }}
                            @elseif(isset($admission) && $admission->announcement_date)
                                {{ $admission->announcement_date->format('d F Y') }}
                            @else
                                Segera Diumumkan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    {{-- PESAN INSTRUKSI JIKA BELUM LENGKAP --}}
    <div class="ppdb-status-card warning-theme animate-in">
        <div class="d-flex flex-column align-items-center text-center p-2">
            <div class="status-icon-wrapper mb-3" style="width: 70px; height: 70px; font-size: 1.8rem; background: rgba(245, 158, 11, 0.1);">
                <i class="fas fa-exclamation-triangle text-warning"></i>
            </div>
            <h5 class="status-title mb-2">Berkas Belum Lengkap!</h5>
            <p class="status-desc max-width-500 mb-0">Silakan lengkapi semua unggahan berkas di bawah ini untuk mendapatkan <strong>Nomor Registrasi</strong> dan mengunduh <strong>Bukti Pendaftaran</strong>.</p>
        </div>
    </div>
@endif

<style>
    .ppdb-status-card {
        position: relative;
        padding: 24px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .card-glow {
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        filter: blur(50px);
        opacity: 0.15;
        z-index: 0;
    }
    .success-theme { border-left: 6px solid #10b981; }
    .success-theme .card-glow { background: #10b981; }
    
    .info-theme { border-left: 6px solid #3b82f6; }
    .info-theme .card-glow { background: #3b82f6; }
    
    .warning-theme { border-left: 6px solid #f59e0b; }
    .warning-theme .card-glow { background: #f59e0b; }

    .status-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 20px;
        position: relative;
        background: rgba(248, 250, 252, 1);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    
    .icon-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        border: 2px solid #10b981;
        opacity: 0;
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 0.5; }
        100% { transform: scale(1.3); opacity: 0; }
    }

    .status-badge-mini {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-title {
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.5px;
    }

    .status-desc {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .announcement-glass {
        background: rgba(16, 185, 129, 0.05);
        border: 1px solid rgba(16, 185, 129, 0.1);
        border-radius: 12px;
        padding: 12px 16px;
        display: inline-block;
    }

    .announcement-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #059669;
    }

    .announcement-date {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e293b;
    }

    .max-width-500 { max-width: 500px; }

    .animate-in {
        animation: slideUp 0.6s ease-out forwards;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

{{-- CATATAN VERIFIKASI --}}
@if($registrant->catatan_verifikasi)
    <div class="alert alert-{{ $registrant->status_color }} border-0 shadow-sm mb-4 p-4 rounded-xl" style="background: #fff; border-left: 5px solid currentColor !important;">
        <div class="d-flex align-items-start">
            <i class="fas fa-comment-dots mt-1 mr-3 fa-lg"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Catatan dari Verifikator:</h6>
                <div class="text-dark">{{ $registrant->catatan_verifikasi }}</div>
            </div>
        </div>
    </div>
@endif

{{-- UNGGAH BERKAS SATU-PER-SATU (Hanya tampil jika belum verifikasi lengkap) --}}
@if(!$ppdbOpen && !in_array($registrant->status, ['berkas_lengkap', 'diterima', 'daftar_ulang', 'daftar_ulang_terverifikasi', 'sudah_masuk_siswa']))
    <div class="alert alert-danger border-0 shadow-sm mb-4 p-4 rounded-xl" style="background: #fff; border-left: 5px solid #dc3545 !important;">
        <h6 class="font-weight-bold text-danger mb-2"><i class="fas fa-lock mr-2"></i> Pendaftaran Telah Ditutup</h6>
        <p class="mb-0 text-muted">Masa pendaftaran dan perbaikan berkas telah ditutup. Anda tidak dapat lagi mengunggah atau mengubah dokumen.</p>
    </div>
@elseif(!in_array($registrant->status, ['berkas_lengkap', 'diterima', 'daftar_ulang', 'daftar_ulang_terverifikasi', 'ditolak', 'sudah_masuk_siswa']))
    <div class="ppdb-card mb-4" style="border-top: 4px solid #007bff;">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="font-weight-bold mb-1"><i class="fas fa-cloud-upload-alt mr-2 text-primary"></i> Unggah Berkas Persyaratan</h6>
                <small class="text-muted">Lengkapi semua dokumen untuk melanjutkan proses seleksi</small>
            </div>
            <div class="text-right">
                <span class="badge badge-pill {{ $isAllUploaded ? 'badge-success' : 'badge-primary' }} px-3 py-2" style="font-size: 0.85rem;">
                    <i class="fas {{ $isAllUploaded ? 'fa-check-circle' : 'fa-tasks' }} mr-1"></i>
                    {{ $uploadedRequired }}/{{ $requiredCount }}
                </span>
                <div class="progress mt-2" style="height: 6px; width: 80px; border-radius: 10px; margin-left: auto;">
                    <div class="progress-bar {{ $isAllUploaded ? 'bg-success' : 'bg-primary' }}" style="width: {{ $requiredCount > 0 ? round(($uploadedRequired/$requiredCount)*100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="p-3 rounded-lg mb-4" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 1px solid #c7d2fe;">
            <div class="d-flex align-items-start">
                <div class="mr-3 mt-1">
                    <div style="width:36px;height:36px;border-radius:50%;background:#6366f1;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                </div>
                <div class="small">
                    <strong class="text-dark">Cara Mengunggah:</strong>
                    <ol class="mb-0 pl-3 mt-1 text-secondary">
                        <li>Klik <strong>"Pilih berkas"</strong> pada dokumen yang ingin diunggah</li>
                        <li>Pilih file foto/scan dokumen dari perangkat Anda</li>
                        <li>Klik tombol <span class="badge badge-primary badge-sm"><i class="fas fa-upload"></i></span> untuk mengunggah</li>
                    </ol>
                    <div class="mt-1 text-muted"><i class="fas fa-file-image mr-1"></i> Format: <strong>JPG, PNG, PDF</strong> &bull; Maks: <strong>5MB</strong></div>
                </div>
            </div>
        </div>

        @php
            $existingDocs = $registrant->documents->pluck('file_path', 'document_type')->toArray();
            $docTypes = \App\Models\PpdbRegistrant::DOCUMENT_TYPES;
            $docMeta = [
                'akta_kelahiran'    => ['icon' => 'fa-baby',           'color' => '#ec4899', 'desc' => 'Scan/foto asli akta kelahiran anak'],
                'kartu_keluarga'    => ['icon' => 'fa-users',          'color' => '#8b5cf6', 'desc' => 'Scan KK yang masih berlaku'],
                'ijazah'            => ['icon' => 'fa-graduation-cap', 'color' => '#3b82f6', 'desc' => 'Ijazah atau Surat Keterangan Lulus (SKL)'],
                'skhun'             => ['icon' => 'fa-certificate',    'color' => '#f59e0b', 'desc' => 'SKHUN atau sertifikat hasil ujian terakhir'],
                'rapor'             => ['icon' => 'fa-book',           'color' => '#10b981', 'desc' => 'Rapor semester terakhir (halaman nilai)'],
                'foto'              => ['icon' => 'fa-camera',         'color' => '#6366f1', 'desc' => 'Pas foto 3x4 latar biru, format formal'],
                'kip'               => ['icon' => 'fa-id-badge',       'color' => '#64748b', 'desc' => 'Kartu Indonesia Pintar (jika memiliki)'],
                'surat_keterangan'  => ['icon' => 'fa-file-alt',       'color' => '#0ea5e9', 'desc' => 'Surat keterangan dari sekolah asal'],
            ];
        @endphp

        <div class="row">
            @foreach($docTypes as $type => $name)
                @php
                    $meta = $docMeta[$type] ?? ['icon' => 'fa-file-alt', 'color' => '#64748b', 'desc' => ''];
                    $isUploaded = isset($existingDocs[$type]);
                @endphp
                <div class="col-md-6 mb-3">
                    <div class="doc-upload-card {{ $isUploaded ? 'uploaded' : '' }}">
                        {{-- Header --}}
                        <div class="d-flex align-items-start mb-2">
                            <div class="doc-icon" style="background: {{ $isUploaded ? '#10b981' : $meta['color'] }};">
                                <i class="fas {{ $isUploaded ? 'fa-check' : $meta['icon'] }} text-white"></i>
                            </div>
                            <div class="ml-3 flex-grow-1">
                                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.9rem;">{{ $name }}</h6>
                                <small class="text-muted">{{ $meta['desc'] }}</small>
                                @if($type === 'kip')
                                    <span class="badge badge-light border ml-1" style="font-size:0.65rem;">Opsional</span>
                                @endif
                            </div>
                        </div>

                        {{-- Status --}}
                        @if($isUploaded)
                            <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded" style="background: rgba(16,185,129,0.08);">
                                <small class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Terunggah</small>
                                <a href="{{ Storage::url($existingDocs[$type]) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:0.75rem;">
                                    <i class="fas fa-eye mr-1"></i>Lihat
                                </a>
                            </div>
                        @endif

                        {{-- Upload Form --}}
                        <form onsubmit="handleUpload(event, '{{ $type }}')">
                            <div class="input-group input-group-sm">
                                <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="file_{{ $type }}" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <label class="custom-file-label text-truncate" for="file_{{ $type }}" style="font-size:0.8rem;">
                                        {{ $isUploaded ? 'Ganti berkas...' : 'Pilih berkas...' }}
                                    </label>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="btn {{ $isUploaded ? 'btn-outline-success' : 'btn-primary' }} px-3" id="btn_{{ $type }}">
                                        <i class="fas fa-upload"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="progress mt-2 d-none" id="progress_wrapper_{{ $type }}" style="height:5px;border-radius:10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress_{{ $type }}" style="width:0%;background:{{ $meta['color'] }};"></div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .doc-upload-card {
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        padding: 18px;
        transition: all 0.3s ease;
        background: #fafbfc;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .doc-upload-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #d1d5db, #e5e7eb);
        transition: all 0.3s;
    }
    .doc-upload-card:hover {
        border-color: #818cf8;
        background: #fafaff;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(99,102,241,0.12);
    }
    .doc-upload-card:hover::before {
        background: linear-gradient(90deg, #6366f1, #818cf8);
    }
    .doc-upload-card.uploaded {
        border: 2px solid #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    }
    .doc-upload-card.uploaded::before {
        background: linear-gradient(90deg, #10b981, #34d399);
    }
    .doc-upload-card.uploaded:hover {
        box-shadow: 0 8px 24px rgba(16,185,129,0.15);
        transform: translateY(-2px);
    }
    .doc-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .doc-icon i { font-size: 1rem; }
</style>

<script>
    function handleUpload(event, type) {
        event.preventDefault();
        const form = event.target;
        const fileInput = form.querySelector('input[type="file"]');
        const btn = document.getElementById('btn_' + type);
        const progressWrapper = document.getElementById('progress_wrapper_' + type);
        const progressBar = document.getElementById('progress_' + type);

        if (!fileInput.files.length) return;

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('type', type);
        formData.append('_token', '{{ csrf_token() }}');

        // UI Feedback
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        progressWrapper.classList.remove('d-none');
        progressBar.style.width = '0%';

        $.ajax({
            url: '{{ route("ppdb.upload_document") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            },
            error: function(xhr) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-upload"></i>';
                progressWrapper.classList.add('d-none');
                
                let msg = 'Gagal mengunggah berkas.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: msg
                });
            }
        });
    }

    // Update label custom-file
    $(document).on('change', '.custom-file-input', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>

{{-- DATA RINGKASAN --}}
<div class="ppdb-card">
    <div class="card-header">
        <i class="fas fa-user-shield mr-2"></i> Ringkasan Profil Pendaftar
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <div class="position-relative d-inline-block">
                    @if($registrant->foto)
                        <img src="{{ Storage::url($registrant->foto) }}" class="img-fluid rounded shadow"
                            style="width:140px;height:180px;object-fit:cover;border:4px solid #fff;">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center shadow-sm"
                            style="width:140px;height:180px;border:2px dashed #cbd5e0;">
                            <i class="fas fa-user-tie fa-4x text-light"></i>
                        </div>
                    @endif
                    <div class="mt-2">
                        <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm">Pendaftar Aktif</span>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Nama Lengkap</small>
                        <p class="mb-0 font-weight-bold text-dark">{{ $registrant->nama_lengkap }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">NISN / NIK</small>
                        <p class="mb-0 text-dark">{{ $registrant->nisn ?? '-' }} / {{ $registrant->nik ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Tempat, Tgl Lahir</small>
                        <p class="mb-0 text-dark">{{ $registrant->tempat_lahir }}, {{ $registrant->tanggal_lahir->format('d M Y') }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Asal Sekolah</small>
                        <p class="mb-0 text-dark">{{ $registrant->asal_sekolah ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Gelombang / Jalur</small>
                        <p class="mb-0 text-dark">{{ $registrant->admissionPhase->phase_name ?? '-' }} ({{ $registrant->admissionType->admission_type_name ?? '-' }})</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted text-uppercase font-weight-bold letter-spacing-1">Kontak Orang Tua</small>
                        <p class="mb-0 text-dark"><i class="fab fa-whatsapp text-success mr-1"></i> {{ $registrant->no_hp_ortu ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- BERKAS --}}
<div class="ppdb-card">
    <div class="card-header">
        <i class="fas fa-file-invoice mr-2"></i> Verifikasi Berkas Persyaratan
    </div>
    <div class="card-body p-0">
        @if($registrant->documents->count() > 0)
            @foreach($registrant->documents as $doc)
                <div class="doc-item">
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded mr-3">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <div>
                            <span class="doc-name">{{ $doc->document_name }}</span>
                            @if($doc->verification_note)
                                <br><small class="text-danger font-italic"><i class="fas fa-exclamation-circle mr-1"></i> {{ $doc->verification_note }}</small>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($doc->is_verified)
                            <span class="badge badge-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle mr-1"></i> Terverifikasi</span>
                        @else
                            <span class="badge badge-warning px-3 py-2 rounded-pill text-dark"><i class="fas fa-clock mr-1"></i> Sedang Ditinjau</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                <p class="mb-0">Belum ada dokumen yang diunggah ke sistem.</p>
            </div>
        @endif
    </div>
</div>

{{-- TOMBOL EDIT --}}
@if(in_array($registrant->status, ['pending', 'berkas_tidak_lengkap']))
    <div class="ppdb-card border-top border-warning" style="border-top-width: 4px !important;">
        <div class="card-body">
            <div class="d-md-flex align-items-center justify-content-between">
                <div class="mb-3 mb-md-0">
                    <h6 class="font-weight-bold text-dark mb-1">Perlu melakukan perubahan data?</h6>
                    <p class="mb-0 text-muted small">
                        @if($registrant->status === 'berkas_tidak_lengkap')
                            <i class="fas fa-exclamation-triangle text-warning mr-1"></i> Mohon segera lengkapi berkas Anda sesuai catatan verifikator.
                        @else
                            <i class="fas fa-info-circle text-info mr-1"></i> Data masih dapat diubah selama status pendaftaran masih dalam peninjauan.
                        @endif
                    </p>
                </div>
                <button type="button" class="btn btn-ppdb shadow-sm" id="btnShowEdit">
                    <i class="fas fa-pencil-alt mr-2"></i> Perbarui Data Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- FORM EDIT (hidden by default) --}}
    <div id="editFormContainer" style="display:none;" class="mt-4">
        @include('ppdb.form-biodata', [
            'action' => route('ppdb.update_biodata'),
            'method' => 'PUT',
            'registrant' => $registrant
        ])
    </div>

    @push('scripts')
    <script>
        $('#btnShowEdit').on('click', function() {
            $(this).closest('.ppdb-card').hide();
            $('#editFormContainer').slideDown(400);
            $('html, body').animate({ scrollTop: $('#editFormContainer').offset().top - 30 }, 600);
        });
    </script>
    @endpush
@endif    </div>
@endif

{{-- VERIFIKASI INFO (Hanya tampil jika BELUM masuk ke tahap seleksi akhir/lengkap agar tidak double info) --}}
@if($registrant->verified_at && !in_array($registrant->status, ['berkas_lengkap', 'diterima', 'daftar_ulang', 'daftar_ulang_terverifikasi']))
    <div class="ppdb-card mb-4 overflow-hidden" style="border: none;">
        <div class="p-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
            <div class="d-flex align-items-center">
                <div class="mr-3" style="width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-shield-alt text-white fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="text-white font-weight-bold mb-1" style="font-size:0.95rem;">
                        <i class="fas fa-check-circle text-success mr-1"></i> Terverifikasi oleh Sistem
                    </h6>
                    <div class="d-flex flex-wrap align-items-center" style="gap:12px;">
                        <span class="text-white-50 small">
                            <i class="fas fa-user-tie mr-1"></i> {{ $registrant->verifier->name ?? 'Admin Sistem' }}
                        </span>
                        <span class="text-white-50 small">
                            <i class="fas fa-calendar-check mr-1"></i> {{ $registrant->verified_at->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                </div>
                <span class="badge px-3 py-2" style="background:rgba(16,185,129,0.2);color:#34d399;border:1px solid rgba(16,185,129,0.3);border-radius:20px;">
                    <i class="fas fa-lock mr-1"></i> Valid
                </span>
            </div>
        </div>
    </div>
@endif
