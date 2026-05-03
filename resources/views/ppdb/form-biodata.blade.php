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

            {{-- STEPPER PENDAFTARAN --}}
            <div class="registration-stepper mb-5">
                <div class="step active">
                    <div class="step-num">1</div>
                    <div class="step-text">Identitas Diri</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">Unggah Berkas</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-num"><i class="fas fa-check"></i></div>
                    <div class="step-text">Selesai</div>
                </div>
            </div>

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
            <div class="alert alert-info border-0 shadow-sm mt-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-lg mr-3"></i>
                    <div class="small">
                        <strong>Langkah Berikutnya:</strong> Setelah menyimpan data identitas ini, Anda akan diminta untuk mengunggah berkas persyaratan (Akta, KK, dll) secara satu-per-satu di halaman berikutnya.
                    </div>
                </div>
            </div>

            <div class="mt-5 p-4 rounded bg-light border-top">
                <div class="row align-items-center">
                    <div class="col-md-8 text-muted small mb-3 mb-md-0">
                        <i class="fas fa-shield-alt mr-2"></i> Dengan menekan tombol kirim, Anda menyatakan bahwa data yang diisi adalah benar dan dapat dipertanggungjawabkan sesuai hukum yang berlaku.
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="submit" class="btn btn-ppdb btn-lg btn-block" id="btnSubmitBiodata">
                            <i class="fas fa-arrow-right mr-2"></i>
                            {{ isset($registrant) ? 'Simpan Perubahan' : 'Simpan & Lanjut Unggah Berkas' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .registration-stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0;
    }
    .registration-stepper .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
    }
    .registration-stepper .step-num {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
        transition: all 0.3s;
        border: 4px solid #fff;
        box-shadow: 0 0 0 1px #e2e8f0;
    }
    .registration-stepper .step-text {
        font-size: 0.8rem;
        font-weight: 600;
        color: #94a3b8;
    }
    .registration-stepper .step-line {
        flex: 0.5;
        height: 2px;
        background: #e2e8f0;
        margin-top: -20px;
        max-width: 80px;
    }
    .registration-stepper .step.active .step-num {
        background: #10b981;
        color: white;
        box-shadow: 0 0 0 1px #10b981;
    }
    .registration-stepper .step.active .step-text {
        color: #10b981;
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const userId = "{{ auth()->id() }}";
        const storageKey = 'ppdb_draft_' + userId;
        const form = $('#formBiodata');

        // 1. Fungsi untuk Memuat Data dari LocalStorage
        function loadDraft() {
            const savedData = localStorage.getItem(storageKey);
            if (savedData) {
                const data = JSON.parse(savedData);
                let count = 0;
                Object.keys(data).forEach(key => {
                    const input = form.find(`[name="${key}"]`);
                    if (input.length) {
                        if (input.attr('type') !== 'file' && data[key]) {
                            input.val(data[key]);
                            count++;
                        }
                    }
                });
                
                if (count > 0) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Draf pendaftaran otomatis dipulihkan',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        }

        // 2. Fungsi untuk Menyimpan Data ke LocalStorage
        function saveDraft() {
            const formData = {};
            form.serializeArray().forEach(item => {
                // Jangan simpan token CSRF
                if (item.name !== '_token' && item.name !== '_method') {
                    formData[item.name] = item.value;
                }
            });
            localStorage.setItem(storageKey, JSON.stringify(formData));
        }

        // Jalankan pemulihan data saat halaman siap
        // Hanya jalankan jika ini adalah pendaftaran baru (registrant tidak ada)
        @if(!isset($registrant))
            loadDraft();
            
            // Simpan draft setiap kali ada perubahan input
            form.on('input change', 'input, select, textarea', function() {
                saveDraft();
            });
        @endif

        // Hapus draft saat form disubmit
        form.on('submit', function() {
            $('#btnSubmitBiodata').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
            localStorage.removeItem(storageKey);
        });
    });
</script>
@endpush
