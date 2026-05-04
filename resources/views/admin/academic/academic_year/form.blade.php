<x-modal data-backdrop="static" data-keyboard="false" size="modal-md" id="modal-form">
    <div class="modal-header bg-gradient-blue-cool text-white border-0 py-3 px-4">
        <h5 class="modal-title font-weight-bold">
            <i class="fas fa-calendar-plus mr-2"></i> Konfigurasi Tahun Pelajaran
        </h5>
        <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
    </div>

    @method('POST')

    <div class="modal-body p-4 bg-light-soft">
        <div class="card border-0 shadow-sm rounded-20 p-4 bg-white">
            <div class="row">
                {{-- TAHUN PELAJARAN --}}
                <div class="col-lg-12">
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <input name="academic_year" type="text" class="form-control font-weight-bold" placeholder="Contoh: 2024/2025" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                {{-- SEMESTER --}}
                <div class="col-lg-12">
                    <div class="form-group mb-0">
                        <label class="text-xs font-weight-bold text-muted uppercase">Semester <span class="text-danger">*</span></label>
                        <div class="input-group-premium shadow-sm">
                            <i class="fas fa-clock text-info"></i>
                            <select name="semester_id" class="form-control font-weight-bold" required>
                                <option disabled selected>-- Pilih Semester --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->semester_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold mr-2 shadow-xs" data-dismiss="modal">BATAL</button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-blue-light text-white" id="submitBtn">
            <i class="fas fa-save mr-2"></i> SIMPAN PERIODE
        </button>
    </x-slot>
</x-modal>

<style>
    /* Premium UI Components for Academic Form */
    .bg-gradient-blue-cool { background: linear-gradient(135deg, #007bff 0%, #00d2ff 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-20 { border-radius: 20px; }
    .shadow-blue-light { box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4); }
    
    /* Input Group Premium Blue */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #007bff; box-shadow: 0 0 15px rgba(0, 123, 255, 0.1); }
</style>
