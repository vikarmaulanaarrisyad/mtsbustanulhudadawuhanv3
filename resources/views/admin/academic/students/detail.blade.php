{{-- MODAL DETAIL SISWA PREMIUM --}}
<div class="modal fade animate__animated animate__zoomIn" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
            
            {{-- EXECUTIVE PROFILE HEADER --}}
            <div class="bg-gradient-primary position-relative pt-5 pb-4 px-4 text-center">
                <button type="button" class="close text-white position-absolute" style="top: 15px; right: 20px;" data-dismiss="modal">
                    <span aria-hidden="true" class="text-lg">&times;</span>
                </button>
                
                <div class="position-relative d-inline-block mb-3">
                    <img id="det_foto" src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" 
                         class="rounded-circle shadow-lg profile-avatar border-4-white">
                    <div class="status-indicator bg-success shadow-sm" title="Siswa Aktif"></div>
                </div>
                
                <h3 class="text-white font-weight-bold mb-1" id="det_nama">-</h3>
                <p class="text-white-50 mb-3" style="letter-spacing: 1px;"><i class="fas fa-graduation-cap mr-1"></i> Profil Peserta Didik</p>
                
                <div class="d-flex justify-content-center flex-wrap" style="gap: 10px;">
                    <div class="badge badge-glass px-3 py-2"><span class="opacity-8 mr-1">NIS:</span> <strong id="det_nis">-</strong></div>
                    <div class="badge badge-glass px-3 py-2"><span class="opacity-8 mr-1">NISN:</span> <strong id="det_nisn">-</strong></div>
                    <div class="badge badge-glass bg-white text-primary px-3 py-2"><i class="fas fa-school mr-1"></i> <strong id="det_kelas">-</strong></div>
                </div>
                
                <div class="bg-circle-1"></div>
                <div class="bg-circle-2"></div>
            </div>

            <div class="modal-body p-4 bg-light-soft">
                <div class="row">
                    {{-- IDENTITAS DIRI CARD --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-15">
                            <div class="card-body p-4">
                                <h6 class="text-uppercase text-primary font-weight-bold mb-3 border-bottom pb-2">
                                    <i class="fas fa-id-card mr-2"></i> Identitas Diri
                                </h6>
                                <div class="detail-row">
                                    <div class="detail-icon bg-soft-primary"><i class="fas fa-fingerprint text-primary"></i></div>
                                    <div class="detail-content">
                                        <small class="text-muted text-uppercase">NIK / No. KTP</small>
                                        <div class="font-weight-bold text-dark" id="det_nik">-</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-icon bg-soft-info"><i class="fas fa-venus-mars text-info"></i></div>
                                    <div class="detail-content">
                                        <small class="text-muted text-uppercase">Jenis Kelamin</small>
                                        <div class="font-weight-bold text-dark" id="det_jk">-</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-icon bg-soft-success"><i class="fas fa-calendar-day text-success"></i></div>
                                    <div class="detail-content">
                                        <small class="text-muted text-uppercase">Tempat, Tanggal Lahir</small>
                                        <div class="font-weight-bold text-dark" id="det_ttl">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT AKADEMIK CARD --}}
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm rounded-15">
                            <div class="card-body p-4">
                                <h6 class="text-uppercase text-primary font-weight-bold mb-3 border-bottom pb-2">
                                    <i class="fas fa-university mr-2"></i> Riwayat Akademik
                                </h6>
                                <div class="detail-row">
                                    <div class="detail-icon bg-soft-warning"><i class="fas fa-history text-warning"></i></div>
                                    <div class="detail-content">
                                        <small class="text-muted text-uppercase">Tahun Pelajaran Masuk</small>
                                        <div class="font-weight-bold text-dark" id="det_tahun">-</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-icon bg-soft-danger"><i class="fas fa-school text-danger"></i></div>
                                    <div class="detail-content">
                                        <small class="text-muted text-uppercase">Asal Sekolah</small>
                                        <div class="font-weight-bold text-dark" id="det_asal_sekolah">-</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-icon bg-soft-primary"><i class="fas fa-sign-in-alt text-primary"></i></div>
                                    <div class="detail-content">
                                        <small class="text-muted text-uppercase">Tanggal Diterima</small>
                                        <div class="font-weight-bold text-dark" id="det_tgl_masuk">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ALAMAT & KONTAK CARD --}}
                    <div class="col-md-12 mb-4">
                        <div class="card border-0 shadow-sm rounded-15 overflow-hidden">
                            <div class="row no-gutters">
                                <div class="col-md-7 p-4 bg-white border-right">
                                    <h6 class="text-uppercase text-primary font-weight-bold mb-3">
                                        <i class="fas fa-map-marked-alt mr-2"></i> Alamat Domisili
                                    </h6>
                                    <p class="font-weight-bold text-dark mb-0" id="det_alamat">-</p>
                                </div>
                                <div class="col-md-5 p-4 bg-light-soft">
                                    <h6 class="text-uppercase text-success font-weight-bold mb-3">
                                        <i class="fas fa-address-book mr-2"></i> Kontak Siswa
                                    </h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fab fa-whatsapp text-success mr-2 text-lg"></i>
                                        <span class="font-weight-bold text-dark" id="det_no_hp">-</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-info mr-2 text-lg"></i>
                                        <span class="text-muted" id="det_email">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ORANG TUA CARD --}}
                    <div class="col-md-6 mb-2">
                        <div class="card border-0 shadow-xs rounded-15 bg-primary-light h-100">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="avatar-lg bg-white rounded-circle shadow-sm d-flex justify-content-center align-items-center mr-3" style="width:50px;height:50px;">
                                    <i class="fas fa-male text-primary text-xl"></i>
                                </div>
                                <div>
                                    <small class="text-primary text-uppercase font-weight-bold">Data Ayah</small>
                                    <h6 class="font-weight-bold text-dark mb-0 mt-1" id="det_ayah">-</h6>
                                    <small class="text-muted" id="det_hp_ayah">-</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <div class="card border-0 shadow-xs rounded-15 bg-danger-light h-100">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="avatar-lg bg-white rounded-circle shadow-sm d-flex justify-content-center align-items-center mr-3" style="width:50px;height:50px;">
                                    <i class="fas fa-female text-danger text-xl"></i>
                                </div>
                                <div>
                                    <small class="text-danger text-uppercase font-weight-bold">Data Ibu</small>
                                    <h6 class="font-weight-bold text-dark mb-0 mt-1" id="det_ibu">-</h6>
                                    <small class="text-muted" id="det_hp_ibu">-</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 p-3 bg-white justify-content-center">
                <span class="badge badge-light px-3 py-2 text-muted rounded-pill border">
                    Status Akademik Saat Ini: <strong class="text-success ml-1" id="det_status">-</strong>
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Detail Modal Styling */
    .shadow-lg-premium { box-shadow: 0 15px 50px rgba(0,0,0,0.15) !important; }
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important; }
    .bg-light-soft { background: #f8faff; }
    .rounded-15 { border-radius: 15px; }
    
    .border-4-white { border: 4px solid #fff; }
    .profile-avatar { width: 120px; height: 120px; object-fit: cover; position: relative; z-index: 2; }
    .status-indicator { 
        position: absolute; bottom: 5px; right: 10px; width: 22px; height: 22px; 
        border-radius: 50%; border: 3px solid #fff; z-index: 3;
    }
    
    .badge-glass { 
        background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); 
        backdrop-filter: blur(5px); color: #fff; border-radius: 8px; font-size: 0.8rem;
    }
    
    .bg-circle-1 { position: absolute; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; top: -100px; left: -50px; z-index: 0; }
    .bg-circle-2 { position: absolute; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; bottom: -50px; right: -50px; z-index: 0; }

    /* Detail Rows */
    .detail-row { display: flex; align-items: flex-start; margin-bottom: 15px; }
    .detail-row:last-child { margin-bottom: 0; }
    .detail-icon { 
        width: 38px; height: 38px; border-radius: 10px; display: flex; 
        align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0;
    }
    .detail-content { flex-grow: 1; }
    .detail-content small { font-size: 0.65rem; letter-spacing: 0.5px; display: block; margin-bottom: 2px; }

    /* Pastel Boxes */
    .bg-soft-primary { background: #e3f2fd; }
    .bg-soft-info { background: #e0f7fa; }
    .bg-soft-success { background: #e8f5e9; }
    .bg-soft-warning { background: #fff3e0; }
    .bg-soft-danger { background: #ffebee; }
    
    .bg-primary-light { background-color: rgba(0, 123, 255, 0.05); border: 1px solid rgba(0, 123, 255, 0.1); }
    .bg-danger-light { background-color: rgba(220, 53, 69, 0.05); border: 1px solid rgba(220, 53, 69, 0.1); }
</style>
