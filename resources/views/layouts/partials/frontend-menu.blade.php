@foreach ($menus as $menu)
    @php
        $children = \App\Models\Menu::where('menu_parent_id', $menu->id)
            ->orderBy('menu_position')
            ->get();

        if ($menu->menu_type === 'link') {
            $url = $menu->menu_url;
        } else {
            $url = route('front.handle', $menu->menu_slug);
        }

        $isActive = request()->is($menu->menu_slug . '*');
        
        // Cek jika ada child yang aktif
        $hasActiveChild = false;
        foreach($children as $child) {
            if(request()->is($child->menu_slug . '*')) {
                $hasActiveChild = true;
                break;
            }
        }
        if($hasActiveChild) {
            $isActive = true;
        }
    @endphp

    @if ($children->count() > 0)
        @if ($menu->menu_parent_id == 0)
            {{-- Level 1 dengan anak --}}
            <li class="nav-item dropdown {{ $isActive ? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="{{ $url }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target="{{ $menu->menu_target }}">
                    {{ $menu->menu_title }}
                </a>
                <div class="dropdown-menu">
                    @include('layouts.partials.frontend-menu', ['menus' => $children])
                </div>
            </li>
        @else
            {{-- Level 2+ dengan anak --}}
            <div class="dropdown-submenu position-relative">
                <a class="dropdown-item dropdown-toggle {{ $isActive ? 'active' : '' }}" href="{{ $url }}" target="{{ $menu->menu_target }}">
                    {{ $menu->menu_title }}
                </a>
                <div class="dropdown-menu">
                    @include('layouts.partials.frontend-menu', ['menus' => $children])
                </div>
            </div>
        @endif
    @else
        @if ($menu->menu_parent_id == 0)
            {{-- Level 1 tanpa anak --}}
            <li class="nav-item {{ $isActive ? 'active' : '' }}">
                <a class="nav-link" href="{{ $url }}" target="{{ $menu->menu_target }}">
                    {{ $menu->menu_title }}
                </a>
            </li>
        @else
            {{-- Level 2+ tanpa anak --}}
            <a class="dropdown-item {{ $isActive ? 'active' : '' }}" href="{{ $url }}" target="{{ $menu->menu_target }}">
                {{ $menu->menu_title }}
            </a>
        @endif
    @endif
@endforeach
