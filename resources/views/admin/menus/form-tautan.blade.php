<form class="formMenu" action="{{ route('menus.store') }}" method="POST">
    @csrf
    <input type="hidden" name="menu_type" value="tautan">

    <div class="form-group">
        <label>URL</label>
        <input type="text" name="menu_url" class="form-control">
    </div>

    <div class="form-group">
        <label>Link Text *</label>
        <input type="text" name="menu_title" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Target</label>
        <select class="form-control" name="menu_target">
            <option value="_self">Self</option>
            <option value="_blank">Blank</option>
            <option value="_parent">Parent</option>
            <option value="_top">Top</option>
        </select>
    </div>

    <button class="btn btn-primary w-100">
        <i class="fas fa-save mr-1"></i> Simpan Menu
    </button>
</form>
