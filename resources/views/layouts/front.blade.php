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


    @stack('css')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
        }

        .topbar {
            background: #0eaaa6;
            color: #fff;
            font-size: 14px;
            padding: 5px 15px;
            display: flex;
            align-items: center;
        }

        .topbar img {
            height: 100%;
            max-height: 80px;
            /* optional supaya tidak terlalu besar */
            width: auto;
        }

        /* Ukuran HP */
        @media (max-width: 768px) {
            .topbar {
                padding: 10px 5px !important;
            }

            .topbar img {
                height: 78px !important;
                /* khusus HP */
            }

            .topbar-info {
                display: none;
            }
        }

        /* HP kecil banget */
        @media (max-width: 480px) {
            .topbar {
                padding: 5px 8px !important;
            }

            .topbar img {
                height: 50px !important;
            }
        }


        .topbar a {
            color: #fff;
            margin-left: 15px;
            transition: 0.3s;
        }

        .topbar a:hover {
            color: #d4ffd6;
        }

        .navbar {
            transition: all .3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            padding: 10px 30px;
        }

        .navbar .nav-link {
            font-weight: 500;
            margin-left: 10px;
            position: relative;
        }

        .navbar .nav-link::after {
            content: '';
            position: absolute;
            width: 0%;
            height: 2px;
            background: #0eaaa6;
            bottom: -5px;
            left: 0;
            transition: 0.4s;
        }

        .navbar .nav-link:hover::after,
        .navbar .nav-link.active::after {
            width: 100%;
        }

        .navbar .dropdown:hover>.dropdown-menu {
            display: block;
            animation: fadeIn .7s ease-in-out;
        }

        .dropdown-menu {
            margin-top: 6px;
            border-radius: 0 0 8px 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .carousel-item img {
            height: 70vh;
            object-fit: cover;
        }

        .carousel-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }

        .carousel-caption {
            z-index: 2;
        }

        .breaking-news {
            background: #0eaaa6;
            color: #fff;
            overflow: hidden;
            position: relative;
            padding: 5px 30px;
        }

        .breaking-news .news-text {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation: scroll-left 15s linear infinite;
            font-weight: 500;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .news .card {
            transition: 0.3s;
            border-radius: 8px;
            overflow: hidden;
        }

        .news .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .news .card img {
            height: 200px;
            object-fit: cover;
        }

        .post-title-hover {
            transition: color 0.3s, transform 0.3s;
            cursor: pointer;
        }

        .post-title-hover:hover {
            color: #0eaaa6;
        }

        .line-height-15 {
            line-height: 1.5;
        }

        .post-meta {
            color: #0eaaa6;
            /* warna hijau */
            padding: 0 5px;
            /* jarak kiri-kanan */
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer {
            background: #1d1d1d;
            color: rgba(255, 255, 255, 0.7);
            padding: 15px 0px;
            text-align: center;
            border-top: 3px solid #0eaaa6;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
        }

        .footer a:hover {
            color: #fff;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- Header Atas --}}
    <div class="topbar d-flex justify-content-between align-items-center">
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

    </div>

    @php
        $menus = App\Models\Menu::where('menu_parent_id', 0)->orderBy('menu_position')->get();
    @endphp

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-sm sticky-top fixed-top navbar-light bg-white border-bottom">
        <a class="navbar-brand font-weight-bold text-success" href="{{ url('/') }}">
            <img src="{{ Storage::url($setting->path_image_header) }}" alt="Logo" style="height:30px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav ml-auto mr-5">
                {{--  @foreach ($menus as $menu)
                    @php
                        $children = \App\Models\Menu::where('menu_parent_id', $menu->id)
                            ->orderBy('menu_position')
                            ->get();

                        // cek active: kalau path cocok
                        $isActive = request()->is(trim($menu->menu_url, '/') . '*');
                    @endphp

                    @if ($children->count() > 0)
                        <li class="nav-item dropdown {{ $isActive ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                {{ $menu->menu_title }}
                            </a>
                            <div class="dropdown-menu">
                                @foreach ($children as $child)
                                    @php
                                        $childActive = request()->is(trim($child->menu_url, '/') . '*');
                                    @endphp
                                    <a class="dropdown-item {{ $childActive ? 'active' : '' }}"
                                        href="{{ url($child->menu_url) }}">
                                        {{ $child->menu_title }}
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li class="nav-item {{ $isActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url($menu->menu_url) }}">
                                {{ $menu->menu_title }}
                            </a>
                        </li>
                    @endif
                @endforeach  --}}

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

    @yield('content')

    {{-- Footer --}}
    <div class="footer">
        <p class="mb-1">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p><a href="{{ url('/about') }}">Tentang Kami</a> | <a href="{{ url('/contact') }}">Kontak</a> | <a
                href="{{ url('/ppdb') }}">PPDB</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
