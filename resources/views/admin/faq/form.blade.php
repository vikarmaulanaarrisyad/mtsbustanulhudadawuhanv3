<x-modal data-backdrop="static" data-keyboard="false" size="modal-md" id="modal-faq">
    <x-slot name="title">
        <span class="modal-title-text font-weight-bold text-dark"><i class="fas fa-question-circle text-cyan mr-2"></i> Tambah FAQ</span>
    </x-slot>

    @method('POST')
    <div class="p-3">
        <!-- Question -->
        <div class="form-group mb-4">
            <label for="question" class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                Pertanyaan <span class="text-danger">*</span>
            </label>
            <textarea name="question" id="question" rows="2" class="form-control" placeholder="Tuliskan pertanyaan..." style="border-radius: 8px; border: 1px solid #e2e8f0;"></textarea>
            <small class="text-muted"><i class="fas fa-info-circle"></i> Pertanyaan yang sering diajukan pengunjung.</small>
        </div>

        <!-- Answer -->
        <div class="form-group mb-4">
            <label for="answer" class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                Jawaban <span class="text-danger">*</span>
            </label>
            <textarea name="answer" id="answer" rows="4" class="form-control" placeholder="Tuliskan jawaban yang informatif..." style="border-radius: 8px; border: 1px solid #e2e8f0;"></textarea>
            <small class="text-muted"><i class="fas fa-info-circle"></i> Jawaban untuk pertanyaan di atas.</small>
        </div>

        <!-- Position -->
        <div class="form-group mb-4">
            <label for="position" class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                Posisi Urutan
            </label>
            <input type="number" name="position" id="position" class="form-control" placeholder="0" value="0" style="border-radius: 8px; border: 1px solid #e2e8f0;">
            <small class="text-muted"><i class="fas fa-info-circle"></i> Semakin kecil angkanya, semakin di atas tampilannya.</small>
        </div>

        <!-- Active Status -->
        <div class="form-group mb-0">
            <div class="custom-control custom-switch custom-switch-lg custom-switch-success">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                <label class="custom-control-label font-weight-bold text-dark" for="is_active" style="padding-top: 4px; cursor: pointer;">
                    Aktifkan FAQ Ini
                </label>
            </div>
            <small class="text-muted d-block mt-2"><i class="fas fa-eye"></i> Jika dinonaktifkan, FAQ ini tidak akan muncul di halaman depan.</small>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> BATAL
        </button>
        <button type="button" onclick="submitForm(this.form)" class="btn btn-cyan rounded-pill px-4 font-weight-bold shadow-cyan-light" id="submitBtn">
            <i class="fas fa-save mr-1"></i> SIMPAN FAQ
        </button>
    </x-slot>
</x-modal>
