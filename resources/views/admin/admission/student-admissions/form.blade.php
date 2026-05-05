<div class="modal fade" id="modal-form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-32 overflow-hidden bg-light-soft">
            <!-- PREMIUM HEADER -->
            <div class="modal-header bg-gradient-indigo p-4 text-white border-0 position-relative">
                <div class="position-relative z-index-10 d-flex align-items-center">
                    <div class="icon-box-premium mr-3">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-black mb-0 tracking-tight text-white">Konfigurasi Periode PPDB</h5>
                        <p class="text-[10px] font-bold text-white opacity-60 uppercase tracking-widest mb-0">Manajemen Penerimaan Siswa Baru</p>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-8 hover:opacity-100 transition-all" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-3xl">&times;</span>
                </button>
                <div class="header-glow"></div>
            </div>

            <form id="form-ppdb" method="POST">
                @csrf
                @method('POST')
                
                <div class="modal-body p-4 p-md-5">
                    <!-- STATUS SECTION -->
                    <div class="form-section-premium mb-4">
                        <div class="section-badge mb-3">
                            <i class="fas fa-info-circle mr-2"></i> STATUS & TAHUN
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="label-premium">Status Pendaftaran</label>
                                    <div class="input-wrapper-premium">
                                        <i class="fas fa-toggle-on text-indigo"></i>
                                        <select name="admission_status" class="form-control" required>
                                            <option value="open">🔓 AKTIF (Pendaftaran Dibuka)</option>
                                            <option value="close" selected>🔒 NON-AKTIF (Pendaftaran Ditutup)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="label-premium">Tahun Pendaftaran</label>
                                    <div class="input-wrapper-premium">
                                        <i class="fas fa-award text-indigo"></i>
                                        <input name="admission_year" type="number" class="form-control font-weight-bold" min="2000" max="2100" placeholder="Contoh: 2025" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TIMELINE SECTION -->
                    <div class="form-section-premium mb-4">
                        <div class="section-badge mb-3 bg-emerald-soft text-emerald">
                            <i class="fas fa-clock mr-2"></i> RENTANG WAKTU
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 d-block">Mulai Pendaftaran</label>
                                    <div class="input-wrapper-premium shadow-sm">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                        <input type="date" name="admission_start_date" class="form-control font-weight-bold" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 d-block">Selesai Pendaftaran</label>
                                    <div class="input-wrapper-premium shadow-sm">
                                        <i class="fas fa-calendar-times text-danger"></i>
                                        <input type="date" name="admission_end_date" class="form-control font-weight-bold" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 d-block text-indigo">Awal Pengumuman</label>
                                    <div class="input-wrapper-premium shadow-sm">
                                        <i class="fas fa-bullhorn text-indigo"></i>
                                        <input type="date" name="announcement_start_date" class="form-control font-weight-bold" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="text-[10px] font-black text-muted uppercase tracking-widest mb-2 d-block text-warning">Akhir Pengumuman</label>
                                    <div class="input-wrapper-premium shadow-sm">
                                        <i class="fas fa-hourglass-end text-warning"></i>
                                        <input type="date" name="announcement_end_date" class="form-control font-weight-bold" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ADMIN SECTION -->
                    <div class="form-section-premium">
                        <div class="section-badge mb-3 bg-slate-soft text-muted">
                            <i class="fas fa-file-alt mr-2"></i> LEGALITAS
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="label-premium">No. Berita Acara</label>
                                    <div class="input-wrapper-premium border-dashed">
                                        <i class="fas fa-file-signature opacity-50"></i>
                                        <input name="ba_letter_number" type="text" class="form-control text-sm" placeholder="Otomatis">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="label-premium">No. SK Kolektif</label>
                                    <div class="input-wrapper-premium border-dashed">
                                        <i class="fas fa-stamp opacity-50"></i>
                                        <input name="sk_letter_number" type="text" class="form-control text-sm" placeholder="Otomatis">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-ghost" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(document.querySelector('#form-ppdb'))" class="btn btn-indigo-premium" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> SIMPAN KONFIGURASI
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* CUSTOM PREMIUM MODAL STYLES */
    .rounded-32 { border-radius: 32px !important; }
    .z-index-10 { z-index: 10; }
    .bg-gradient-indigo { background: linear-gradient(135deg, #4338ca 0%, #1e1b4b 100%) !important; }
    .bg-light-soft { background: #f8fafc !important; }
    
    /* FIX: Remove overflow hidden to prevent datepicker clipping */
    .modal-content { overflow: visible !important; }
    .form-section-premium { 
        background: #ffffff; border-radius: 20px; padding: 20px;
        border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        position: relative;
        overflow: visible !important;
    }

    .icon-box-premium {
        width: 48px; height: 48px; background: rgba(255,255,255,0.1); 
        border-radius: 14px; border: 1px solid rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        box-shadow: inset 0 0 10px rgba(255,255,255,0.1);
    }
    
    .header-glow {
        position: absolute; top: -50px; right: -50px; width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%; filter: blur(40px);
    }

    .section-badge {
        display: inline-flex; align-items: center; padding: 6px 12px;
        background: #f5f3ff; color: #4f46e5; border-radius: 10px;
        font-size: 9px; font-weight: 900; letter-spacing: 1px;
    }
    .bg-emerald-soft { background: #ecfdf5 !important; }
    .bg-slate-soft { background: #f8fafc !important; }
    .text-emerald { color: #059669 !important; }

    .label-premium {
        font-size: 10px; font-weight: 800; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block;
    }

    .input-wrapper-premium {
        background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 12px;
        padding: 4px 15px; display: flex; align-items: center; transition: all 0.3s ease;
        position: relative;
    }
    .input-wrapper-premium:focus-within {
        border-color: #4338ca; background: #fff; transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.1);
    }
    .input-wrapper-premium.border-dashed { border-style: dashed; }
    
    .input-wrapper-premium i { font-size: 14px; margin-right: 12px; opacity: 0.7; }
    .input-wrapper-premium .form-control {
        border: none !important; background: transparent !important; box-shadow: none !important;
        font-weight: 700; color: #1e293b; padding: 10px 0 !important; height: auto !important;
    }

    .btn-indigo-premium {
        background: #4338ca; color: #fff; border-radius: 50px;
        padding: 12px 30px; font-weight: 900; font-size: 11px;
        letter-spacing: 1px; border: none; transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(67, 56, 202, 0.3);
    }
    .btn-indigo-premium:hover { transform: translateY(-2px); box-shadow: 0 15px 30px rgba(67, 56, 202, 0.4); color: #fff; }
    .btn-ghost { color: #94a3b8; font-weight: 800; font-size: 11px; letter-spacing: 1px; border: none; background: none; }

    /* Fix Datepicker Z-Index and Background */
    .bootstrap-datetimepicker-widget { 
        z-index: 99999 !important; 
        background: #ffffff !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
        border: 1px solid #e2e8f0 !important;
        padding: 10px !important;
    }
</style>
