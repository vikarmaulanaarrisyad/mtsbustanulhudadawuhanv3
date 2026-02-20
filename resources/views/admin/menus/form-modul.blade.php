<form class="formMenu" action="{{ route('menus.store') }}" method="POST">
    @csrf
    <input type="hidden" name="menu_type" value="modules">

    <div class="form-group">
        <label>Nama Menu *</label>
        <input type="text" name="menu_title" class="form-control" required>
    </div>

    <div class="form-group">
        <label>URL Menu</label>
        <input type="text" name="menu_url" class="form-control">
    </div>

    <button class="btn btn-primary w-100">
        <i class="fas fa-save mr-1"></i> Simpan Modul
    </button>
</form>
