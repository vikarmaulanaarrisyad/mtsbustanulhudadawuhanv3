<form action="{{ route('setting.update', $setting->id) }}?pills=premium" method="post" class="w-full">
    @csrf
    @method('PUT')
    
    <div class="card border-0 bg-transparent mb-0">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="font-weight-bold text-dark mb-1"><i class="fas fa-crown text-warning mr-2 animate__animated animate__jackInTheBox"></i> Manajemen Modul Premium PRO</h5>
                    <p class="text-muted text-xs mb-0">Aktifkan atau kunci modul premium berbayar secara manual di seluruh sistem sekolah.</p>
                </div>
            </div>

            <!-- PREMIUM MODULE: ROADMAP / WORKFLOW -->
            <div class="p-4 border mb-4 rounded-15 bg-white shadow-sm position-relative overflow-hidden premium-card-hover" style="transition: all 0.3s ease;">
                <div class="row align-items-center">
                    <div class="col-lg-8 d-flex align-items-start">
                        <div class="avatar-lg bg-soft-warning rounded-circle d-flex align-items-center justify-content-center mr-3 mt-1" style="width: 55px; height: 55px; min-width: 55px; background-color: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-map-marked-alt text-warning fa-2x"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="font-weight-bold mb-0 text-dark mr-2">Peta Jalan Admin (Workflow)</h6>
                                <span class="badge badge-warning text-[9px] font-weight-bold px-2 py-1 rounded-pill uppercase">PRO FEATURE</span>
                            </div>
                            <p class="text-xs text-muted mb-0">
                                Panduan langkah-demi-langkah kronologis yang komprehensif bagi kepala madrasah/staf TU untuk menyelesaikan tugas di semester ganjil & genap secara berurutan.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <div class="d-flex align-items-center justify-content-lg-end">
                            <span class="mr-3 font-weight-bold text-xs {{ $setting->is_workflow_pro_active ? 'text-success' : 'text-warning' }}">
                                <i class="fas {{ $setting->is_workflow_pro_active ? 'fa-check-circle' : 'fa-lock' }} mr-1"></i>
                                {{ $setting->is_workflow_pro_active ? 'AKTIF / TERBUKA' : 'TERKUNCI / BERBAYAR' }}
                            </span>
                            <!-- Beautiful Toggle Switch -->
                            <div class="custom-control custom-switch custom-switch-lg">
                                <input type="checkbox" class="custom-control-input" id="is_workflow_pro_active" name="is_workflow_pro_active" value="1" {{ $setting->is_workflow_pro_active ? 'checked' : '' }}>
                                <label class="custom-control-label cursor-pointer" for="is_workflow_pro_active"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FUTURE PREMIUM MODULE 2: ADVANCED STATS -->
            <div class="p-4 border mb-4 rounded-15 bg-white shadow-sm opacity-60" style="background-color: #fafafa;">
                <div class="row align-items-center">
                    <div class="col-lg-8 d-flex align-items-start">
                        <div class="avatar-lg bg-soft-info rounded-circle d-flex align-items-center justify-content-center mr-3 mt-1" style="width: 55px; height: 55px; min-width: 55px; background-color: rgba(14, 165, 233, 0.1);">
                            <i class="fas fa-chart-line text-info fa-2x"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="font-weight-bold mb-0 text-dark mr-2">Dashboard Statistik Lanjutan</h6>
                                <span class="badge badge-secondary text-[9px] font-weight-bold px-2 py-1 rounded-pill uppercase">SEGARA HADIR</span>
                            </div>
                            <p class="text-xs text-muted mb-0">
                                Visualisasi analitik data kesiswaan, tren kenaikan kelas, dan laporan keuangan komparatif interaktif berbasis grafis modern.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <div class="d-flex align-items-center justify-content-lg-end">
                            <span class="mr-3 font-weight-bold text-xs text-muted">
                                <i class="fas fa-clock mr-1"></i> COOMING SOON
                            </span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="module_stats_mock" disabled>
                                <label class="custom-control-label" for="module_stats_mock"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FUTURE PREMIUM MODULE 3: QRIS AUTOMATION -->
            <div class="p-4 border mb-4 rounded-15 bg-white shadow-sm opacity-60" style="background-color: #fafafa;">
                <div class="row align-items-center">
                    <div class="col-lg-8 d-flex align-items-start">
                        <div class="avatar-lg bg-soft-success rounded-circle d-flex align-items-center justify-content-center mr-3 mt-1" style="width: 55px; height: 55px; min-width: 55px; background-color: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-qrcode text-success fa-2x"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="font-weight-bold mb-0 text-dark mr-2">Otomatisasi Pembayaran QRIS</h6>
                                <span class="badge badge-secondary text-[9px] font-weight-bold px-2 py-1 rounded-pill uppercase">SEGERA HADIR</span>
                            </div>
                            <p class="text-xs text-muted mb-0">
                                Integrasi sistem invoicing sekolah langsung dengan generate kode QRIS dinamis berbayar otomatis melacak status pelunasan siswa.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <div class="d-flex align-items-center justify-content-lg-end">
                            <span class="mr-3 font-weight-bold text-xs text-muted">
                                <i class="fas fa-clock mr-1"></i> COOMING SOON
                            </span>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="module_qris_mock" disabled>
                                <label class="custom-control-label" for="module_qris_mock"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 border rounded-15 bg-light d-flex align-items-start mb-4" style="background-color: #f8fafc;">
                <i class="fas fa-info-circle text-info fa-lg mr-3 mt-1"></i>
                <div class="text-xs text-dark" style="line-height: 1.6;">
                    <strong>Petunjuk Pemilik Website:</strong> 
                    Mengaktifkan toggle switch di atas akan langsung menghapus halaman penutup (lock screen) dan membuka modul bagi pengguna tingkat Administrator/Wali Kelas tanpa mengharuskan pembayaran simulasi. Sebaliknya, menonaktifkan toggle switch akan memaksa modul terkunci kembali dan menampilkan form gerbang pembayaran simulation.
                </div>
            </div>
        </div>
        
        <div class="card-footer px-0 pb-0">
            <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold rounded-pill shadow-primary">
                <i class="fas fa-save mr-2"></i> SIMPAN PENGATURAN MODUL
            </button>
        </div>
    </div>
</form>

<style>
    /* Styling switch toggle yang lebih besar dan premium */
    .custom-switch-lg .custom-control-label::before {
        height: 1.5rem !important;
        width: 2.75rem !important;
        border-radius: 1rem !important;
    }
    .custom-switch-lg .custom-control-label::after {
        width: calc(1.5rem - 4px) !important;
        height: calc(1.5rem - 4px) !important;
        border-radius: 50% !important;
        background-color: #adb5bd !important;
        transition: transform .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out !important;
    }
    .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(1.25rem) !important;
        background-color: #ffffff !important;
    }
    .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
    }
    .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25) !important;
    }
    .cursor-pointer { cursor: pointer; }
    .opacity-60 { opacity: 0.65; }
    
    .premium-card-hover:hover {
        transform: translateY(-2px);
        border-color: #f59e0b !important;
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.08) !important;
    }
</style>
