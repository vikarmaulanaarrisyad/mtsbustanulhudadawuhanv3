<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" onsubmit="submitForm(this); return false;" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-briefcase mr-2"></i> Form Jabatan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted uppercase">Nama Jabatan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-tag"></i></span>
                                    </div>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Kepala Madrasah" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted uppercase">Kode Singkatan</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-hashtag"></i></span>
                                    </div>
                                    <input type="text" name="code" id="code" class="form-control" placeholder="Contoh: KAMAD">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-xs font-weight-bold text-muted uppercase">Deskripsi Tugas</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Jelaskan tupoksi jabatan ini..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-xs font-weight-bold text-muted uppercase">Urutan Tampil</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control" value="0">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3 pt-4">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                    <label class="custom-control-label font-weight-bold" for="is_active">Status Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3 pt-4">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_signer" value="0">
                                    <input type="checkbox" class="custom-control-input" id="is_signer" name="is_signer" value="1">
                                    <label class="custom-control-label font-weight-bold" for="is_signer">Bisa TTD Dokumen</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold" data-dismiss="modal">BATAL</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary rounded-pill px-5 font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> SIMPAN DATA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
