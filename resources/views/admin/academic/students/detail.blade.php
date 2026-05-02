{{-- MODAL DETAIL SISWA --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success shadow-sm">
                <h5 class="modal-title text-white font-weight-bold">
                    <i class="fas fa-user-graduate mr-2"></i> Detail Informasi Siswa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                {{-- Profile Header --}}
                <div class="p-4 bg-light border-bottom d-flex align-items-center">
                    <div class="mr-4">
                        <img id="det_foto" src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}"
                            class="img-thumbnail rounded-circle shadow-sm" style="width:110px;height:110px;object-fit:cover;border: 4px solid #fff;">
                    </div>
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark" id="det_nama">-</h4>
                        <div class="d-flex flex-wrap" style="gap: 10px;">
                            <div class="badge badge-info px-3 py-2 shadow-xs">NIS: <span id="det_nis">-</span></div>
                            <div class="badge badge-secondary px-3 py-2 shadow-xs">NISN: <span id="det_nisn">-</span></div>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row">
                        {{-- Data Pribadi --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-id-card mr-2 text-info"></i> Identitas Diri
                            </h6>
                            <div class="list-group list-group-flush border rounded shadow-sm">
                                <div class="list-group-item d-flex justify-content-between p-2">
                                    <span class="text-muted small">NIK</span>
                                    <span class="font-weight-bold" id="det_nik">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between p-2">
                                    <span class="text-muted small">Jenis Kelamin</span>
                                    <span class="font-weight-bold" id="det_jk">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between p-2 border-bottom-0">
                                    <span class="text-muted small">Tempat, Tgl Lahir</span>
                                    <span class="font-weight-bold text-right ml-2" id="det_ttl">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Data Akademik --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-university mr-2 text-primary"></i> Data Akademik
                            </h6>
                            <div class="list-group list-group-flush border rounded shadow-sm bg-light">
                                <div class="list-group-item d-flex justify-content-between p-2 bg-transparent">
                                    <span class="text-muted small">Kelas Sekarang</span>
                                    <span class="badge badge-primary" id="det_kelas">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between p-2 bg-transparent">
                                    <span class="text-muted small">Tahun Pelajaran</span>
                                    <span class="font-weight-bold" id="det_tahun">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between p-2 bg-transparent">
                                    <span class="text-muted small">Status Siswa</span>
                                    <span class="font-weight-bold text-success" id="det_status">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between p-2 bg-transparent">
                                    <span class="text-muted small">Asal Sekolah</span>
                                    <span class="font-weight-bold" id="det_asal_sekolah">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between p-2 bg-transparent border-bottom-0">
                                    <span class="text-muted small">Tanggal Masuk</span>
                                    <span class="font-weight-bold" id="det_tgl_masuk">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Alamat & Kontak --}}
                        <div class="col-md-12 mb-4">
                            <div class="card shadow-sm border-left-info">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="text-uppercase text-muted font-weight-bold mb-2" style="font-size: 0.8rem;">
                                                <i class="fas fa-map-marked-alt mr-1 text-danger"></i> Alamat Domisili
                                            </h6>
                                            <p class="mb-0 font-weight-bold" id="det_alamat">-</p>
                                        </div>
                                        <div class="col-md-4 border-left">
                                            <h6 class="text-uppercase text-muted font-weight-bold mb-2" style="font-size: 0.8rem;">
                                                <i class="fas fa-phone mr-1 text-success"></i> Kontak
                                            </h6>
                                            <div class="font-weight-bold" id="det_no_hp">-</div>
                                            <div class="small text-muted" id="det_email">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Orang Tua --}}
                        <div class="col-md-6">
                            <div class="info-box shadow-xs border">
                                <span class="info-box-icon bg-primary-light"><i class="fas fa-male text-primary"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted small">Nama Ayah</span>
                                    <span class="info-box-number font-weight-bold" id="det_ayah">-</span>
                                    <span class="extra-small text-muted" id="det_hp_ayah">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box shadow-xs border">
                                <span class="info-box-icon bg-danger-light"><i class="fas fa-female text-danger"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted small">Nama Ibu</span>
                                    <span class="info-box-number font-weight-bold" id="det_ibu">-</span>
                                    <span class="extra-small text-muted" id="det_hp_ibu">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-xs { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05); }
    .extra-small { font-size: 0.75rem; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    .bg-primary-light { background-color: rgba(0, 123, 255, 0.1); }
    .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
    .info-box { min-height: 70px; padding: 0.5rem; }
    .info-box .info-box-icon { width: 50px; height: 50px; border-radius: 8px; font-size: 1.2rem; }
</style>
