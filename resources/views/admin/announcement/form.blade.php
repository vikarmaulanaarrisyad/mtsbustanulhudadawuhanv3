<!-- PREMIUM MODAL ANNOUNCEMENT FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')
            <input type="hidden" name="id" id="id">
            
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-indigo text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-satellite-dish mr-2"></i> <span class="modal-title-text">Form Siaran</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4 bg-light-soft">
                    
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Judul Siaran / Pengumuman <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-heading text-indigo"></i>
                            <input type="text" name="title" id="title" class="form-control border-0 font-weight-bold text-lg" placeholder="Masukkan judul pengumuman yang menarik..." required autofocus>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Target Audiens <span class="text-danger">*</span></label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-users text-indigo"></i>
                                <select name="type" id="type" class="form-control select2-premium border-0 font-weight-bold" required>
                                    <option value="Umum">🌍 Umum (Semua Orang)</option>
                                    <option value="Guru">👨‍🏫 Khusus Staf & Guru</option>
                                    <option value="Siswa">🎓 Khusus Siswa & Wali</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase">Status Publikasi <span class="text-danger">*</span></label>
                            <div class="input-group-premium bg-white">
                                <i class="fas fa-toggle-on text-indigo"></i>
                                <select name="is_active" id="is_active" class="form-control select2-premium border-0 font-weight-bold">
                                    <option value="1">✅ Terbitkan Segera (Aktif)</option>
                                    <option value="0">🕒 Simpan sbg Draf (Nonaktif)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted uppercase">Isi Pesan Siaran <span class="text-danger">*</span></label>
                        <div class="premium-textarea-wrapper bg-white">
                            <textarea name="content" id="content" rows="8" class="form-control border-0 font-weight-normal text-dark" placeholder="Tuliskan isi pengumuman secara lengkap di sini. Anda dapat menggunakan format HTML dasar seperti <b>, <i>, atau <br>..." required></textarea>
                        </div>
                        <div class="alert alert-soft-indigo border-0 rounded-10 mt-3 mb-0 d-flex align-items-center p-3 shadow-xs">
                            <i class="fas fa-lightbulb text-indigo text-lg mr-3"></i>
                            <small class="mb-0 text-indigo-dark font-weight-bold">Gunakan tag &lt;br&gt; untuk enter baris baru, dan &lt;b&gt;teks&lt;/b&gt; untuk menebalkan tulisan.</small>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-indigo rounded-pill px-5 font-weight-bold shadow-indigo-light text-white">
                        <i class="fas fa-paper-plane mr-2"></i> SIMPAN SIARAN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .shadow-lg-premium { box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05); }
    .text-indigo-dark { color: #3730a3; }
    .alert-soft-indigo { background: #e0e7ff; }
    .rounded-10 { border-radius: 10px; }
    
    /* Input Group Premium inside modal */
    #modal-form .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 50px;
    }
    #modal-form .input-group-premium i { font-size: 18px; margin-right: 15px; }
    #modal-form .input-group-premium input, #modal-form .input-group-premium select { 
        padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #1e293b; width: 100%; height: 100%; outline: none;
    }
    #modal-form .input-group-premium:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
    
    /* Premium Textarea Wrapper */
    .premium-textarea-wrapper {
        border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.3s ease; overflow: hidden;
    }
    .premium-textarea-wrapper textarea {
        padding: 20px !important; resize: vertical; box-shadow: none !important; line-height: 1.6;
    }
    .premium-textarea-wrapper:focus-within { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
    
    /* Select2 Modal Tweak */
    .select2-container--default .select2-selection--single { border: none !important; background: transparent !important; height: auto !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 0; color: #1e293b; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { display: none; }
</style>
