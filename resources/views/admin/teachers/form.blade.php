<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-header bg-gradient-info text-white border-0 py-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="modal-title font-weight-bold">Form Guru & Staf</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="font-weight-bold text-muted small uppercase">Nama Lengkap Guru <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control rounded-pill px-3" required autofocus placeholder="Masukkan nama lengkap">
                                <span class="help-block with-errors text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nip" class="font-weight-bold text-muted small uppercase">NIP / No. Identitas</label>
                                <input type="text" name="nip" id="nip" class="form-control rounded-pill px-3" placeholder="Contoh: 198XXXXXXXXXXXXXXX">
                                <span class="help-block with-errors text-danger small"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="position" class="font-weight-bold text-muted small uppercase">Jabatan / Tugas Utama</label>
                                <input type="text" name="position" id="position" class="form-control rounded-pill px-3" placeholder="Contoh: Guru Mata Pelajaran">
                                <span class="help-block with-errors text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rank" class="font-weight-bold text-muted small uppercase">Pangkat / Golongan</label>
                                <input type="text" name="rank" id="rank" class="form-control rounded-pill px-3" placeholder="Contoh: Pembina / IV-a">
                                <span class="help-block with-errors text-danger small"></span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info bg-soft-info border-0 mt-3 mb-0 shadow-xs" style="border-radius: 10px;">
                        <div class="d-flex">
                            <i class="fas fa-info-circle mt-1 mr-2"></i>
                            <span class="small">Pastikan data yang dimasukkan sudah sesuai dengan dokumen resmi kepegawaian.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-info rounded-pill px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> SIMPAN DATA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-soft-info { background: #e0f7fa; color: #00838f; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>
