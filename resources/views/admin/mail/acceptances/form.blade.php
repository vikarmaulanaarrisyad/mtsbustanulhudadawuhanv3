<x-modal size="modal-lg" data-backdrop="static">
    <x-slot name="title">
        <i class="fas fa-id-card-alt mr-2 text-emerald"></i> Form Surat Keterangan Diterima
    </x-slot>

    <form id="formAcceptance">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="row">
            <div class="col-md-8 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Pilih Siswa <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white">
                    <i class="fas fa-user-graduate"></i>
                    <select name="student_id" id="student_id" class="form-control select2" style="width: 100%;">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->nis }} - {{ $s->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Tanggal Surat <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white">
                    <i class="fas fa-calendar-day text-emerald"></i>
                    <input type="date" name="acceptance_date" id="acceptance_date" class="form-control font-weight-bold" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="col-12 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Nomor Surat <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white">
                    <i class="fas fa-hashtag"></i>
                    <input type="text" name="acceptance_number" id="acceptance_number" class="form-control font-weight-bold" placeholder="001/MTs-BH/V/2026">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-link text-emerald font-weight-bold p-0 mr-2" onclick="generateNumber('StudentAcceptance', 'MT', '#acceptance_number', 'acceptance_number')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Sekolah Asal <span class="text-danger">*</span></label>
                <div class="input-group-premium bg-white">
                    <i class="fas fa-school text-info"></i>
                    <input type="text" name="origin_school" id="origin_school" class="form-control font-weight-bold" placeholder="Nama Madrasah/Sekolah Asal">
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <label class="text-xs font-weight-bold text-muted uppercase ml-1">Kelas Asal</label>
                <div class="input-group-premium bg-white">
                    <i class="fas fa-layer-group text-muted"></i>
                    <input type="text" name="origin_class" id="origin_class" class="form-control font-weight-bold" placeholder="Contoh: VII (Tujuh)">
                </div>
            </div>
        </div>

        <div class="mt-3 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <h6 class="text-dark font-weight-bold mb-3 d-flex align-items-center">
                <span class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center mr-2 text-emerald">
                    <i class="fas fa-signature text-xs"></i>
                </span>
                Informasi Penandatangan
            </h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-md-0">
                        <label class="text-[10px] font-black text-muted uppercase">Nama</label>
                        <input type="text" name="signer_name" id="signer_name" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_name ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-md-0">
                        <label class="text-[10px] font-black text-muted uppercase">Jabatan</label>
                        <input type="text" name="signer_position" id="signer_position" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_position ?? 'Kepala Madrasah' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-md-0">
                        <label class="text-[10px] font-black text-muted uppercase">NIP</label>
                        <input type="text" name="signer_nip" id="signer_nip" class="form-control form-control-sm font-weight-bold border-0 bg-white rounded-lg shadow-sm" value="{{ $mailSetting->default_signer_nip ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" data-dismiss="modal" class="btn btn-light rounded-pill px-4 font-weight-bold text-muted mr-2">
            BATAL
        </button>
        <button type="submit" form="formAcceptance" class="btn btn-emerald rounded-pill px-5 font-weight-bold shadow-emerald-light" id="submitBtn">
            <i class="fas fa-save mr-1"></i> SIMPAN SURAT
        </button>
    </x-slot>
</x-modal>

<style>
    .bg-slate-50 { background: #f8fafc; }
    .rounded-2xl { border-radius: 1.5rem !important; }
</style>
