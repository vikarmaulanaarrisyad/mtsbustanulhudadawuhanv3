<!-- PREMIUM MODAL IMAGE SLIDER FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <input type="hidden" name="id" id="id">
            
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-violet text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-camera-retro mr-2"></i> <span class="modal-title-text">Unggah Gambar Slide</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4 bg-light-soft">
                    
                    <div class="row">
                        <div class="col-12 mb-4">
                            <label class="text-xs font-weight-bold text-muted uppercase"><i class="fas fa-cloud-upload-alt text-violet mr-1"></i> Aset File Gambar <span class="text-danger">*</span></label>
                            <div class="file-drop-area border-dashed rounded-20 p-4 text-center bg-white position-relative transition-all">
                                <i class="fas fa-images fa-3x text-violet opacity-50 mb-3 d-block"></i>
                                <span class="d-block font-weight-bold text-dark mb-1">Klik atau Tarik file ke sini</span>
                                <span class="text-muted small">Mendukung format JPG, PNG, WEBP (Max: 2MB)</span>
                                <input id="image" class="file-input-invisible" type="file" name="image" autocomplete="off" accept="image/*">
                                <div id="file-name-display" class="mt-3 text-violet font-weight-bold text-sm" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted uppercase"><i class="fas fa-quote-left text-violet mr-1"></i> Caption / Keterangan Gambar</label>
                        <div class="premium-textarea-wrapper bg-white">
                            <textarea id="summernote" name="caption" class="form-control summernote border-0" rows="15" placeholder="Tuliskan keterangan mengenai gambar ini..."></textarea>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" class="btn btn-violet rounded-pill px-5 font-weight-bold shadow-violet-light text-white" id="submitBtn">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> SIMPAN GAMBAR
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .shadow-lg-premium { box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05); }
    .bg-gradient-violet { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%) !important; }
    
    /* File Drop Area */
    .file-drop-area { border: 2px dashed #cbd5e1; cursor: pointer; transition: all 0.3s ease; }
    .file-drop-area:hover { border-color: #8b5cf6; background: #f5f3ff !important; }
    .file-input-invisible { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    
    /* Premium Textarea / Summernote Wrapper */
    .premium-textarea-wrapper {
        border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.3s ease; overflow: hidden;
    }
    .premium-textarea-wrapper:focus-within { border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1); }
    
    /* Summernote specific override to fit inside premium wrapper */
    .note-editor.note-frame { border: none !important; margin-bottom: 0 !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
</style>

<script>
    // File input visual feedback
    document.getElementById('image').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : '';
        var displayNode = document.getElementById('file-name-display');
        if (fileName) {
            displayNode.innerHTML = '<i class="fas fa-check-circle mr-1"></i> File terpilih: ' + fileName;
            displayNode.style.display = 'block';
            document.querySelector('.file-drop-area').style.borderColor = '#8b5cf6';
            document.querySelector('.file-drop-area').style.backgroundColor = '#f5f3ff';
        } else {
            displayNode.style.display = 'none';
        }
    });

    // Reset file display on modal close
    $('#modal-form').on('hidden.bs.modal', function () {
        document.getElementById('file-name-display').style.display = 'none';
        document.querySelector('.file-drop-area').style.borderColor = '#cbd5e1';
        document.querySelector('.file-drop-area').style.backgroundColor = '#fff';
    });
</script>
