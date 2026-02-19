<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->company_name ?? '' }}</title>

    {{-- Bootstrap & Font Awesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta name="description" content="{{ $setting->nama_aplikasi }}" />

    <!-- FAVICONS ICON ============================================= -->
    <link rel="icon" href="{{ $setting->path_image }}" type="image/x-icon" />
    <link rel="icon" href="{{ Storage::url($setting->path_image ?? '') }}" type="image/*">
    <link rel="stylesheet" href="{{ asset('/public/css/mycss.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/custom.css') }}">



    @stack('css')

</head>

<body>

    {{-- Header Atas --}}
    {{--  <div class="topbar d-flex justify-content-between align-items-center">
        <div class="topbar">
            <img src="{{ Storage::url($setting->path_image_header) }}" alt="Logo">
        </div>
        <div class="topbar-info">
            <i class="fa fa-map-marker-alt"></i> {{ $setting->address }}
            <span class="ml-3"><i class="fa fa-phone"></i> {{ $setting->phone }}</span>
            <span class="ml-3"><i class="fa fa-envelope"></i> {{ $setting->email }}</span>
            <a href="{{ $setting->fanpage_link }}"><i class="fab fa-facebook-f"></i></a>
            <a href="{{ $setting->instagram_link }}"><i class="fab fa-instagram"></i></a>
            <a href="{{ $setting->twitter_link }}"><i class="fab fa-youtube"></i></a>
        </div>

    </div>  --}}

    @php
        $menus = App\Models\Menu::where('menu_parent_id', 0)->orderBy('menu_position')->get();
    @endphp

    {{-- Navbar --}}

    <nav class="navbar navbar-expand-sm sticky-top fixed-top navbar-light bg-white border-bottom">

        <a class="navbar-brand font-weight-bold text-success" href="{{ url('/') }}">
            <img src="{{ Storage::url($setting->path_image_header) }}" alt="Logo" class="logo-image">
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler" type="button" id="mobileMenuToggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Overlay -->
        <div class="offcanvas-overlay" id="offcanvasOverlay"></div>
        <!-- MENU -->
        <div class="collapse navbar-collapse offcanvas-menu" id="navbar1">
            <ul class="navbar-nav ml-auto mr-5">
                @foreach ($menus as $menu)
                    @php
                        $children = \App\Models\Menu::where('menu_parent_id', $menu->id)
                            ->orderBy('menu_position')
                            ->get();

                        // Tentukan URL berdasarkan menu_type
                        if ($menu->menu_type === 'pages' || $menu->menu_type === 'modul') {
                            $url = route('front.handle', $menu->menu_slug);
                        } elseif ($menu->menu_type === 'link') {
                            $url = $menu->menu_url; // langsung ke external
                        } else {
                            $url = '#';
                        }

                        // cek active: cocok dengan slug sekarang
                        $isActive = request()->is($menu->menu_slug . '*');
                    @endphp

                    @if ($children->count() > 0)
                        <li class="nav-item dropdown {{ $isActive ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                {{ $menu->menu_title }}
                            </a>
                            <div class="dropdown-menu">
                                @foreach ($children as $child)
                                    @php
                                        if ($child->menu_type === 'pages' || $child->menu_type === 'modul') {
                                            $childUrl = route('front.handle', $child->menu_slug);
                                        } elseif ($child->menu_type === 'link') {
                                            $childUrl = $child->menu_url;
                                        } else {
                                            $childUrl = '#';
                                        }

                                        $childActive = request()->is($child->menu_slug . '*');
                                    @endphp

                                    <a class="dropdown-item {{ $childActive ? 'active' : '' }}"
                                        href="{{ $childUrl }}" target="{{ $child->menu_target }}">
                                        {{ $child->menu_title }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li class="nav-item {{ $isActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ $url }}" target="{{ $menu->menu_target }}">
                                {{ $menu->menu_title }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

    </nav>

    {{--
    <nav class="navbar navbar-expand-sm sticky-top fixed-top navbar-light bg-white border-bottom">
        <a class="navbar-brand font-weight-bold text-success" href="{{ url('/') }}">
            <img src="{{ Storage::url($setting->path_image_header) }}" alt="Logo" class="logo-image">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav ml-auto mr-5">
                @foreach ($menus as $menu)
                    @php
                        $children = \App\Models\Menu::where('menu_parent_id', $menu->id)
                            ->orderBy('menu_position')
                            ->get();

                        // Tentukan URL berdasarkan menu_type
                        if ($menu->menu_type === 'pages' || $menu->menu_type === 'modul') {
                            $url = route('front.handle', $menu->menu_slug);
                        } elseif ($menu->menu_type === 'link') {
                            $url = $menu->menu_url; // langsung ke external
                        } else {
                            $url = '#';
                        }

                        // cek active: cocok dengan slug sekarang
                        $isActive = request()->is($menu->menu_slug . '*');
                    @endphp

                    @if ($children->count() > 0)
                        <li class="nav-item dropdown {{ $isActive ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                {{ $menu->menu_title }}
                            </a>
                            <div class="dropdown-menu">
                                @foreach ($children as $child)
                                    @php
                                        if ($child->menu_type === 'pages' || $child->menu_type === 'modul') {
                                            $childUrl = route('front.handle', $child->menu_slug);
                                        } elseif ($child->menu_type === 'link') {
                                            $childUrl = $child->menu_url;
                                        } else {
                                            $childUrl = '#';
                                        }

                                        $childActive = request()->is($child->menu_slug . '*');
                                    @endphp

                                    <a class="dropdown-item {{ $childActive ? 'active' : '' }}"
                                        href="{{ $childUrl }}" target="{{ $child->menu_target }}">
                                        {{ $child->menu_title }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li class="nav-item {{ $isActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ $url }}" target="{{ $menu->menu_target }}">
                                {{ $menu->menu_title }}
                            </a>
                        </li>
                    @endif
                @endforeach

            </ul>
        </div>
    </nav>  --}}

    @yield('content')

    {{-- Footer --}}
    <div class="footer">
        <p class="mb-1">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p><a href="{{ url('/about') }}">Tentang Kami</a> | <a href="{{ url('/contact') }}">Kontak</a> | <a
                href="{{ url('/ppdb') }}">PPDB</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const toggleBtn = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('navbar1');
        const overlay = document.getElementById('offcanvasOverlay');

        toggleBtn.addEventListener('click', function() {

            if (window.innerWidth < 576) {
                mobileMenu.classList.toggle('show');
                overlay.classList.toggle('active');
            }
        });

        overlay.addEventListener('click', function() {
            mobileMenu.classList.remove('show');
            overlay.classList.remove('active');
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 576) {
                mobileMenu.classList.remove('show');
                overlay.classList.remove('active');
            }
        });
    </script>


    @stack('scripts')
</body>

</html>
