<x-modal data-backdrop="static" data-keyboard="false" size="modal-md" id="modal-form">
    <div class="modal-header bg-gradient-cyan text-white border-0 py-3 px-4">
        <h5 class="modal-title font-weight-bold">
            <i class="fas fa-chart-pie mr-2"></i> Alokasi Kuota Pendaftaran
        </h5>
        <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
    </div>

    @method('POST')

    <div class="modal-body p-4 bg-light-soft">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Gelombang <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-layer-group text-cyan"></i>
                            <select name="admission_phase_id" id="admission_phase_id" class="form-control font-weight-bold" required>
                                <option disabled selected>-- Pilih Gelombang --</option>
                                @foreach ($admissionPhases as $phase)
                                    <option value="{{ $phase->id }}">{{ $phase->phase_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Pilih Jalur Pendaftaran <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-route text-cyan"></i>
                            <select name="admission_types_id" id="admission_types_id" class="form-control font-weight-bold" required>
                                <option disabled selected>-- Pilih Jalur --</option>
                                @foreach ($admissionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->admission_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase text-cyan">Jumlah Kuota (Siswa) <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-user-plus text-cyan"></i>
                            <input id="quota" name="quota" type="number" min="0" class="form-control font-weight-bold h5 mb-0" autocomplete="off" value="0" required>
                        </div>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle mr-1"></i> Jumlah maksimum siswa yang dapat diterima untuk kombinasi ini.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold mr-2 shadow-xs" data-dismiss="modal">BATAL</button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-cyan rounded-pill px-5 font-weight-bold shadow-cyan-light text-white" id="submitBtn">
            <i class="fas fa-save mr-2"></i> SIMPAN KUOTA
        </button>
    </x-slot>
</x-modal>

<style>
    /* Premium UI Components for Quota Form */
    .bg-gradient-cyan { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-20 { border-radius: 20px; }
    .shadow-cyan-light { box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4); }
    
    /* Input Group Premium Cyan */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #06b6d4; box-shadow: 0 0 15px rgba(6, 182, 212, 0.1); }
    .text-cyan { color: #06b6d4; }
</style>
