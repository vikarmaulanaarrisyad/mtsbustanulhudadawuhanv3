<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
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

                <div class="modal-body p-4 bg-light-soft">
                    <div class="card border-0 shadow-sm rounded-20 p-4">
                        <div class="section-title mb-4">
                            <h6 class="font-weight-bold text-info text-uppercase"><i class="fas fa-info-circle mr-2"></i> Identitas Pegawai</h6>
                            <hr class="mt-1">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Nama Lengkap Guru <span class="text-danger">*</span></label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-user-circle"></i>
                                        <input type="text" name="name" id="name" class="form-control" required placeholder="Masukkan nama lengkap beserta gelar">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="text-xs font-weight-bold text-muted uppercase">NIP / Nomor Identitas Pegawai</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-id-card-alt"></i>
                                        <input type="text" name="nip" id="nip" class="form-control" placeholder="Kosongkan jika belum memiliki NIP">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Jabatan / Tugas Utama</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-briefcase"></i>
                                        <input type="text" name="position" id="position" class="form-control" placeholder="Contoh: Guru Matematika">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Pangkat / Golongan</label>
                                    <div class="input-group-premium">
                                        <i class="fas fa-medal"></i>
                                        <input type="text" name="rank" id="rank" class="form-control" placeholder="Contoh: Penata / III-c">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-soft-info border-0 mt-4 rounded-15 shadow-xs">
                        <div class="d-flex align-items-center">
                            <div class="mr-3 bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:30px;height:30px;">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="small font-weight-bold">Data ini akan digunakan untuk keperluan cetak SK, Rapor, dan dokumen resmi lainnya.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-info rounded-pill px-5 font-weight-bold shadow-info-light">
                        <i class="fas fa-save mr-2"></i> SIMPAN DATA GURU
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
    .input-group-premium input { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #2d4154; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #17a2b8; box-shadow: 0 0 15px rgba(23,162,184,0.1); }
    .input-group-premium:focus-within i { color: #17a2b8; }

    .shadow-info-light { box-shadow: 0 4px 15px rgba(23,162,184,0.3); }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
</style>
