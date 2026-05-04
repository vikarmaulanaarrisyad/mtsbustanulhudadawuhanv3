<x-modal data-backdrop="static" data-keyboard="false" size="modal-md" id="modal-form">
    <div class="modal-header bg-gradient-indigo text-white border-0 py-3 px-4">
        <h5 class="modal-title font-weight-bold">
            <i class="fas fa-layer-group mr-2"></i> Konfigurasi Gelombang
        </h5>
        <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
    </div>

    @method('POST')

    <div class="modal-body p-4 bg-light-soft">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Gelombang Pendaftaran <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-tag text-indigo"></i>
                            <input name="phase_name" type="text" class="form-control" autocomplete="off" placeholder="Contoh: Gelombang 1" required>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase text-primary">Tanggal Mulai <span class="text-danger">*</span></label>
                        <div class="input-group datepicker-premium shadow-sm" id="phase_start_date" data-target-input="nearest">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <input type="text" name="phase_start_date" class="form-control datetimepicker-input border-0 bg-transparent px-0"
                                data-target="#phase_start_date" data-toggle="datetimepicker" autocomplete="off" placeholder="Pilih Tanggal" required/>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase text-danger">Tanggal Selesai <span class="text-danger">*</span></label>
                        <div class="input-group datepicker-premium shadow-sm" id="phase_end_date" data-target-input="nearest">
                            <i class="fas fa-calendar-check text-danger"></i>
                            <input type="text" name="phase_end_date" class="form-control datetimepicker-input border-0 bg-transparent px-0"
                                data-target="#phase_end_date" data-toggle="datetimepicker" autocomplete="off" placeholder="Pilih Tanggal" required/>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase text-success">Tanggal Pengumuman <span class="text-danger">*</span></label>
                        <div class="input-group datepicker-premium shadow-sm" id="announcement_date" data-target-input="nearest">
                            <i class="fas fa-bullhorn text-success"></i>
                            <input type="text" name="announcement_date" class="form-control datetimepicker-input border-0 bg-transparent px-0"
                                data-target="#announcement_date" data-toggle="datetimepicker" autocomplete="off" placeholder="Pilih Tanggal" required/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold mr-2 shadow-xs" data-dismiss="modal">BATAL</button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light" id="submitBtn">
            <i class="fas fa-save mr-2"></i> SIMPAN GELOMBANG
        </button>
    </x-slot>
</x-modal>

<style>
    /* Premium UI Components for Phase Form */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4338ca 0%, #1e1b4b 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-20 { border-radius: 20px; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(67, 56, 202, 0.4); }
    
    /* Input Group Premium */
    .input-group-premium, .datepicker-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i, .datepicker-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control, .datepicker-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within, .datepicker-premium:focus-within { border-color: #4338ca; box-shadow: 0 0 15px rgba(67, 56, 202, 0.1); }
</style>
