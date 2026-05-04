{{-- MODAL VERIFIKASI BERKAS PREMIUM --}}
<div class="modal fade animate__animated animate__fadeIn" id="modal-verify" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg-premium" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-gradient-success text-white border-0 py-3 px-4">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-clipboard-check mr-2"></i> Panel Verifikasi Berkas
                </h5>
                <button type="button" class="close text-white opacity-8" data-dismiss="modal"><span>&times;</span></button>
            </div>
            
            <form onsubmit="event.preventDefault(); submitVerify(this);">
                @csrf
                <div class="modal-body p-4 bg-light-soft">
                    {{-- INFO PENDAFTAR COMPACT --}}
                    <div class="alert alert-soft-success border-0 shadow-sm rounded-15 mb-4 p-3">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <span class="text-xs font-weight-bold text-muted uppercase d-block mb-1">Nama Pendaftar</span>
                                <h5 class="font-weight-bold text-dark mb-0" id="verify_nama">-</h5>
                            </div>
                            <div class="col-md-5 text-md-right mt-2 mt-md-0">
                                <span class="text-xs font-weight-bold text-muted uppercase d-block mb-1">Nomor Registrasi</span>
                                <span class="badge badge-indigo px-3 py-2 rounded-pill font-weight-bold shadow-sm" id="verify_reg_no">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- DAFTAR BERKAS DOKUMEN --}}
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-folder-open mr-2 text-success"></i> Validasi Kelengkapan Dokumen
                    </h6>
                    <div class="card border-0 shadow-sm rounded-15 overflow-hidden mb-4">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light text-uppercase text-xs font-weight-bold text-muted">
                                    <tr>
                                        <th class="px-4 py-3">Nama Berkas</th>
                                        <th width="15%" class="text-center py-3">Pratinjau</th>
                                        <th width="12%" class="text-center py-3">Valid?</th>
                                        <th width="35%" class="py-3">Catatan Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody id="verify_docs_tbody">
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Memuat data berkas...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- NILAI & JARAK (KRITERIA SELEKSI) --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm rounded-15 p-3 bg-white h-100">
                                <label class="text-xs font-weight-bold text-muted uppercase mb-2">Rata-rata Nilai Rapor</label>
                                <div class="input-group-premium shadow-sm mb-1">
                                    <i class="fas fa-chart-bar text-success"></i>
                                    <input type="number" step="0.01" name="average_score" id="verify_score" class="form-control" placeholder="0.00">
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Digunakan untuk kriteria Jalur Prestasi</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm rounded-15 p-3 bg-white h-100">
                                <label class="text-xs font-weight-bold text-muted uppercase mb-2">Jarak Rumah (KM)</label>
                                <div class="input-group-premium shadow-sm mb-1">
                                    <i class="fas fa-route text-success"></i>
                                    <input type="number" step="0.01" name="distance_km" id="verify_distance" class="form-control" placeholder="0.00">
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Digunakan untuk kriteria Jalur Zonasi</small>
                            </div>
                        </div>
                    </div>

                    {{-- KEPUTUSAN FINAL --}}
                    <div class="card border-0 shadow-sm rounded-15 p-4 bg-white">
                        <h6 class="font-weight-bold text-dark mb-4"><i class="fas fa-gavel mr-2 text-indigo"></i> Keputusan Akhir Panitia</h6>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group mb-0">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Status Verifikasi <span class="text-danger">*</span></label>
                                    <select name="status" id="verify_status" class="form-control rounded-pill border-2 px-3 font-weight-bold">
                                        <option value="pending">Menunggu Verifikasi</option>
                                        <option value="berkas_lengkap">Berkas Lengkap</option>
                                        <option value="berkas_tidak_lengkap">Berkas Tidak Lengkap</option>
                                        <option value="diterima">✅ LULUS / DITERIMA</option>
                                        <option value="ditolak">❌ TIDAK LULUS / DITOLAK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7 mt-3 mt-md-0">
                                <div class="form-group mb-0">
                                    <label class="text-xs font-weight-bold text-muted uppercase">Catatan / Alasan</label>
                                    <textarea name="catatan_verifikasi" id="verify_catatan" class="form-control rounded-15 border-2" rows="3" placeholder="Contoh: Berkas sudah sesuai, layak untuk dilanjutkan ke tahap berikutnya."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white border-0 py-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs mr-2" data-dismiss="modal">BATALKAN</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 font-weight-bold shadow-success-light" id="submitVerifyBtn">
                        <i class="fas fa-check-circle mr-2"></i> SIMPAN HASIL VERIFIKASI
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Premium Verify Styling */
    .bg-gradient-success { background: linear-gradient(135deg, #059669 0%, #064e3b 100%) !important; }
    .alert-soft-success { background: #ecfeff; border: 1px solid #cffafe; }
    .shadow-success-light { box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4); }
    .rounded-15 { border-radius: 15px; }
    .rounded-20 { border-radius: 20px; }
    
    .badge-indigo { background: #4f46e5; color: #fff; }
    
    /* Input Group Premium Success */
    .input-group-premium { 
        display: flex; align-items: center; background: #fff; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease;
    }
    .input-group-premium i { color: #94a3b8; font-size: 16px; margin-right: 12px; }
    .input-group-premium .form-control { 
        border: none !important; padding: 12px 0 !important; background: transparent !important; 
        box-shadow: none !important; font-weight: 600; color: #334155; width: 100%;
    }
    .input-group-premium:focus-within { border-color: #10b981; box-shadow: 0 0 15px rgba(16, 185, 129, 0.1); }
</style>
