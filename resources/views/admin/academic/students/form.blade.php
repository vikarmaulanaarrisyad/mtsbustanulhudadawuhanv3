<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-xl" role="document">
        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <!-- MODAL HEADER WITH GRADIENT -->
                <div class="modal-header bg-gradient-primary text-white border-0 py-4 position-relative">
                    <div class="position-relative" style="z-index: 1;">
                        <h4 class="modal-title font-weight-bold mb-0">
                            <i class="fas fa-user-edit mr-2"></i> 
                            <span class="title-text">Formulir Data Siswa</span>
                        </h4>
                        <p class="mb-0 opacity-8 small">Lengkapi seluruh data identitas dan akademik dengan teliti.</p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- Decorative Circle -->
                    <div class="bg-circle-header"></div>
                </div>

                <div class="modal-body p-0">
                    <!-- CUSTOM PREMIUM TABS -->
                    <div class="premium-tab-container border-bottom bg-light-soft px-4 pt-3">
                        <ul class="nav nav-pills custom-pills" id="studentTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-identitas">
                                    <i class="fas fa-id-card mr-2"></i> Identitas Utama
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-akademik">
                                    <i class="fas fa-graduation-cap mr-2"></i> Akademik
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-alamat">
                                    <i class="fas fa-map-marked-alt mr-2"></i> Kontak & Fisik
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-ortu">
                                    <i class="fas fa-users mr-2"></i> Orang Tua
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content p-4">
                        {{-- TAB 1: IDENTITAS --}}
                        <div class="tab-pane fade show active" id="tab-identitas">
                            <div class="section-title mb-3">
                                <h6 class="font-weight-bold text-primary text-uppercase"><i class="fas fa-fingerprint mr-2"></i> Identitas Dasar</h6>
                                <hr class="mt-1 mb-3">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">NIS <span class="text-info font-italic">(Otomatis Jika Kosong)</span></label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-barcode"></i>
                                            <input type="text" name="nis" id="nis" class="form-control" placeholder="Auto-generate">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">NISN</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-id-badge"></i>
                                            <input type="text" name="nisn" id="nisn" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">NIK Siswa</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-address-card"></i>
                                            <input type="text" name="nik" id="nik" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-user"></i>
                                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Panggilan</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-user-tag"></i>
                                            <input type="text" name="nama_panggilan" id="nama_panggilan" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control rounded-pill px-3 border-2" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tempat Lahir</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-calendar-day"></i>
                                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Foto Siswa</label>
                                        <div class="custom-file-premium">
                                            <input type="file" name="foto" id="foto" accept="image/*">
                                            <label for="foto"><i class="fas fa-camera mr-2"></i> Unggah Foto</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 2: AKADEMIK --}}
                        <div class="tab-pane fade" id="tab-akademik">
                            <div class="section-title mb-3">
                                <h6 class="font-weight-bold text-success text-uppercase"><i class="fas fa-book-reader mr-2"></i> Data Penempatan & Status</h6>
                                <hr class="mt-1 mb-3">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tahun Pelajaran</label>
                                        <select name="academic_year_id" id="academic_year_id" class="form-control select2 rounded-pill px-3">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($academicYears as $ay)
                                                <option value="{{ $ay->id }}">{{ $ay->academic_year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Rombongan Belajar (Kelas)</label>
                                        <select name="student_class_group_id" id="student_class_group_id" class="form-control select2 rounded-pill px-3">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($classGroups as $cg)
                                                <option value="{{ $cg->id }}">{{ $cg->class_group }} {{ $cg->sub_class_group }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Status Keaktifan</label>
                                        <select name="student_status_id" id="student_status_id" class="form-control select2 rounded-pill px-3">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($studentStatuses as $ss)
                                                <option value="{{ $ss->id }}">{{ $ss->student_status_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tanggal Masuk</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Asal Sekolah Sebelumnya</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-school"></i>
                                            <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 bg-light-soft rounded-20 border-info-light border-dashed mt-2">
                                <h6 class="font-weight-bold text-dark text-xs uppercase mb-3"><i class="fas fa-laptop-code mr-2"></i> Pengaturan CBT (Ujian AI)</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="text-[10px] font-weight-bold text-muted uppercase">Gelombang</label>
                                            <select name="cbt_wave" id="cbt_wave" class="form-control form-control-sm">
                                                <option value="1">Gelombang 1</option>
                                                <option value="2">Gelombang 2</option>
                                                <option value="3">Gelombang 3</option>
                                                <option value="4">Gelombang 4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="text-[10px] font-weight-bold text-muted uppercase">Sesi Ujian</label>
                                            <select name="cbt_session" id="cbt_session" class="form-control form-control-sm">
                                                <option value="1">Sesi 1</option>
                                                <option value="2">Sesi 2</option>
                                                <option value="3">Sesi 3</option>
                                                <option value="4">Sesi 4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="text-[10px] font-weight-bold text-muted uppercase">Ruang Ujian</label>
                                            <input type="text" name="cbt_room" id="cbt_room" class="form-control form-control-sm" placeholder="Contoh: R1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 3: KONTAK & FISIK --}}
                        <div class="tab-pane fade" id="tab-alamat">
                            <div class="row">
                                <div class="col-md-7">
                                    <h6 class="font-weight-bold text-info text-uppercase"><i class="fas fa-map-marked-alt mr-2"></i> Alamat Domisili</h6>
                                    <hr class="mt-1 mb-3">
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" class="form-control rounded-15" rows="3" placeholder="Nama Jalan, No. Rumah..."></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="text-xs font-weight-bold text-muted uppercase">Desa/Kelurahan</label>
                                                <input type="text" name="desa" id="desa" class="form-control rounded-pill px-3">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="text-xs font-weight-bold text-muted uppercase">Kecamatan</label>
                                                <input type="text" name="kecamatan" id="kecamatan" class="form-control rounded-pill px-3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h6 class="font-weight-bold text-info text-uppercase"><i class="fas fa-running mr-2"></i> Data Fisik</h6>
                                    <hr class="mt-1 mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label class="text-xs font-weight-bold text-muted uppercase">Tinggi (cm)</label>
                                                <input type="number" name="tinggi_badan" id="tinggi_badan" class="form-control rounded-pill px-3">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group mb-3">
                                                <label class="text-xs font-weight-bold text-muted uppercase">Berat (kg)</label>
                                                <input type="number" name="berat_badan" id="berat_badan" class="form-control rounded-pill px-3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">No. HP / WhatsApp</label>
                                        <div class="input-group-premium border-info-light">
                                            <i class="fab fa-whatsapp text-success"></i>
                                            <input type="text" name="no_hp" id="no_hp" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 4: DATA ORANG TUA --}}
                        <div class="tab-pane fade" id="tab-ortu">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light-soft p-3 rounded-20 shadow-xs mb-3">
                                        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-male mr-2"></i> Data Ayah</h6>
                                        <div class="form-group mb-3">
                                            <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap Ayah</label>
                                            <input type="text" name="father_name" id="father_name" class="form-control rounded-pill px-3">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="text-xs font-weight-bold text-muted uppercase">No. HP Ayah</label>
                                            <input type="text" name="father_phone" id="father_phone" class="form-control rounded-pill px-3">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light-soft p-3 rounded-20 shadow-xs mb-3">
                                        <h6 class="font-weight-bold text-danger mb-3"><i class="fas fa-female mr-2"></i> Data Ibu</h6>
                                        <div class="form-group mb-3">
                                            <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap Ibu</label>
                                            <input type="text" name="mother_name" id="mother_name" class="form-control rounded-pill px-3">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="text-xs font-weight-bold text-muted uppercase">No. HP Ibu</label>
                                            <input type="text" name="mother_phone" id="mother_phone" class="form-control rounded-pill px-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-light-soft">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" id="submitBtn" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-primary-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                    </button>
                </div>
            </div>

            {{-- Hidden fields for internal profiles --}}
            <input type="hidden" name="profile_nik" id="profile_nik">
            <input type="hidden" name="profile_no_kk" id="profile_no_kk">
        </form>
    </div>
</div>

<style>
    /* Premium Modal Styling */
    .shadow-lg-premium { box-shadow: 0 15px 50px rgba(0,0,0,0.15) !important; }
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important; }
    .bg-circle-header { position: absolute; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -50px; right: -50px; z-index: 0; }
    .bg-light-soft { background: #f8faff; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    
    /* Custom Tabs */
    .custom-pills .nav-link { 
        border: none; color: #5a7ea3; font-weight: 700; font-size: 14px; 
        padding: 12px 20px; border-radius: 10px; transition: all 0.3s ease;
        margin-right: 8px; margin-bottom: 10px;
    }
    .custom-pills .nav-link.active { 
        background: #fff !important; color: #007bff !important; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); transform: translateY(-2px);
    }
    .custom-pills .nav-link:hover:not(.active) { background: rgba(0,123,255,0.05); color: #007bff; }

    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e1e8ef; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #adb5bd; font-size: 16px; margin-right: 12px; }
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #2d4154; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #007bff; box-shadow: 0 0 15px rgba(0,123,255,0.1); }
    .input-group-premium:focus-within i { color: #007bff; }

    /* Custom File Input */
    .custom-file-premium { position: relative; }
    .custom-file-premium input { display: none; }
    .custom-file-premium label { 
        display: block; background: #fff; border: 2px dashed #007bff; color: #007bff; 
        text-align: center; padding: 10px; border-radius: 12px; cursor: pointer;
        font-weight: bold; transition: all 0.3s ease;
    }
    .custom-file-premium label:hover { background: #007bff; color: #fff; }

    .shadow-primary-light { box-shadow: 0 4px 15px rgba(0,123,255,0.3); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
</style>
