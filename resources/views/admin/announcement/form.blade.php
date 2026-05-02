<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')
            <input type="hidden" name="id" id="id">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="title" class="col-md-2 col-md-offset-1 control-label">Judul</label>
                        <div class="col-md-10">
                            <input type="text" name="title" id="title" class="form-control" required autofocus>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type" class="col-md-2 col-md-offset-1 control-label">Kategori</label>
                        <div class="col-md-10">
                            <select name="type" id="type" class="form-control" required>
                                <option value="Umum">Umum (Semua)</option>
                                <option value="Guru">Khusus Guru</option>
                                <option value="Siswa">Khusus Siswa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-md-2 col-md-offset-1 control-label">Isi</label>
                        <div class="col-md-10">
                            <textarea name="content" id="content" rows="10" class="form-control" required></textarea>
                            <small class="text-muted">Gunakan format HTML sederhana untuk paragraf.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="is_active" class="col-md-2 col-md-offset-1 control-label">Status</label>
                        <div class="col-md-10">
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
