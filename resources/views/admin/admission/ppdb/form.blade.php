<x-modal data-backdrop="static" data-keyboard="false" size="modal-xl" id="modal-form">
    <div class="modal-header bg-gradient-indigo text-white border-0 py-4">
        <h5 class="modal-title font-weight-bold mb-0">
            <i class="fas fa-user-plus mr-2"></i> Form Pendaftaran Calon Siswa
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>

    @method('POST')
    <input type="hidden" name="student_admission_id" value="{{ $admission->id ?? '' }}">

    {{-- PREMIUM TABS NAVIGATION --}}
    <div class="px-4 pt-4 bg-light-soft">
        <ul class="nav nav-pills nav-pills-premium mb-0" role="tablist">
            <li class="nav-item mr-2">
                <a class="nav-link active font-weight-bold px-4 py-2" data-toggle="tab" href="#tab-data-diri">
                    <i class="fas fa-id-card mr-2"></i> DATA IDENTITAS
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link font-weight-bold px-4 py-2" data-toggle="tab" href="#tab-akademik">
                    <i class="fas fa-graduation-cap mr-2"></i> AKADEMIK & HP
                </a>
            </li>
            <li class="nav-item mr-2">
                <a class="nav-link font-weight-bold px-4 py-2" data-toggle="tab" href="#tab-orang-tua">
                    <i class="fas fa-users mr-2"></i> ORANG TUA & ALAMAT
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold px-4 py-2" data-toggle="tab" href="#tab-berkas">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> UPLOAD BERKAS
                </a>
            </li>
        </ul>
    </div>

    <div class="modal-body p-4 bg-light-soft">
        <div class="tab-content">
            {{-- TAB 1: DATA IDENTITAS --}}
            <div class="tab-pane fade show active" id="tab-data-diri">
                <div class="card border-0 shadow-sm rounded-20 p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group-premium">
                                    <i class="fas fa-user"></i>
                                    <input class="form-control" type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap sesuai ijazah" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">NISN</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-fingerprint"></i>
                                    <input class="form-control" type="text" name="nisn" placeholder="10 Digit NISN">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">NIK</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-id-card"></i>
                                    <input class="form-control" type="text" name="nik" placeholder="16 Digit NIK">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="input-group-premium">
                                    <i class="fas fa-venus-mars"></i>
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="" disabled selected>Pilih JK</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Tempat Lahir</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <input class="form-control" type="text" name="tempat_lahir" placeholder="Kota Kelahiran">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="input-group-premium">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input class="form-control" type="date" name="tanggal_lahir" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: AKADEMIK & HP --}}
            <div class="tab-pane fade" id="tab-akademik">
                <div class="card border-0 shadow-sm rounded-20 p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Asal Sekolah</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-school"></i>
                                    <input class="form-control" type="text" name="asal_sekolah" placeholder="Nama SD / MI Asal">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">No. HP Orang Tua / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group-premium">
                                    <i class="fab fa-whatsapp"></i>
                                    <input class="form-control" type="text" name="no_hp_ortu" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Gelombang Pendaftaran</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-layer-group"></i>
                                    <select name="admission_phase_id" class="form-control">
                                        <option value="">Pilih Gelombang</option>
                                        @foreach ($phases as $p)
                                            <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Jalur Masuk</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-route"></i>
                                    <select name="admission_type_id" class="form-control">
                                        <option value="">Pilih Jalur</option>
                                        @foreach ($types as $t)
                                            <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: ORANG TUA & ALAMAT --}}
            <div class="tab-pane fade" id="tab-orang-tua">
                <div class="card border-0 shadow-sm rounded-20 p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Nama Ayah Kandung</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-user-friends"></i>
                                    <input class="form-control" type="text" name="nama_ayah" placeholder="Nama lengkap Ayah">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Nama Ibu Kandung</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-user-friends"></i>
                                    <input class="form-control" type="text" name="nama_ibu" placeholder="Nama lengkap Ibu">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Alamat Lengkap</label>
                                <div class="input-group-premium align-items-start py-2">
                                    <i class="fas fa-home mt-2"></i>
                                    <textarea class="form-control border-0 px-0" name="alamat" rows="3" placeholder="Jl. Nama Desa, RT/RW, Kecamatan, Kabupaten"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-xs font-weight-bold text-muted uppercase">Foto Calon Siswa</label>
                                <div class="input-group-premium">
                                    <i class="fas fa-camera"></i>
                                    <input class="form-control" type="file" name="foto" accept="image/*">
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> Format: JPG/PNG, Maks 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 4: UPLOAD BERKAS --}}
            <div class="tab-pane fade" id="tab-berkas">
                <div class="alert alert-soft-indigo rounded-15 border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center text-indigo">
                        <i class="fas fa-cloud-upload-alt fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold mb-0">Digitalisasi Berkas</h6>
                            <p class="small mb-0">Upload berkas pendaftaran calon siswa (JPG, PNG, PDF, Maks 5MB per file).</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm rounded-15 p-3 h-100">
                            <h6 class="font-weight-bold text-muted text-xs uppercase mb-3"><i class="fas fa-id-card mr-2 text-primary"></i> Identitas Diri</h6>
                            <div class="form-group mb-3">
                                <label class="small text-muted font-weight-bold">Akta Kelahiran</label>
                                <input type="file" class="form-control form-control-sm border-2 rounded-pill" name="doc_akta" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="form-group mb-0">
                                <label class="small text-muted font-weight-bold">Kartu Keluarga (KK)</label>
                                <input type="file" class="form-control form-control-sm border-2 rounded-pill" name="doc_kk" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm rounded-15 p-3 h-100">
                            <h6 class="font-weight-bold text-muted text-xs uppercase mb-3"><i class="fas fa-graduation-cap mr-2 text-success"></i> Dokumen Akademik</h6>
                            <div class="form-group mb-3">
                                <label class="small text-muted font-weight-bold">Ijazah / SKL</label>
                                <input type="file" class="form-control form-control-sm border-2 rounded-pill" name="doc_ijazah" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="form-group mb-0">
                                <label class="small text-muted font-weight-bold">SKHUN / Sertifikat</label>
                                <input type="file" class="form-control form-control-sm border-2 rounded-pill" name="doc_skhun" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-0">
                        <div class="card border-0 shadow-sm rounded-15 p-3">
                            <h6 class="font-weight-bold text-muted text-xs uppercase mb-3"><i class="fas fa-file-alt mr-2 text-warning"></i> Berkas Tambahan</h6>
                            <div class="form-group mb-3">
                                <label class="small text-muted font-weight-bold">Rapor Terakhir</label>
                                <input type="file" class="form-control form-control-sm border-2 rounded-pill" name="doc_rapor" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="form-group mb-0">
                                <label class="small text-muted font-weight-bold">KIP / KPS / PKH <small>(opsional)</small></label>
                                <input type="file" class="form-control form-control-sm border-2 rounded-pill" name="doc_kip" accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs mr-2" data-dismiss="modal">BATAL</button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light" id="submitBtn">
            <i class="fas fa-save mr-2"></i> SIMPAN PENDAFTAR
        </button>
    </x-slot>
</x-modal>

<style>
    /* Premium UI Components for Form */
    .bg-gradient-indigo { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; }
    .bg-light-soft { background: #f8fafc; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .shadow-indigo-light { box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4); }
    .alert-soft-indigo { background: #eef2ff; color: #4338ca; }
    
    .nav-pills-premium .nav-link { 
        border-radius: 10px; color: #64748b; background: transparent; 
        transition: all 0.3s; border: 1px solid transparent; margin-bottom: 10px;
    }
    .nav-pills-premium .nav-link.active { background: #4f46e5 !important; color: #fff !important; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3); }
    .nav-pills-premium .nav-link:hover:not(.active) { background: #e2e8f0; color: #1e293b; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #4f46e5; box-shadow: 0 0 15px rgba(79, 70, 229, 0.1); }
</style>
