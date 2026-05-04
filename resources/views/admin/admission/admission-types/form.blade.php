<x-modal data-backdrop="static" data-keyboard="false" size="modal-md" id="modal-form">
    <div class="modal-header bg-gradient-emerald text-white border-0 py-3 px-4">
        <h5 class="modal-title font-weight-bold">
            <i class="fas fa-tags mr-2"></i> Konfigurasi Jalur Pendaftaran
        </h5>
        <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
    </div>

    @method('POST')

    <div class="modal-body p-4 bg-light-soft">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Jalur Pendaftaran <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-route text-emerald"></i>
                            <input name="admission_type_name" type="text" class="form-control" autocomplete="off" placeholder="Contoh: Jalur Prestasi / Tahfidz" required>
                        </div>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle mr-1"></i> Jalur ini akan muncul sebagai opsi pilihan bagi pendaftar.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold mr-2 shadow-xs" data-dismiss="modal">BATAL</button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-emerald rounded-pill px-5 font-weight-bold shadow-emerald-light" id="submitBtn">
            <i class="fas fa-save mr-2"></i> SIMPAN JALUR
        </button>
    </x-slot>
</x-modal>

<style>
    /* Premium UI Components for Type Form */
    .bg-gradient-emerald { background: linear-gradient(135deg, #10b981 0%, #065f46 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-20 { border-radius: 20px; }
    .shadow-emerald-light { box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); }
    
    /* Input Group Premium Emerald */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #10b981; box-shadow: 0 0 15px rgba(16, 185, 129, 0.1); }
    .text-emerald { color: #10b981; }
</style>
