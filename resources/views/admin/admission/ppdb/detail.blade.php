{{-- MODAL DETAIL PENDAFTAR --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white"><i class="fas fa-user-graduate mr-1"></i> Detail Pendaftar PPDB</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <img id="det_foto" src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}"
                            class="img-fluid img-circle elevation-2" style="width:110px;height:110px;object-fit:cover;">
                        <p class="mt-2 mb-0"><strong id="det_reg_no" class="text-primary">-</strong></p>
                    </div>
                    <div class="col-md-9">
                        <h5 class="mb-1 font-weight-bold" id="det_nama">-</h5>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td width="35%"><strong>NISN</strong></td><td id="det_nisn">-</td></tr>
                            <tr><td><strong>NIK</strong></td><td id="det_nik">-</td></tr>
                            <tr><td><strong>Jenis Kelamin</strong></td><td id="det_jk">-</td></tr>
                            <tr><td><strong>TTL</strong></td><td id="det_ttl">-</td></tr>
                            <tr><td><strong>Asal Sekolah</strong></td><td id="det_asal">-</td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-info-circle mr-1"></i> Info Pendaftaran</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Gelombang</strong></td><td id="det_gelombang">-</td></tr>
                            <tr><td><strong>Jalur</strong></td><td id="det_jalur">-</td></tr>
                            <tr><td><strong>Nama Ayah</strong></td><td id="det_ayah">-</td></tr>
                            <tr><td><strong>Nama Ibu</strong></td><td id="det_ibu">-</td></tr>
                            <tr><td><strong>No. HP</strong></td><td id="det_hp">-</td></tr>
                            <tr><td><strong>Alamat</strong></td><td id="det_alamat">-</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-clipboard-check mr-1"></i> Verifikasi</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Catatan</strong></td><td id="det_catatan">-</td></tr>
                            <tr><td><strong>Diverifikasi Oleh</strong></td><td id="det_verifier">-</td></tr>
                            <tr><td><strong>Tanggal</strong></td><td id="det_verified_at">-</td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h6 class="text-primary"><i class="fas fa-folder-open mr-1"></i> Berkas Persyaratan</h6>
                <table class="table table-sm table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Berkas</th>
                            <th width="25%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="det_docs_tbody">
                        <tr><td colspan="3" class="text-center text-muted">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
