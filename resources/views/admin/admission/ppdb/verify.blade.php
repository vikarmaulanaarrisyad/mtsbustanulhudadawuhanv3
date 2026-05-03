{{-- MODAL VERIFIKASI BERKAS --}}
<div class="modal fade" id="modal-verify" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="fas fa-clipboard-check mr-1"></i> Verifikasi Berkas</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form onsubmit="event.preventDefault(); submitVerify(this);">
                @csrf
                <div class="modal-body">
                    {{-- Info Pendaftar --}}
                    <div class="callout callout-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nama:</strong> <span id="verify_nama">-</span>
                            </div>
                            <div class="col-md-6">
                                <strong>No. Pendaftaran:</strong> <span id="verify_reg_no">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Berkas --}}
                    <h6 class="font-weight-bold mb-2"><i class="fas fa-folder-open mr-1"></i> Daftar Berkas</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Berkas</th>
                                <th width="15%" class="text-center">Preview</th>
                                <th width="10%" class="text-center">Valid?</th>
                                <th width="30%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="verify_docs_tbody">
                            <tr><td colspan="4" class="text-center text-muted">Memuat...</td></tr>
                        </tbody>
                    </table>

                    <hr>

                    {{-- Nilai & Jarak --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="verify_score"><strong>Rata-rata Nilai Rapor</strong></label>
                                <input type="number" step="0.01" name="average_score" id="verify_score" class="form-control" placeholder="0.00">
                                <small class="text-muted">Gunakan untuk Jalur Prestasi</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="verify_distance"><strong>Jarak Rumah (KM)</strong></label>
                                <input type="number" step="0.01" name="distance_km" id="verify_distance" class="form-control" placeholder="0.00">
                                <small class="text-muted">Gunakan untuk Jalur Zonasi</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Status Verifikasi --}}
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="verify_status"><strong>Status Keseluruhan</strong> <span class="text-danger">*</span></label>
                                <select name="status" id="verify_status" class="form-control">
                                    <option value="pending">Menunggu Verifikasi</option>
                                    <option value="berkas_lengkap">Berkas Lengkap</option>
                                    <option value="berkas_tidak_lengkap">Berkas Tidak Lengkap</option>
                                    <option value="diterima">✅ Diterima</option>
                                    <option value="ditolak">❌ Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="verify_catatan"><strong>Catatan Verifikasi</strong></label>
                                <textarea name="catatan_verifikasi" id="verify_catatan" class="form-control" rows="3" placeholder="Tambahkan catatan verifikasi..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-check-circle mr-1"></i> Simpan Verifikasi
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
