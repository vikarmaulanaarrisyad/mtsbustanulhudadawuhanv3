{{-- FORM BIODATA PPDB --}}
<div class="ppdb-card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="bg-success text-white rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h5 class="mb-0 font-weight-bold">{{ isset($registrant) ? 'Edit Data Pendaftaran' : 'Formulir Pendaftaran PPDB' }}</h5>
                <small class="text-muted">Lengkapi data di bawah ini dengan benar</small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="formBiodata">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif

            @if(isset($admission))
                <input type="hidden" name="student_admission_id" value="{{ $admission->id }}">
            @endif

            {{-- DATA DIRI --}}
            <h6 class="form-section-title"><i class="fas fa-user-circle mr-2"></i> Data Identitas Diri</h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            value="{{ old('nama_lengkap', $registrant->nama_lengkap ?? '') }}" placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">NISN</label>
                        <input type="text" name="nisn" class="form-control"
                            value="{{ old('nisn', $registrant->nisn ?? '') }}" placeholder="10 digit NISN">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">NIK</label>
                        <input type="text" name="nik" class="form-control"
                            value="{{ old('nik', $registrant->nik ?? '') }}" placeholder="16 digit NIK">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="" disabled {{ !old('jenis_kelamin', $registrant->jenis_kelamin ?? '') ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $registrant->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $registrant->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror"
                            value="{{ old('tempat_lahir', $registrant->tempat_lahir ?? '') }}" placeholder="Kota kelahiran" required>
                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                            value="{{ old('tanggal_lahir', isset($registrant) && $registrant->tanggal_lahir ? $registrant->tanggal_lahir->format('Y-m-d') : '') }}" required>
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Asal Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="asal_sekolah" class="form-control @error('asal_sekolah') is-invalid @enderror"
                            value="{{ old('asal_sekolah', $registrant->asal_sekolah ?? '') }}" placeholder="Nama sekolah asal" required>
                        @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- GELOMBANG & JALUR --}}
            <h6 class="form-section-title"><i class="fas fa-list-alt mr-2"></i> Jalur & Dokumen</h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Gelombang Pendaftaran</label>
                        <select name="admission_phase_id" class="form-control">
                            <option value="">Pilih Gelombang</option>
                            @foreach($phases as $p)
                                <option value="{{ $p->id }}" {{ old('admission_phase_id', $registrant->admission_phase_id ?? '') == $p->id ? 'selected' : '' }}>
                                    {{ $p->phase_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Jalur Masuk</label>
                        <select name="admission_type_id" class="form-control">
                            <option value="">Pilih Jalur</option>
                            @foreach($types as $t)
                                <option value="{{ $t->id }}" {{ old('admission_type_id', $registrant->admission_type_id ?? '') == $t->id ? 'selected' : '' }}>
                                    {{ $t->admission_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Pas Foto</label>
                        <div class="custom-file">
                            <input type="file" name="foto" class="custom-file-input" id="customFile" accept="image/*">
                            <label class="custom-file-label" for="customFile">Pilih file...</label>
                        </div>
                        @if(isset($registrant) && $registrant->foto)
                            <small class="text-success"><i class="fas fa-check-circle"></i> Foto sudah tersedia</small>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ORANG TUA --}}
            <h6 class="form-section-title"><i class="fas fa-user-friends mr-2"></i> Data Orang Tua / Wali</h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Ayah</label>
                        <input type="text" name="nama_ayah" class="form-control"
                            value="{{ old('nama_ayah', $registrant->nama_ayah ?? '') }}" placeholder="Nama lengkap ayah">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control"
                            value="{{ old('nama_ibu', $registrant->nama_ibu ?? '') }}" placeholder="Nama lengkap ibu">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">No. HP Orang Tua <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp_ortu" class="form-control @error('no_hp_ortu') is-invalid @enderror"
                            value="{{ old('no_hp_ortu', $registrant->no_hp_ortu ?? '') }}" placeholder="Contoh: 08123456789" required>
                        @error('no_hp_ortu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Nama jalan, RT/RW, Desa, Kecamatan, Kabupaten">{{ old('alamat', $registrant->alamat ?? '') }}</textarea>
            </div>

            {{-- UPLOAD BERKAS --}}
            <h6 class="form-section-title"><i class="fas fa-cloud-upload-alt mr-2"></i> Unggah Berkas Persyaratan</h6>

            <div class="alert alert-warning border-0 shadow-sm" style="background-color: #fffaf0; border-left: 4px solid #ed8936 !important;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle text-warning fa-lg mr-3"></i>
                    <div class="text-dark small">
                        Format: <strong>JPG, PNG, PDF</strong> (maks 2MB per file). Berkas dapat menyusul, namun wajib dilengkapi sebelum proses seleksi dimulai.
                    </div>
                </div>
            </div>

            @php
                $existingDocs = isset($registrant) ? $registrant->documents->pluck('document_type')->toArray() : [];
            @endphp

            <div class="row mt-4">
                @foreach(\App\Models\PpdbRegistrant::DOCUMENT_TYPES as $type => $name)
                    <div class="col-md-6 mb-3">
                        <div class="p-3 rounded border bg-light d-flex flex-column h-100">
                            <label class="font-weight-bold mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file-pdf text-danger mr-2"></i> {{ $name }}</span>
                                @if(in_array($type, $existingDocs))
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Terunggah</span>
                                @else
                                    <span class="badge badge-secondary">Belum ada</span>
                                @endif
                            </label>
                            <input type="file" class="form-control-file" name="doc_{{ $type }}" accept=".jpg,.jpeg,.png,.pdf">
                            @if($type === 'kip') <small class="text-muted mt-1">* Hanya bagi yang memiliki</small> @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 p-4 rounded bg-light border-top">
                <div class="row align-items-center">
                    <div class="col-md-8 text-muted small mb-3 mb-md-0">
                        <i class="fas fa-shield-alt mr-2"></i> Dengan menekan tombol kirim, Anda menyatakan bahwa data yang diisi adalah benar dan dapat dipertanggungjawabkan sesuai hukum yang berlaku.
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="submit" class="btn btn-ppdb btn-lg btn-block" id="btnSubmitBiodata">
                            <i class="fas fa-paper-plane mr-2"></i>
                            {{ isset($registrant) ? 'Simpan Perubahan' : 'Kirim Pendaftaran' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $('#formBiodata').on('submit', function() {
        $('#btnSubmitBiodata').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    });
</script>
@endpush
