<!-- PREMIUM MODAL TAGS FORM -->
<div class="modal fade animate__animated animate__zoomIn" id="modal-tags" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')
            <input type="hidden" name="id" id="id">
            
            <div class="modal-content border-0 shadow-lg-premium rounded-20">
                <div class="modal-header bg-gradient-pink text-white border-0 py-4">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-hashtag mr-2"></i> <span class="modal-title-text">Label Baru</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body p-4 bg-light-soft">
                    
                    <div class="form-group mb-2">
                        <label class="text-xs font-weight-bold text-muted uppercase">Nama Tag / Label <span class="text-danger">*</span></label>
                        <div class="input-group-premium bg-white">
                            <i class="fas fa-hashtag text-pink opacity-50"></i>
                            <input type="text" autocomplete="off" name="tag_name" id="tag_name" class="form-control border-0 font-weight-bold text-lg text-dark" placeholder="nama_label..." required autofocus>
                        </div>
                        <small class="text-muted d-block mt-2 ml-1">Slug URL akan dibuat secara otomatis berdasarkan nama label.</small>
                    </div>
                    
                </div>
                
                <div class="modal-footer border-0 p-4 bg-white">
                    <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold shadow-xs" data-dismiss="modal">BATAL</button>
                    <button type="button" onclick="submitForm(this.form)" class="btn btn-pink rounded-pill px-5 font-weight-bold shadow-pink-light text-white" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> SIMPAN LABEL
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .shadow-lg-premium { box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05); }
    .bg-gradient-pink { background: linear-gradient(135deg, #ec4899 0%, #db2777 100%) !important; }
    
    /* Input Group Premium inside modal */
    #modal-tags .input-group-premium { 
        display: flex; align-items: center; border: 2px solid #e2e8f0; 
        border-radius: 12px; padding: 0 15px; transition: all 0.3s ease; height: 50px;
    }
    #modal-tags .input-group-premium i { font-size: 18px; margin-right: 15px; }
    #modal-tags .input-group-premium input { 
        padding: 0 !important; background: transparent !important; 
        box-shadow: none !important; color: #1e293b; width: 100%; height: 100%; outline: none;
    }
    #modal-tags .input-group-premium:focus-within { border-color: #ec4899; box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1); }
</style>
