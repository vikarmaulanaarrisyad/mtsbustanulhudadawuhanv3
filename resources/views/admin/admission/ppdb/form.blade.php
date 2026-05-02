<x-modal data-backdrop="static" data-keyboard="false" size="modal-xl">
    <x-slot name="title">Tambah Pendaftar</x-slot>

    @method('POST')
    <input type="hidden" name="student_admission_id" value="{{ $admission->id ?? '' }}">

    {{-- TABS --}}
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-data-diri"><i class="fas fa-user mr-1"></i> Data Diri</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-berkas"><i class="fas fa-file-upload mr-1"></i> Upload Berkas</a>
        </li>
    </ul>

    <div class="tab-content pt-3">
        {{-- TAB 1: DATA DIRI --}}
        <div class="tab-pane fade show active" id="tab-data-diri">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                        <input id="nama_lengkap" class="form-control" type="text" name="nama_lengkap" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nisn">NISN</label>
                        <input id="nisn" class="form-control" type="text" name="nisn" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input id="nik" class="form-control" type="text" name="nik" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                            <option disabled selected>Pilih</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input id="tempat_lahir" class="form-control" type="text" name="tempat_lahir" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input id="tanggal_lahir" class="form-control" type="date" name="tanggal_lahir">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asal_sekolah">Asal Sekolah</label>
                        <input id="asal_sekolah" class="form-control" type="text" name="asal_sekolah" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="admission_phase_id">Gelombang</label>
                        <select name="admission_phase_id" id="admission_phase_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($phases as $p)
                                <option value="{{ $p->id }}">{{ $p->phase_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="admission_type_id">Jalur Masuk</label>
                        <select name="admission_type_id" id="admission_type_id" class="form-control">
                            <option value="">Pilih</option>
                            @foreach ($types as $t)
                                <option value="{{ $t->id }}">{{ $t->admission_type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_hp_ortu">No. HP Orang Tua <span class="text-danger">*</span></label>
                        <input id="no_hp_ortu" class="form-control" type="text" name="no_hp_ortu" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nama_ayah">Nama Ayah</label>
                        <input id="nama_ayah" class="form-control" type="text" name="nama_ayah" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nama_ibu">Nama Ibu</label>
                        <input id="nama_ibu" class="form-control" type="text" name="nama_ibu" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="foto">Pas Foto Pendaftar</label>
                        <input id="foto" class="form-control" type="file" name="foto" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" class="form-control" name="alamat" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: UPLOAD BERKAS --}}
        <div class="tab-pane fade" id="tab-berkas">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Upload berkas persyaratan. Format yang diterima: <strong>JPG, PNG, PDF</strong> (maks 5MB per file).
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_akta"><i class="fas fa-file-alt text-primary mr-1"></i> Akta Kelahiran</label>
                        <input type="file" class="form-control" id="doc_akta" name="doc_akta" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_kk"><i class="fas fa-file-alt text-primary mr-1"></i> Kartu Keluarga (KK)</label>
                        <input type="file" class="form-control" id="doc_kk" name="doc_kk" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_ijazah"><i class="fas fa-file-alt text-success mr-1"></i> Ijazah / SKL</label>
                        <input type="file" class="form-control" id="doc_ijazah" name="doc_ijazah" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_skhun"><i class="fas fa-file-alt text-success mr-1"></i> SKHUN / Sertifikat Hasil Ujian</label>
                        <input type="file" class="form-control" id="doc_skhun" name="doc_skhun" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_rapor"><i class="fas fa-file-alt text-warning mr-1"></i> Rapor Terakhir</label>
                        <input type="file" class="form-control" id="doc_rapor" name="doc_rapor" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_foto"><i class="fas fa-camera text-info mr-1"></i> Pas Foto 3x4</label>
                        <input type="file" class="form-control" id="doc_foto" name="doc_foto" accept=".jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="doc_kip"><i class="fas fa-id-card text-danger mr-1"></i> Kartu Indonesia Pintar (KIP) <small class="text-muted">— opsional</small></label>
                        <input type="file" class="form-control" id="doc_kip" name="doc_kip" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </x-slot>
</x-modal>
