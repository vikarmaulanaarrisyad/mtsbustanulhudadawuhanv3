<!-- PREMIUM MODAL QUOTES FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-quotes" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')
            <input type="hidden" name="id" id="id">
            
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-cyan text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-feather-alt mr-2"></i> <span class="modal-title-text">Kutipan Baru</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4 bg-light-soft">
                    
                    <div class="form-group mb-4">
                        <label class="text-xs font-weight-bold text-muted uppercase">Dikutip Dari (Tokoh / Sumber) <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-user-edit text-cyan opacity-50"></i>
                            <input type="text" autocomplete="off" name="quote_by" id="quote_by" class="form-control border-0 font-weight-bold text-lg text-dark" placeholder="Contoh: Albert Einstein, Al-Ghazali, dll..." required autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted uppercase">Isi Kata Mutiara / Motivasi <span class="text-danger">*</span></label>
                        <div class="premium-textarea-wrapper bg-white">
                            <textarea id="quote" name="quote" class="form-control quote summernote border-0" rows="10" placeholder="Tuliskan kata mutiaranya di sini..."></textarea>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" class="btn btn-cyan rounded-pill px-5 font-weight-bold shadow-cyan-light text-white" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> SIMPAN KUTIPAN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .shadow-lg-premium { box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05); }
    .bg-gradient-cyan { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%) !important; }
    
    /* Input Group Premium inside modal */
    #modal-quotes .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 50px;
    }
    #modal-quotes .input-group-premium i { font-size: 18px; margin-right: 15px; }
    #modal-quotes .input-group-premium input { 
        padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #1e293b; width: 100%; height: 100%; outline: none;
    }
    #modal-quotes .input-group-premium:focus-within { border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
    
    /* Premium Textarea / Summernote Wrapper */
    .premium-textarea-wrapper {
        border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.3s ease; overflow: hidden;
    }
    .premium-textarea-wrapper:focus-within { border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
    
    /* Summernote specific override to fit inside premium wrapper */
    .note-editor.note-frame { border: none !important; margin-bottom: 0 !important; }
    .note-toolbar { background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
</style>
