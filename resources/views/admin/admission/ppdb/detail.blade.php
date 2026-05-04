{{-- MODAL DETAIL PENDAFTAR PREMIUM --}}
<div class="modal fade animate__animated animate__fadeIn" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 25px; overflow: hidden;">
            <div class="modal-header bg-gradient-gold text-white border-0 py-3 px-4">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-id-card-alt mr-2"></i> Profil Lengkap Pendaftar
                </h5>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <div class="modal-body p-0 bg-light-soft">
                {{-- PREMIUM PROFILE HEADER --}}
                <div class="profile-banner-ppdb p-4 d-flex align-items-center position-relative overflow-hidden">
                    <div class="profile-avatar-wrapper mr-4 position-relative" style="z-index: 2;">
                        <img id="det_foto" src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}"
                            class="img-thumbnail rounded-circle shadow-lg" 
                            style="width:130px;height:130px;object-fit:cover;border: 5px solid rgba(255,255,255,0.8);">
                    </div>
                    <div class="text-white position-relative" style="z-index: 2;">
                        <h3 class="font-weight-bold mb-1 animate__animated animate__fadeInDown" id="det_nama">-</h3>
                        <div class="d-flex align-items-center mb-2 animate__animated animate__fadeInUp">
                            <span class="badge badge-gold-light px-3 py-2 font-weight-bold mr-2 shadow-sm" id="det_reg_no" style="font-size: 0.9rem;">-</span>
                            <span class="text-white-50 small font-weight-bold text-uppercase tracking-wider" id="det_asal">-</span>
                        </div>
                        <div class="d-flex" style="gap: 15px;">
                            <span class="small opacity-8"><i class="fas fa-venus-mars mr-1"></i> <span id="det_jk">-</span></span>
                            <span class="small opacity-8"><i class="fas fa-calendar-alt mr-1"></i> <span id="det_ttl">-</span></span>
                        </div>
                    </div>
                    <div class="banner-overlay"></div>
                </div>

                {{-- STATUS STEPPER PREMIUM --}}
                <div class="px-4 py-4 bg-white shadow-sm mb-4">
                    <div class="ppdb-modern-stepper" id="det_stepper">
                        <div class="step-item" id="step_1">
                            <div class="step-circle"><i class="fas fa-edit"></i></div>
                            <span class="step-text">Daftar</span>
                        </div>
                        <div class="step-connector" id="line_1"></div>
                        <div class="step-item" id="step_2">
                            <div class="step-circle"><i class="fas fa-user-check"></i></div>
                            <span class="step-text">Verifikasi</span>
                        </div>
                        <div class="step-connector" id="line_2"></div>
                        <div class="step-item" id="step_3">
                            <div class="step-circle"><i class="fas fa-tasks"></i></div>
                            <span class="step-text">Seleksi</span>
                        </div>
                        <div class="step-connector" id="line_3"></div>
                        <div class="step-item" id="step_4">
                            <div class="step-circle"><i class="fas fa-trophy"></i></div>
                            <span class="step-text">Hasil</span>
                        </div>
                    </div>
                </div>

                <div class="px-4 pb-4">
                    <div class="row">
                        {{-- DATA PRIBADI --}}
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm rounded-20 overflow-hidden h-100">
                                <div class="card-header bg-white py-3 border-0">
                                    <h6 class="font-weight-bold text-indigo mb-0"><i class="fas fa-user-circle mr-2"></i> Identitas Personal</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="premium-list">
                                        <div class="p-item">
                                            <span class="p-label">NISN</span>
                                            <span class="p-value" id="det_nisn">-</span>
                                        </div>
                                        <div class="p-item border-top">
                                            <span class="p-label">NIK</span>
                                            <span class="p-value" id="det_nik">-</span>
                                        </div>
                                        <div class="p-item border-top">
                                            <span class="p-label">WhatsApp</span>
                                            <span class="p-value text-success font-weight-bold" id="det_hp">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ORANG TUA --}}
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm rounded-20 overflow-hidden h-100">
                                <div class="card-header bg-white py-3 border-0">
                                    <h6 class="font-weight-bold text-orange mb-0"><i class="fas fa-users-cog mr-2"></i> Wali & Keluarga</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="premium-list">
                                        <div class="p-item">
                                            <span class="p-label">Nama Ayah</span>
                                            <span class="p-value" id="det_ayah">-</span>
                                        </div>
                                        <div class="p-item border-top">
                                            <span class="p-label">Nama Ibu</span>
                                            <span class="p-value" id="det_ibu">-</span>
                                        </div>
                                        <div class="p-item border-top">
                                            <span class="p-label">Alamat</span>
                                            <span class="p-value text-right" id="det_alamat" style="max-width: 180px; font-size: 0.75rem;">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PENDAFTARAN & VERIFIKASI --}}
                        <div class="col-md-12 mb-4">
                            <div class="card border-0 shadow-sm rounded-20 p-4 bg-white">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center border-right">
                                        <p class="text-xs font-weight-bold text-muted uppercase mb-1">Gelombang</p>
                                        <h5 class="font-weight-bold text-indigo mb-0" id="det_gelombang">-</h5>
                                    </div>
                                    <div class="col-md-3 text-center border-right">
                                        <p class="text-xs font-weight-bold text-muted uppercase mb-1">Jalur</p>
                                        <h5 class="font-weight-bold text-indigo mb-0" id="det_jalur">-</h5>
                                    </div>
                                    <div class="col-md-6 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3 p-2 bg-light rounded-circle"><i class="fas fa-user-shield text-warning"></i></div>
                                            <div>
                                                <p class="text-xs font-weight-bold text-muted uppercase mb-0">Diverifikasi Oleh</p>
                                                <span class="font-weight-bold text-dark" id="det_verifier">-</span>
                                                <small class="d-block text-muted" id="det_verified_at">-</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PEMBAYARAN DAFTAR ULANG --}}
                        <div class="col-md-12 mb-4 d-none" id="det_payment_section">
                            <div class="card border-0 shadow-sm rounded-20 overflow-hidden border-left-success">
                                <div class="card-body p-4 bg-soft-success">
                                    <div class="row align-items-center">
                                        <div class="col-md-3 text-center">
                                            <a id="det_payment_link" href="#" target="_blank" class="d-block position-relative">
                                                <img id="det_payment_img" src="" class="img-fluid rounded shadow-sm border" style="max-height: 120px;">
                                                <div class="zoom-overlay"><i class="fas fa-search-plus"></i></div>
                                            </a>
                                        </div>
                                        <div class="col-md-9">
                                            <h5 class="font-weight-bold text-success mb-1">Bukti Daftar Ulang</h5>
                                            <p class="text-muted small mb-3">Diunggah pada: <span id="det_payment_date" class="font-weight-bold text-dark">-</span></p>
                                            
                                            <div id="payment_status_verified" class="d-none">
                                                <div class="alert alert-success d-inline-block py-2 px-4 rounded-pill mb-0 shadow-sm border-0">
                                                    <i class="fas fa-check-double mr-2"></i> <span class="font-weight-bold">PEMBAYARAN SAH</span>
                                                </div>
                                            </div>

                                            <div id="payment_status_pending" class="d-none animate__animated animate__pulse animate__infinite">
                                                <button type="button" id="btn-verify-payment" class="btn btn-success btn-lg rounded-pill px-5 shadow-success font-weight-bold">
                                                    <i class="fas fa-check-circle mr-2"></i> VERIFIKASI SEKARANG
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- DOKUMEN BERKAS --}}
                        <div class="col-md-12">
                            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-folder-open mr-2 text-indigo"></i> Kelengkapan Berkas Digital</h6>
                            <div id="det_docs_container" class="row">
                                {{-- Berkas via JS --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-0 py-4 px-4">
                <a id="btn-print-letter" href="#" target="_blank" class="btn btn-indigo rounded-pill px-4 font-weight-bold shadow-indigo-light d-none">
                    <i class="fas fa-file-pdf mr-2"></i> CETAK SK HASIL
                </a>
                <button id="btn-move-student" type="button" class="btn btn-success rounded-pill px-4 font-weight-bold shadow-success d-none">
                    <i class="fas fa-user-check mr-2"></i> PINDAH KE DATA INDUK
                </button>
                <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold ml-auto" data-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Detail Styling */
    .bg-gradient-gold { background: linear-gradient(135deg, #b45309 0%, #78350f 100%) !important; }
    .bg-soft-success { background: #f0fdf4; }
    .border-left-success { border-left: 5px solid #22c55e !important; }
    .text-orange { color: #f97316; }
    .tracking-wider { letter-spacing: 0.1em; }
    
    .profile-banner-ppdb { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); min-height: 180px; }
    .banner-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1; }
    
    .badge-gold-light { background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }
    
    /* Stepper Styling */
    .ppdb-modern-stepper { display: flex; align-items: center; justify-content: space-between; position: relative; }
    .step-item { display: flex; flex-direction: column; align-items: center; z-index: 2; position: relative; }
    .step-circle { 
        width: 45px; height: 45px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; 
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 3px solid #fff; box-shadow: 0 0 0 2px #f1f5f9;
    }
    .step-text { font-size: 0.7rem; font-weight: 800; color: #94a3b8; margin-top: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
    .step-connector { height: 4px; background: #f1f5f9; flex-grow: 1; margin: 0 -15px; margin-top: -25px; transition: all 0.4s ease; border-radius: 2px; }
    
    .step-item.active .step-circle { background: #4f46e5; color: #fff; box-shadow: 0 0 15px rgba(79, 70, 229, 0.5); }
    .step-item.active .step-text { color: #4f46e5; }
    .step-item.success .step-circle { background: #10b981; color: #fff; box-shadow: 0 0 15px rgba(16, 185, 129, 0.5); }
    .step-item.danger .step-circle { background: #ef4444; color: #fff; box-shadow: 0 0 15px rgba(239, 68, 68, 0.5); }
    .step-connector.active { background: #4f46e5; }

    /* List Premium */
    .premium-list .p-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; }
    .p-label { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
    .p-value { font-weight: 700; color: #1e293b; font-size: 0.9rem; }

    .zoom-overlay { 
        position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); 
        display: none; align-items: center; justify-content: center; color: #fff; border-radius: 4px;
    }
    .ppdb-glass-card:hover .zoom-overlay { display: flex; }
</style>
