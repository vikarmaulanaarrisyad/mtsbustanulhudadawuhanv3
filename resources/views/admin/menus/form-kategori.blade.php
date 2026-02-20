<form class="formMenu" action="{{ route('menus.store') }}" method="POST">
    @csrf
    <input type="hidden" name="menu_type" value="categories">

    <div class="form-group">
        <label>Pilih Kategori</label>
        <select name="menu_url" class="form-control">
            <option value="" disabled selected>Pilih Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->category_slug }}">{{ $category->category_name }}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary w-100">
        <i class="fas fa-save mr-1"></i> Simpan Menu
    </button>
</form>
