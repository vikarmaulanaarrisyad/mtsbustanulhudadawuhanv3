@foreach ($menus->where('menu_parent_id', $parent_id ?? 0) as $menu)
    <li data-id="{{ $menu->id }}" id="menu_{{ $menu->id }}">
        <span>
            <i class="fas fa-grip-vertical handle"></i>
            <span class="menu-title-text">{{ $menu->menu_title }}</span>
            <span class="menu-url-text">{{ $menu->menu_url ?? $menu->menu_slug }}</span>
            
            <div class="tools">
                <button type="button" class="btn-tool btn-tool-delete" title="Hapus" onclick="deleteData('{{ route('menus.destroy', $menu->id) }}', '{{ $menu->menu_title }}')">
                    <i class="fas fa-trash text-xs"></i>
                </button>
            </div>
        </span>
        @if ($menus->where('menu_parent_id', $menu->id)->count())
            <ul>
                @include('admin.menus.menu-list', ['menus' => $menus, 'parent_id' => $menu->id])
            </ul>
        @endif
    </li>
@endforeach
