@foreach ($menus->where('menu_parent_id', $parent_id ?? 0) as $menu)
    <li data-id="{{ $menu->id }}" id="menu_{{ $menu->id }}">
        <span class="handle mr-2"><i class="fas fa-grip-vertical"></i></span>
        {{ $menu->menu_title }}
        @if ($menus->where('menu_parent_id', $menu->id)->count())
            <ul>
                @include('admin.menus.menu-list', ['menus' => $menus, 'parent_id' => $menu->id])
            </ul>
        @endif
    </li>
@endforeach
