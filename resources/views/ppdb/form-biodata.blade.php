{{-- FORM BIODATA PPDB --}}
<div class="ppdb-card">
    <div class="card-header">
        <i class="fas fa-edit mr-1"></i>
        {{ isset($registrant) ? 'Edit Data Pendaftaran' : 'Formulir Pendaftaran PPDB' }}
    </div>
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="formBiodata">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif

            @if(isset($admission))
                <input type="hidden" name="student_admission_id" value="{{ $admission->id }}">
            @endif

            {{-- DATA DIRI --}}
            <h6 class="form-section-title"><i class="fas fa-user mr-1"></i> Data Diri</h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                            value="{{ old('nama_lengkap', $registrant->nama_lengkap ?? '') }}" required>
                        @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>NISN</label>
                        <input type="text" name="nisn" class="form-control"
                            value="{{ old('nisn', $registrant->nisn ?? '') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" class="form-control"
                            value="{{ old('nik', $registrant->nik ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                            <option disabled {{ !old('jenis_kelamin', $registrant->jenis_kelamin ?? '') ? 'selected' : '' }}>Pilih</option>
                            <option value="L" {{ old('jenis_kelamin', $registrant->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $registrant->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror"
                            value="{{ old('tempat_lahir', $registrant->tempat_lahir ?? '') }}" required>
                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                            value="{{ old('tanggal_lahir', isset($registrant) ? $registrant->tanggal_lahir->format('Y-m-d') : '') }}" required>
                        @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Asal Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="asal_sekolah" class="form-control @error('asal_sekolah') is-invalid @enderror"
                            value="{{ old('asal_sekolah', $registrant->asal_sekolah ?? '') }}" required>
                        @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- GELOMBANG & JALUR --}}
            <h6 class="form-section-title"><i class="fas fa-clipboard-list mr-1"></i> Info Pendaftaran</h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gelombang</label>
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
                        <label>Jalur Masuk</label>
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
                        <label>Pas Foto</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        @if(isset($registrant) && $registrant->foto)
                            <small class="text-muted">Foto sudah ada. Upload baru untuk mengganti.</small>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ORANG TUA --}}
            <h6 class="form-section-title"><i class="fas fa-users mr-1"></i> Data Orang Tua</h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Ayah</label>
                        <input type="text" name="nama_ayah" class="form-control"
                            value="{{ old('nama_ayah', $registrant->nama_ayah ?? '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Ibu</label>
                        <input type="text" name="nama_ibu" class="form-control"
                            value="{{ old('nama_ibu', $registrant->nama_ibu ?? '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>No. HP Orang Tua <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp_ortu" class="form-control @error('no_hp_ortu') is-invalid @enderror"
                            value="{{ old('no_hp_ortu', $registrant->no_hp_ortu ?? '') }}" required>
                        @error('no_hp_ortu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $registrant->alamat ?? '') }}</textarea>
            </div>

            {{-- UPLOAD BERKAS --}}
            <h6 class="form-section-title"><i class="fas fa-file-upload mr-1"></i> Upload Berkas Persyaratan</h6>

            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i>
                Format: <strong>JPG, PNG, PDF</strong> (maks 5MB per file). Berkas bersifat opsional saat ini, namun wajib dilengkapi sebelum verifikasi.
            </div>

            @php
                $existingDocs = isset($registrant) ? $registrant->documents->pluck('document_type')->toArray() : [];
            @endphp

            <div class="row">
                @foreach(\App\Models\PpdbRegistrant::DOCUMENT_TYPES as $type => $name)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <i class="fas fa-file-alt text-primary mr-1"></i> {{ $name }}
                                @if($type === 'kip') <small class="text-muted">(opsional)</small> @endif
                                @if(in_array($type, $existingDocs))
                                    <span class="badge badge-success ml-1"><i class="fas fa-check"></i> Sudah Upload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="doc_{{ $type }}" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            <div class="text-center">
                <button type="submit" class="btn btn-ppdb btn-lg" id="btnSubmitBiodata">
                    <i class="fas fa-paper-plane mr-1"></i>
                    {{ isset($registrant) ? 'Simpan Perubahan' : 'Kirim Pendaftaran' }}
                </button>
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
