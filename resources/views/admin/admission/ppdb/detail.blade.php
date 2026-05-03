{{-- MODAL DETAIL PENDAFTAR --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info shadow-sm">
                <h5 class="modal-title text-white font-weight-bold">
                    <i class="fas fa-user-graduate mr-2"></i> Detail Pendaftar PPDB
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                {{-- Profile Header --}}
                <div class="p-4 bg-light border-bottom d-flex align-items-center">
                    <div class="mr-4">
                        <img id="det_foto" src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}"
                            class="img-thumbnail rounded-circle shadow-sm" style="width:120px;height:120px;object-fit:cover;border: 4px solid #fff;">
                    </div>
                    <div>
                        <h4 class="mb-1 font-weight-bold text-dark" id="det_nama">-</h4>
                        <div class="badge badge-primary px-3 py-2" id="det_reg_no" style="font-size: 1rem;">-</div>
                        <div class="mt-2 text-muted small">
                            <i class="fas fa-school mr-1"></i> <span id="det_asal">-</span>
                        </div>
                    </div>
                </div>

                {{-- Stepper Admin --}}
                <div class="px-4 py-3 bg-white border-bottom">
                    <div class="ppdb-stepper" id="det_stepper">
                        <div class="step" id="step_1">
                            <div class="step-icon shadow-xs"><i class="fas fa-file-alt"></i></div>
                            <div class="step-label">Pendaftaran</div>
                        </div>
                        <div class="step-line" id="line_1"></div>
                        <div class="step" id="step_2">
                            <div class="step-icon shadow-xs"><i class="fas fa-clipboard-check"></i></div>
                            <div class="step-label">Verifikasi</div>
                        </div>
                        <div class="step-line" id="line_2"></div>
                        <div class="step" id="step_3">
                            <div class="step-icon shadow-xs"><i class="fas fa-user-graduate"></i></div>
                            <div class="step-label">Seleksi</div>
                        </div>
                        <div class="step-line" id="line_3"></div>
                        <div class="step" id="step_4">
                            <div class="step-icon shadow-xs"><i class="fas fa-award"></i></div>
                            <div class="step-label">Hasil</div>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <div class="row">
                        {{-- Data Pribadi --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-user mr-2 text-info"></i> Data Pribadi
                            </h6>
                            <div class="list-group list-group-flush border rounded shadow-sm">
                                <div class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted small">NISN</span>
                                    <span class="font-weight-bold" id="det_nisn">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted small">NIK</span>
                                    <span class="font-weight-bold" id="det_nik">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted small">Jenis Kelamin</span>
                                    <span class="font-weight-bold" id="det_jk">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between border-bottom-0">
                                    <span class="text-muted small">TTL</span>
                                    <span class="font-weight-bold" id="det_ttl">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Orang Tua & Kontak --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-users mr-2 text-success"></i> Orang Tua & Kontak
                            </h6>
                            <div class="list-group list-group-flush border rounded shadow-sm">
                                <div class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted small">Nama Ayah</span>
                                    <span class="font-weight-bold" id="det_ayah">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted small">Nama Ibu</span>
                                    <span class="font-weight-bold" id="det_ibu">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted small">No. HP (WhatsApp)</span>
                                    <span class="font-weight-bold text-success" id="det_hp">-</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between border-bottom-0">
                                    <span class="text-muted small">Alamat</span>
                                    <span class="font-weight-bold text-right ml-3" id="det_alamat">-</span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Pendaftaran --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-info-circle mr-2 text-primary"></i> Info Pendaftaran
                            </h6>
                            <div class="card shadow-sm border">
                                <div class="card-body p-3">
                                    <div class="row text-center">
                                        <div class="col-6 border-right">
                                            <div class="text-muted small mb-1">Gelombang</div>
                                            <div class="font-weight-bold text-primary" id="det_gelombang">-</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-muted small mb-1">Jalur</div>
                                            <div class="font-weight-bold text-primary" id="det_jalur">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Status Verifikasi --}}
                        <div class="col-md-6 mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-clipboard-check mr-2 text-warning"></i> Status Verifikasi
                            </h6>
                            <div class="card shadow-sm border bg-light h-100">
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <span class="text-muted small d-block">Catatan:</span>
                                        <span class="font-italic small" id="det_catatan">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <span class="text-muted extra-small" id="det_verified_at">-</span>
                                        <span class="badge badge-light shadow-xs border" id="det_verifier">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bukti Pembayaran (Daftar Ulang) --}}
                        <div class="col-md-12 mb-4 d-none" id="det_payment_section">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-money-check-alt mr-2 text-success"></i> Bukti Daftar Ulang
                            </h6>
                            <div class="card shadow-sm border border-success">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-4 text-center">
                                            <a id="det_payment_link" href="#" target="_blank">
                                                <img id="det_payment_img" src="" class="img-thumbnail shadow-sm" style="max-height: 150px;">
                                            </a>
                                        </div>
                                         <div class="col-md-8">
                                             <div class="alert alert-success-light border-0 mb-0">
                                                 <h6 class="font-weight-bold mb-1 text-success">Konfirmasi Daftar Ulang Diterima</h6>
                                                 <p class="small mb-3">Pendaftar telah mengunggah bukti pembayaran pada <span id="det_payment_date" class="font-weight-bold">-</span>.</p>
                                                 
                                                 <div id="payment_status_verified" class="d-none">
                                                     <div class="d-flex align-items-center text-success small font-weight-bold">
                                                         <i class="fas fa-check-circle mr-2 fa-lg"></i> Pembayaran Telah Divalidasi
                                                     </div>
                                                 </div>

                                                 <div id="payment_status_pending" class="d-none">
                                                     <button type="button" id="btn-verify-payment" class="btn btn-success btn-sm px-3 shadow-sm">
                                                         <i class="fas fa-check-circle mr-1"></i> Verifikasi Pembayaran
                                                     </button>
                                                 </div>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Berkas Dokumen --}}
                        <div class="col-md-12">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="letter-spacing: 1px;">
                                <i class="fas fa-folder-open mr-2 text-purple"></i> Berkas Dokumen
                            </h6>
                            <div id="det_docs_container" class="row">
                                {{-- Berkas akan dimuat via JS --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top">
                <a id="btn-print-letter" href="#" target="_blank" class="btn btn-primary px-4 shadow-sm d-none">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak SK
                </a>
                <button id="btn-move-student" type="button" class="btn btn-success px-4 shadow-sm d-none">
                    <i class="fas fa-user-check mr-1"></i> Pindahkan ke Data Siswa
                </button>
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .extra-small { font-size: 0.75rem; }
    .text-purple { color: #6f42c1; }
    #det_alamat { max-width: 250px; line-height: 1.2; }
    .shadow-xs { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05); }
    .alert-success-light { background-color: rgba(40, 167, 69, 0.05); border: 1px solid rgba(40, 167, 69, 0.1); }
</style>
