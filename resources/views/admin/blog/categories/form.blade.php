<x-modal id="modal-categories" data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah Pendaftaran
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="category_name" class="form-label">Nama Kategori</label>
                <input type="text" autocomplete="off" name="category_name" id="category_name" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="category_description" class="form-label fw-semibold">Deskripsi</label>
                <textarea id="category_description" name="category_description" class="form-control category_description summernote"
                    rows="20" cols="20" placeholder="Tulis di sini..."></textarea>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
