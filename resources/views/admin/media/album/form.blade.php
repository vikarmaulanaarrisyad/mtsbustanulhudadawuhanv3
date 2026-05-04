<!-- PREMIUM MODAL ALBUM FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <input type="hidden" name="id" id="id">
            
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-studio text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-folder-plus mr-2"></i> <span class="modal-title-text">Buat Album Baru</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4 bg-light-soft">
                    
                    <div class="row">
                        <!-- BAGIAN KIRI: UPLOAD COVER -->
                        <div class="col-md-5 mb-4 mb-md-0">
                            <label class="text-xs font-weight-bold text-muted uppercase"><i class="fas fa-image text-studio mr-1"></i> Sampul Album Utama <span class="text-danger">*</span></label>
                            <div class="file-drop-area border-dashed rounded-20 p-4 text-center bg-white position-relative transition-all h-100 d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-studio opacity-50 mb-3"></i>
                                <span class="d-block font-weight-bold text-dark mb-1">Pilih Sampul Album</span>
                                <span class="text-muted small">JPG, PNG, WEBP<br>(Max: 2MB)</span>
                                <input id="album_cover" class="file-input-invisible" type="file" name="album_cover" autocomplete="off" accept="image/*">
                                <div id="file-name-display" class="mt-3 text-studio font-weight-bold text-sm bg-light-studio px-3 py-2 rounded-pill" style="display:none; word-break: break-all;"></div>
                            </div>
                        </div>

                        <!-- BAGIAN KANAN: DETAIL ALBUM -->
                        <div class="col-md-7">
                            <div class="form-group mb-4">
                                <label class="text-xs font-weight-bold text-muted uppercase">Judul Album <span class="text-danger">*</span></label>
                                <div class="input-group-premium bg-white">
                                    <i class="fas fa-book text-studio opacity-50"></i>
                                    <input type="text" autocomplete="off" name="album_title" id="album_title" class="form-control border-0 font-weight-bold text-lg text-dark" placeholder="Contoh: Wisuda Angkatan 2024..." required autofocus>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label class="text-xs font-weight-bold text-muted uppercase">Keterangan Singkat</label>
                                <div class="premium-textarea-wrapper bg-white">
                                    <textarea id="summernote" name="album_description" class="form-control summernote border-0" rows="8" placeholder="Ceritakan sedikit tentang isi album ini..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" class="btn btn-studio rounded-pill px-5 font-weight-bold shadow-studio-light text-white" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> SIMPAN ALBUM
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .shadow-lg-premium { box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05); }
    .bg-gradient-studio { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important; }
    
    /* File Drop Area */
    .file-drop-area { border: 2px dashed #cbd5e1; cursor: pointer; transition: all 0.3s ease; }
    .file-drop-area:hover { border-color: #1e293b; background: #f1f5f9 !important; }
    .file-input-invisible { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    
    /* Input Group Premium inside modal */
    #modal-form .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 50px;
    }
    #modal-form .input-group-premium i { font-size: 18px; margin-right: 15px; }
    #modal-form .input-group-premium input { 
        padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #1e293b; width: 100%; height: 100%; outline: none;
    }
    #modal-form .input-group-premium:focus-within { border-color: #1e293b; box-shadow: 0 0 0 4px rgba(30, 41, 59, 0.1); }

    /* Premium Textarea / Summernote Wrapper */
    .premium-textarea-wrapper {
        border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.3s ease; overflow: hidden;
    }
    .premium-textarea-wrapper:focus-within { border-color: #1e293b; box-shadow: 0 0 0 4px rgba(30, 41, 59, 0.1); }
    
    /* Summernote specific override */
    .note-editor.note-frame { border: none !important; margin-bottom: 0 !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
</style>

<script>
    // File input visual feedback
    document.getElementById('album_cover').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : '';
        var displayNode = document.getElementById('file-name-display');
        if (fileName) {
            displayNode.innerHTML = '<i class="fas fa-check mr-1"></i> ' + fileName;
            displayNode.style.display = 'block';
            document.querySelector('.file-drop-area').style.borderColor = '#1e293b';
            document.querySelector('.file-drop-area').style.backgroundColor = '#f1f5f9';
        } else {
            displayNode.style.display = 'none';
        }
    });

    // Reset Dropzone on modal close
    $('#modal-form').on('hidden.bs.modal', function () {
        document.getElementById('file-name-display').style.display = 'none';
        document.querySelector('.file-drop-area').style.borderColor = '#cbd5e1';
        document.querySelector('.file-drop-area').style.backgroundColor = '#fff';
    });
</script>
