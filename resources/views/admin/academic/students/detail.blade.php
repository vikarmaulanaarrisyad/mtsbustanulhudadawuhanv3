{{-- MODAL DETAIL SISWA --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white"><i class="fas fa-user-graduate mr-1"></i> Detail Siswa</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- FOTO --}}
                    <div class="col-md-3 text-center mb-3">
                        <img id="det_foto" src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}"
                            class="img-fluid img-circle elevation-2" style="width:120px;height:120px;object-fit:cover;">
                    </div>

                    {{-- IDENTITAS --}}
                    <div class="col-md-9">
                        <h5 class="mb-1 font-weight-bold" id="det_nama">-</h5>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td width="35%"><strong>NIS</strong></td><td id="det_nis">-</td></tr>
                            <tr><td><strong>NISN</strong></td><td id="det_nisn">-</td></tr>
                            <tr><td><strong>NIK</strong></td><td id="det_nik">-</td></tr>
                            <tr><td><strong>Jenis Kelamin</strong></td><td id="det_jk">-</td></tr>
                            <tr><td><strong>TTL</strong></td><td id="det_ttl">-</td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-graduation-cap mr-1"></i> Data Akademik</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Kelas</strong></td><td id="det_kelas">-</td></tr>
                            <tr><td><strong>Tahun Pelajaran</strong></td><td id="det_tahun">-</td></tr>
                            <tr><td><strong>Status</strong></td><td id="det_status">-</td></tr>
                            <tr><td><strong>Asal Sekolah</strong></td><td id="det_asal_sekolah">-</td></tr>
                            <tr><td><strong>Tanggal Masuk</strong></td><td id="det_tgl_masuk">-</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success"><i class="fas fa-map-marker-alt mr-1"></i> Alamat & Kontak</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Alamat</strong></td><td id="det_alamat">-</td></tr>
                            <tr><td><strong>No. HP</strong></td><td id="det_no_hp">-</td></tr>
                            <tr><td><strong>Email</strong></td><td id="det_email">-</td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary"><i class="fas fa-male mr-1"></i> Data Ayah</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Nama</strong></td><td id="det_ayah">-</td></tr>
                            <tr><td><strong>No. HP</strong></td><td id="det_hp_ayah">-</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger"><i class="fas fa-female mr-1"></i> Data Ibu</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Nama</strong></td><td id="det_ibu">-</td></tr>
                            <tr><td><strong>No. HP</strong></td><td id="det_hp_ibu">-</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
