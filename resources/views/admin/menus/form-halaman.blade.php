<form class="formMenu" action="{{ route('menus.store') }}" method="POST">
    @csrf
    <input type="hidden" name="menu_type" value="pages">

    <div class="form-group">
        <label>Pilih Halaman</label>
        <select name="menu_url" class="form-control">
            <option value="" disabled selected>Pilih Halaman</option>
            @foreach ($pages as $page)
                <option value="{{ $page->slug }}">{{ $page->title }}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-primary w-100">
        <i class="fas fa-save mr-1"></i> Simpan Menu
    </button>
</form>
