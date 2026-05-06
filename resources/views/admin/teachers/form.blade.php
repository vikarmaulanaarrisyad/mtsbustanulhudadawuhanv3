<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-xl" role="document">
        <form action="" method="post" class="form-horizontal" id="teacherForm">
            @csrf
            @method('post')

            <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
                <!-- MODAL HEADER WITH TEAL GRADIENT -->
                <div class="modal-header bg-gradient-info text-white border-0 py-4 position-relative">
                    <div class="position-relative" style="z-index: 1;">
                        <h5 class="modal-title font-weight-bold mb-0">
                            <i class="fas fa-user-tie mr-2"></i> 
                            Data Guru & Tenaga Kependidikan
                        </h5>
                        <p class="mb-0 opacity-8 small">Masukkan informasi profil profesional staf pengajar.</p>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- Decorative Circle -->
                    <div class="bg-circle-header"></div>
                </div>

                <div class="modal-body p-0 bg-light-soft">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified px-4 pt-3 border-0" id="teacherTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold text-uppercase pb-3" id="pribadi-tab" data-toggle="tab" href="#pribadi" role="tab">
                                <i class="fas fa-user mr-1"></i> Data Pribadi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold text-uppercase pb-3" id="kepegawaian-tab" data-toggle="tab" href="#kepegawaian" role="tab">
                                <i class="fas fa-briefcase mr-1"></i> Kepegawaian & Pendidikan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold text-uppercase pb-3" id="finansial-tab" data-toggle="tab" href="#finansial" role="tab">
                                <i class="fas fa-wallet mr-1"></i> Rekening & Gaji
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-4">
                        <!-- TAB DATA PRIBADI -->
                        <div class="tab-pane fade show active" id="pribadi" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-20 p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap (Sesuai Ijazah/Gelar) <span class="text-danger">*</span></label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-user-circle"></i>
                                            <input type="text" name="name" id="name" class="form-control" required placeholder="Cth: Ahmad Rifai, S.Pd">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">NIK (KTP)</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-id-card"></i>
                                            <input type="text" name="nik" id="nik" class="form-control" placeholder="16 Digit NIK" maxlength="16">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">NUPTK</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-id-badge"></i>
                                            <input type="text" name="nuptk" id="nuptk" class="form-control" placeholder="Nomor Unik Pendidik" maxlength="20">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Jenis Kelamin</label>
                                        <select name="gender" id="gender" class="form-control custom-select" style="border-radius: 12px; height: calc(1.5em + 1.25rem + 2px); border: 2px solid #e1e8ef; font-weight: 600; color: #2d4154;">
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tempat Lahir</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" placeholder="Tempat Lahir">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tanggal Lahir</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-calendar-alt"></i>
                                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">No. Handphone / WhatsApp</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-phone-alt"></i>
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Cth: 08123456789">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Alamat Lengkap</label>
                                        <textarea name="address" id="address" class="form-control" rows="3" style="border-radius: 12px; border: 2px solid #e1e8ef; padding: 15px; font-weight: 600; color: #2d4154;" placeholder="Alamat Sesuai KTP/Domisili"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB KEPEGAWAIAN -->
                        <div class="tab-pane fade" id="kepegawaian" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-20 p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">NIP / Nomor Pegawai</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-id-card-alt"></i>
                                            <input type="text" name="nip" id="nip" class="form-control" placeholder="Kosongkan jika bukan PNS">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Status Kepegawaian</label>
                                        <select name="employment_status" id="employment_status" class="form-control custom-select" style="border-radius: 12px; height: calc(1.5em + 1.25rem + 2px); border: 2px solid #e1e8ef; font-weight: 600; color: #2d4154;">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="PNS">PNS / ASN</option>
                                            <option value="PPPK">PPPK</option>
                                            <option value="GTY">Guru Tetap Yayasan (GTY)</option>
                                            <option value="GTT">Guru Tidak Tetap (GTT) / Honorer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Jabatan Utama (Pendidik)</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                            <select name="position" id="position" class="form-control" style="border: none !important; background: transparent !important; height: 50px; cursor: pointer;">
                                                <option value="">-- Pilih Jabatan --</option>
                                                @foreach($educatorPositions as $pos)
                                                    <option value="{{ $pos->name }}">{{ $pos->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Tugas Tambahan (Struktural)</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-briefcase"></i>
                                            <select name="additional_duty" id="additional_duty" class="form-control" style="border: none !important; background: transparent !important; height: 50px; cursor: pointer;">
                                                <option value="">-- Tidak Ada --</option>
                                                @foreach($structuralPositions as $pos)
                                                    <option value="{{ $pos->name }}">{{ $pos->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Pangkat / Golongan</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-medal"></i>
                                            <input type="text" name="rank" id="rank" class="form-control" placeholder="Cth: Penata / III-c">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">TMT (Tanggal Mulai Tugas)</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-calendar-check"></i>
                                            <input type="date" name="start_date" id="start_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Status Sertifikasi</label>
                                        <select name="certification_status" id="certification_status" class="form-control custom-select" style="border-radius: 12px; height: calc(1.5em + 1.25rem + 2px); border: 2px solid #e1e8ef; font-weight: 600; color: #2d4154;">
                                            <option value="0">Belum Sertifikasi</option>
                                            <option value="1">Sudah Sertifikasi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12"><hr></div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Pendidikan Terakhir</label>
                                        <select name="education" id="education" class="form-control custom-select" style="border-radius: 12px; height: calc(1.5em + 1.25rem + 2px); border: 2px solid #e1e8ef; font-weight: 600; color: #2d4154;">
                                            <option value="">-- Pendidikan --</option>
                                            <option value="SMA/SMK">SMA/SMK Sederajat</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1 / D4</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Jurusan Pendidikan</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-graduation-cap"></i>
                                            <input type="text" name="major" id="major" class="form-control" placeholder="Cth: PAI">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Asal Universitas</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-university"></i>
                                            <input type="text" name="university" id="university" class="form-control" placeholder="Cth: UIN Sunan Kalijaga">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB FINANSIAL -->
                        <div class="tab-pane fade" id="finansial" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-20 p-4">
                                <div class="alert alert-soft-info border-0 mb-4 rounded-15 shadow-xs">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3 bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;">
                                            <i class="fas fa-info text-xs"></i>
                                        </div>
                                        <span class="small font-weight-bold">Data finansial ini digunakan untuk fitur penggajian (payroll) bulanan. Pastikan data rekening benar.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Bank</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-building"></i>
                                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Cth: BRI / BSI">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">No. Rekening</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-credit-card"></i>
                                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" placeholder="Nomor Rekening">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Atas Nama Rekening</label>
                                        <div class="input-group-premium">
                                            <i class="fas fa-user-tag"></i>
                                            <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" placeholder="Sesuai Buku Tabungan">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3 mt-2">
                                        <label class="text-xs font-weight-bold text-muted uppercase">Gaji Pokok / Honor Dasar (Rp)</label>
                                        <div class="input-group-premium" style="border-color: #28a745;">
                                            <i class="fas fa-money-bill-wave text-success"></i>
                                            <input type="text" name="base_salary" id="base_salary" class="form-control text-success font-weight-bold" placeholder="Cth: 2.000.000" autocomplete="off">
                                        </div>
                                        <small class="text-muted mt-1 d-block">Masukkan angka, titik akan otomatis ditambahkan (Cth: 2000000 → 2.000.000)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-info rounded-pill px-5 font-weight-bold shadow-info-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN DATA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Modal Styling */
    .shadow-lg-premium { box-shadow: 0 15px 50px rgba(0,0,0,0.15) !important; }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important; }
    .bg-circle-header { position: absolute; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; top: -40px; right: -40px; z-index: 0; }
    .bg-light-soft { background: #f4f7f9; }
    .rounded-20 { border-radius: 20px; }
    .rounded-15 { border-radius: 15px; }
    .alert-soft-info { background: #e0f2f1; color: #00796b; }
    
    /* Input Group Premium */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e1e8ef; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #adb5bd; font-size: 16px; margin-right: 12px; }
    .input-group-premium input, .input-group-premium select { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #2d4154; width: 100%;
    }
    .input-group-premium select {
        padding-left: 5px !important;
        height: 50px !important;
    }
    .input-group-premium:focus-within { border-color: #17a2b8; box-shadow: 0 0 15px rgba(23,162,184,0.1); }
    .input-group-premium:focus-within i { color: #17a2b8; }

    .shadow-info-light { box-shadow: 0 4px 15px rgba(23,162,184,0.3); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

    /* Custom Tabs */
    .nav-tabs .nav-link { color: #a0aec0; border: none; border-bottom: 3px solid transparent; transition: all 0.3s; }
    .nav-tabs .nav-link:hover { color: #17a2b8; }
    .nav-tabs .nav-link.active { color: #17a2b8; border-bottom: 3px solid #17a2b8; background: transparent; }

    /* Tab sticky - isi konten saja yang scroll, tab tetap di atas */
    #modal-form .modal-body { overflow: hidden !important; padding: 0 !important; }
    #modal-form .modal-dialog { max-height: 95vh; }
    #modal-form .modal-content { overflow: hidden; }
    #modal-form #teacherTab { position: sticky; top: 0; z-index: 10; background: #f4f7f9; flex-shrink: 0; }
    #modal-form .tab-content {
        overflow-y: auto;
        max-height: calc(85vh - 200px);
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    #modal-form .tab-content::-webkit-scrollbar { display: none; }
</style>
