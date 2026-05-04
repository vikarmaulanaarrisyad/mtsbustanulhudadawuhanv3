<x-modal data-backdrop="static" data-keyboard="false" size="modal-md" id="modal-form">
    <div class="modal-header bg-gradient-indigo text-white border-0 py-3 px-4">
        <h5 class="modal-title font-weight-bold">
            <i class="fas fa-user-tag mr-2"></i> Konfigurasi Status Siswa
        </h5>
        <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
    </div>

    @method('POST')

    <div class="modal-body p-4 bg-light-soft">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Status Peserta Didik <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-tag text-indigo"></i>
                            <input name="student_status_name" type="text" class="form-control font-weight-bold text-lg" placeholder="Contoh: Aktif / Lulus / Mutasi" autocomplete="off" required>
                        </div>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle mr-1"></i> Label status ini akan digunakan secara global pada data profil siswa.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold mr-2 shadow-xs" data-dismiss="modal">BATAL</button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light text-white" id="submitBtn">
            <i class="fas fa-save mr-2"></i> SIMPAN STATUS
        </button>
    </x-slot>
</x-modal>

<style>
    /* Premium UI Components for Status Form */
    .bg-gradient-indigo { background: linear-gradient(135deg, #6610f2 0%, #4b0082 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-20 { border-radius: 20px; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(102, 16, 242, 0.4); }
    
    /* Input Group Premium Indigo */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #6610f2; box-shadow: 0 0 15px rgba(102, 16, 242, 0.1); }
    .text-indigo { color: #6610f2; }
</style>
