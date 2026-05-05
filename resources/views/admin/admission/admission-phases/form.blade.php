<div class="modal fade" id="modal-form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-32 overflow-hidden bg-light-soft">
            <!-- PREMIUM HEADER -->
            <div class="modal-header bg-gradient-indigo p-4 text-white border-0 position-relative">
                <div class="position-relative z-index-10 d-flex align-items-center">
                    <div class="icon-box-premium mr-3">
                        <i class="fas fa-layer-group fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-black mb-0 tracking-tight text-white">Konfigurasi Gelombang</h5>
                        <p class="text-[10px] font-bold text-white opacity-60 uppercase tracking-widest mb-0">Pengaturan Tahapan PPDB</p>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-8 hover:opacity-100 transition-all" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-3xl">&times;</span>
                </button>
                <div class="header-glow"></div>
            </div>

            <form id="form-phase" method="POST">
                @csrf
                @method('POST')
                
                <div class="modal-body p-4">
                    <div class="card border-0 shadow-sm rounded-24 p-4 bg-white mb-0 overflow-visible">
                        <!-- PHASE NAME -->
                        <div class="form-group mb-4">
                            <label class="label-premium">Nama Gelombang Pendaftaran <span class="text-danger">*</span></label>
                            <div class="input-wrapper-premium">
                                <i class="fas fa-tag text-indigo"></i>
                                <input name="phase_name" type="text" class="form-control font-weight-bold" autocomplete="off" placeholder="Contoh: Gelombang 1" required>
                            </div>
                        </div>

                        <!-- DATE RANGE -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="label-premium text-primary">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <div class="input-wrapper-premium">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                        <input type="date" name="phase_start_date" class="form-control font-weight-bold" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="label-premium text-danger">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <div class="input-wrapper-premium">
                                        <i class="fas fa-calendar-check text-danger"></i>
                                        <input type="date" name="phase_end_date" class="form-control font-weight-bold" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ANNOUNCEMENT DATE -->
                        <div class="form-group mb-0">
                            <label class="label-premium text-success">Tanggal Pengumuman <span class="text-danger">*</span></label>
                            <div class="input-wrapper-premium">
                                <i class="fas fa-bullhorn text-success"></i>
                                <input type="date" name="announcement_date" class="form-control font-weight-bold" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-ghost" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(document.querySelector('#form-phase'))" class="btn btn-indigo-premium" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> SIMPAN GELOMBANG
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* CUSTOM PREMIUM MODAL STYLES */
    .rounded-32 { border-radius: 32px !important; }
    .rounded-24 { border-radius: 24px !important; }
    .z-index-10 { z-index: 10; }
    .bg-gradient-indigo { background: linear-gradient(135deg, #4338ca 0%, #1e1b4b 100%) !important; }
    .bg-light-soft { background: #f8fafc !important; }
    
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
</style>
