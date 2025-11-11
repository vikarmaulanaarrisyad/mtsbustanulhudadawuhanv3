@foreach ($menus->where('menu_parent_id', $parent_id ?? 0) as $menu)
    <li data-id="{{ $menu->id }}" id="menu_{{ $menu->id }}">
        <span class="handle mr-2"><i class="fas fa-grip-vertical"></i></span>
        {{ $menu->menu_title }}
        <div class="tools float-right">
            <i class="fas fa-edit text-primary" onclick="editForm('{{ route('menus.show', $menu->id) }}')"></i>
            <i class="fas fa-trash-alt text-danger"
                onclick="deleteData('{{ route('menus.destroy', $menu->id) }}', '{{ $menu->menu_title }}')"></i>
        </div>

        @if ($menus->where('menu_parent_id', $menu->id)->count())
            <ul>
                @include('admin.menus.menu-list', ['menus' => $menus, 'parent_id' => $menu->id])
            </ul>
        @endif
    </li>
@endforeach
