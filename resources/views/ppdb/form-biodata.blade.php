{{-- FORM BIODATA PPDB --}}
<div class="glass-card border-0 shadow-2xl mb-8" style="border-radius: 2.5rem; overflow: hidden; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px);">
    <div class="card-header bg-white/50 border-0 py-6 px-8">
        <div class="d-flex align-items-center">
            <div class="bg-indigo-soft text-indigo-600 rounded-2xl p-3 mr-4 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 1.25rem !important; background: rgba(99, 102, 241, 0.1);">
                <i class="fas fa-edit fa-lg"></i>
            </div>
            <div>
                <h4 class="mb-0 font-weight-bold" style="color: #1e1b4b; letter-spacing: -0.5px;">{{ isset($registrant) ? 'Perbarui Data Pendaftaran' : 'Formulir Pendaftaran' }}</h4>
                <p class="text-muted mb-0 small font-medium">Silakan isi informasi calon siswa dengan lengkap</p>
            </div>
        </div>
    </div>
    
    <div class="card-body p-4 pt-0">
        {{-- STEPPER PENDAFTARAN --}}
        <div class="registration-stepper mb-5 pt-3">
            <div class="step active">
                <div class="step-num">1</div>
                <div class="step-text">Data Diri</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text">Berkas</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-num"><i class="fas fa-check"></i></div>
                <div class="step-text">Selesai</div>
            </div>
        </div>

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="formBiodata">
            @csrf
            @if($method === 'PUT')
                @method('PUT')
            @endif

            @if(isset($admission))
                <input type="hidden" name="student_admission_id" value="{{ $admission->id }}">
            @endif

            {{-- DATA DIRI --}}
            <div class="form-section">
                <h6 class="form-section-header">Identitas Calon Siswa</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control-clean @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $registrant->nama_lengkap ?? '') }}" placeholder="Contoh: Ahmad Fauzi" required>
                            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">NISN</label>
                            <input type="text" name="nisn" class="form-control-clean"
                                value="{{ old('nisn', $registrant->nisn ?? '') }}" placeholder="10 digit angka">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">NIK</label>
                            <input type="text" name="nik" class="form-control-clean"
                                value="{{ old('nik', $registrant->nik ?? '') }}" placeholder="16 digit angka">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-control-clean custom-select-clean @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('jenis_kelamin', $registrant->jenis_kelamin ?? '') ? 'selected' : '' }}>Pilih...</option>
                                <option value="L" {{ old('jenis_kelamin', $registrant->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $registrant->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control-clean @error('tempat_lahir') is-invalid @enderror"
                                value="{{ old('tempat_lahir', $registrant->tempat_lahir ?? '') }}" placeholder="Kab/Kota" required>
                            @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control-clean @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', isset($registrant) && $registrant->tanggal_lahir ? $registrant->tanggal_lahir->format('Y-m-d') : '') }}" required>
                            @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Asal Sekolah <span class="text-danger">*</span></label>
                            <input type="text" name="asal_sekolah" class="form-control-clean @error('asal_sekolah') is-invalid @enderror"
                                value="{{ old('asal_sekolah', $registrant->asal_sekolah ?? '') }}" placeholder="Nama SD/MI" required>
                            @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- GELOMBANG & JALUR --}}
            <div class="form-section mt-2">
                <h6 class="form-section-header">Pilihan Jalur Masuk</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Gelombang Pendaftaran</label>
                            <select name="admission_phase_id" class="form-control-clean">
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
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Jalur Masuk</label>
                            <select name="admission_type_id" class="form-control-clean">
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
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Pas Foto (Format Gambar)</label>
                            <div class="custom-file-clean">
                                <input type="file" name="foto" id="customFile" accept="image/*" class="d-none">
                                <label for="customFile" id="file-label" class="file-upload-trigger">
                                    <i class="fas fa-camera mr-2"></i> <span>{{ isset($registrant) && $registrant->foto ? 'Ganti Foto' : 'Klik untuk Pilih Foto' }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ORANG TUA --}}
            <div class="form-section mt-2">
                <h6 class="form-section-header">Data Wali Siswa</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Nama Lengkap Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control-clean"
                                value="{{ old('nama_ayah', $registrant->nama_ayah ?? '') }}" placeholder="Nama Ayah">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">Nama Lengkap Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control-clean"
                                value="{{ old('nama_ibu', $registrant->nama_ibu ?? '') }}" placeholder="Nama Ibu">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="form-label-clean">No. HP Aktif (WA) <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp_ortu" class="form-control-clean @error('no_hp_ortu') is-invalid @enderror"
                                value="{{ old('no_hp_ortu', $registrant->no_hp_ortu ?? '') }}" placeholder="Contoh: 0812..." required>
                            @error('no_hp_ortu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label-clean">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control-clean" rows="2" placeholder="Tuliskan alamat lengkap tempat tinggal">{{ old('alamat', $registrant->alamat ?? '') }}</textarea>
                </div>
            </div>

            <div class="submit-section mt-10 p-6 rounded-3xl shadow-xl border-0" style="background: rgba(248, 250, 252, 0.8); backdrop-filter: blur(10px); border-radius: 1.5rem;">
                <div class="row align-items-center">
                    <div class="col-md-8 text-muted small mb-4 mb-md-0 d-flex">
                        <i class="fas fa-shield-alt fa-3x mr-4 text-indigo-600 opacity-40"></i>
                        <span class="font-medium">Pastikan data yang Anda isi sudah benar sebelum melanjutkan ke tahap berikutnya. Data yang sudah diverifikasi tidak dapat diubah kembali tanpa bantuan admin.</span>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="submit" class="btn bg-grad-indigo text-white btn-block py-4 font-bold" style="border-radius: 1rem; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);" id="btnSubmitBiodata">
                            {{ isset($registrant) ? 'Simpan Perubahan' : 'Lanjut Unggah Berkas' }} <i class="fas fa-arrow-right ml-2"></i>
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
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 8px;
        transition: all 0.3s;
        border: 2px solid #e2e8f0;
    }
    .registration-stepper .step-text {
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .registration-stepper .step-line {
        flex: 0.3;
        height: 2px;
        background: #e2e8f0;
        margin: -25px 15px 0;
        max-width: 60px;
    }
    .registration-stepper .step.active .step-num {
        background: linear-gradient(135deg, #6366f1, #4338ca);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
    }
    .registration-stepper .step.active .step-text {
        color: #4338ca;
        font-weight: 800;
    }

    /* Clean Form Elements */
    .form-section-header {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #4f46e5;
        font-weight: 900;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 3px solid rgba(99, 102, 241, 0.1);
        display: inline-block;
    }
    .form-label-clean {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: block;
    }
    .form-control-clean {
        display: block;
        width: 100%;
        padding: 14px 20px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e1b4b;
        background-color: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .form-control-clean:focus {
        outline: none;
        background-color: #fff;
        border-color: #6366f1;
        box-shadow: 0 0 0 5px rgba(99, 102, 241, 0.15);
        transform: translateY(-2px);
    }
    .custom-select-clean {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.25rem;
    }
    .file-upload-trigger {
        display: block;
        padding: 14px 20px;
        background: #fff;
        border: 2px dashed #e2e8f0;
        border-radius: 1rem;
        color: #6366f1;
        font-size: 0.9rem;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    .file-upload-trigger:hover {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.05);
        transform: translateY(-2px);
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const userId = "{{ auth()->id() }}";
        const storageKey = 'ppdb_draft_' + userId;
        const form = $('#formBiodata');

        // Show filename on upload
        $('#customFile').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('#file-label').find('span').addClass('text-primary').html(fileName);
        });

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
                        title: 'Draf pendaftaran dipulihkan',
                        text: 'Melanjutkan pengisian data yang tersimpan sebelumnya.',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        background: '#f0f9ff',
                        color: '#0369a1',
                        iconColor: '#0369a1'
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
