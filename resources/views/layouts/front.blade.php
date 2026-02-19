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

    <style>
        /* =====================================================
   1. ROOT & GLOBAL
===================================================== */
        :root {
            --primary-color: #0eaaa6;
            --primary-light: #d4ffd6;
            --dark-color: #1d1d1d;
            --text-light: rgba(255, 255, 255, 0.7);
            --shadow-soft: 0 2px 6px rgba(0, 0, 0, 0.05);
            --shadow-medium: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-strong: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
        }

        /* =====================================================
   2. TOPBAR
===================================================== */
        .topbar {
            background: var(--primary-color);
            color: #fff;
            font-size: 14px;
            padding: 5px 15px;
            display: flex;
            align-items: center;
        }

        .topbar img {
            max-height: 80px;
            width: auto;
        }

        .topbar a {
            color: #fff;
            margin-left: 15px;
            transition: 0.3s;
        }

        .topbar a:hover {
            color: var(--primary-light);
        }

        /* =====================================================
   3. NAVBAR
===================================================== */
        .logo-image {
            max-height: 50px;
            width: auto;
        }

        .navbar {
            padding: 10px 30px;
            transition: all .3s ease;
            box-shadow: var(--shadow-soft);
        }

        .navbar .nav-link {
            font-weight: 500;
            margin-left: 10px;
            position: relative;
        }

        .navbar .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0%;
            height: 2px;
            background: var(--primary-color);
            transition: 0.4s;
        }

        .navbar .nav-link:hover::after,
        .navbar .nav-link.active::after {
            width: 100%;
        }

        .navbar .dropdown:hover>.dropdown-menu {
            display: block;
            animation: fadeIn .5s ease-in-out;
        }

        .dropdown-menu {
            margin-top: 6px;
            border-radius: 0 0 8px 8px;
            border: none;
            box-shadow: var(--shadow-medium);
        }

        /* =====================================================
   4. CAROUSEL
===================================================== */
        .carousel-item img {
            height: 70vh;
            object-fit: cover;
        }

        .carousel-item::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1;
        }

        .carousel-caption {
            z-index: 2;
        }

        /* =====================================================
   5. BREAKING NEWS
===================================================== */
        .breaking-news {
            background: var(--primary-color);
            color: #fff;
            padding: 5px 30px;
            overflow: hidden;
            position: relative;
        }

        .breaking-news .news-text {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation: scroll-left 15s linear infinite;
            font-weight: 500;
        }

        /* =====================================================
   6. NEWS CARD - PREMIUM VERSION
===================================================== */

        .news .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            transition: all 0.35s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        /* Hover effect */
        .news .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
        }

        /* Image wrapper */
        .news .card-img-wrapper {
            position: relative;
            overflow: hidden;
        }

        /* Image */
        .news .card img {
            height: 220px;
            width: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        /* Zoom on hover */
        .news .card:hover img {
            transform: scale(1.08);
        }

        /* Gradient overlay */
        .news .card-img-wrapper::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent 60%);
            opacity: 0;
            transition: 0.4s ease;
        }

        .news .card:hover .card-img-wrapper::after {
            opacity: 1;
        }

        /* Card body */
        .news .card-body {
            padding: 20px;
        }

        /* Title */
        .post-title-hover {
            font-size: 13px;
            font-weight: 600;
            color: #222;
            transition: 0.3s ease;
            cursor: pointer;
        }

        .post-title-hover:hover {
            color: var(--primary-color);
        }

        /* Meta */
        .post-meta {
            font-size: 13px;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 8px;
            display: inline-block;
        }

        /* Description */
        .news .card-text {
            font-size: 14px;
            color: #666;
            line-height: 1.6;
        }

        /* Utility */
        .line-height-15 {
            line-height: 1.5;
        }


        /* =====================================================
   7. FOOTER
===================================================== */
        .footer {
            background: var(--dark-color);
            color: var(--text-light);
            padding: 15px 0;
            text-align: center;
            border-top: 3px solid var(--primary-color);
        }

        .footer a {
            color: var(--text-light);
        }

        .footer a:hover {
            color: #fff;
        }

        /* =====================================================
   8. ANIMATIONS
===================================================== */
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

        @keyframes scroll-left {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }

        /* =====================================================
   9. RESPONSIVE
===================================================== */

        /* Tablet */
        @media (max-width: 768px) {
            .topbar {
                padding: 10px 5px;
            }

            .topbar img {
                max-height: 78px;
            }

            .topbar-info {
                display: none;
            }

            .logo-image {
                max-height: 50px;
                width: auto;
            }
        }

        /* HP kecil */
        @media (max-width: 480px) {
            .topbar {
                padding: 5px 8px;
            }

            .topbar img {
                max-height: 50px;
            }

            .logo-image {
                max-height: 30px;
                width: auto;
            }
        }

        /* =========================================
   MOBILE OFFCANVAS ONLY
========================================= */

        @media (max-width: 575.98px) {

            .offcanvas-menu {
                position: fixed;
                top: 0;
                right: -300px;
                width: 280px;
                height: 100vh;
                background: #fff;
                box-shadow: -4px 0 15px rgba(0, 0, 0, 0.15);
                transition: 0.3s ease-in-out;
                z-index: 1050;
                padding: 20px;
                overflow-y: auto;
            }

            .offcanvas-menu.show {
                right: 0;
            }

            .offcanvas-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                opacity: 0;
                visibility: hidden;
                transition: 0.3s;
                z-index: 1040;
            }

            .offcanvas-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            /* Supaya menu vertical */
            .offcanvas-menu .navbar-nav {
                flex-direction: column;
            }

            .offcanvas-menu .nav-link {
                padding: 10px 0;
            }
        }

        /* =========================================
   DESKTOP NORMAL NAVBAR
========================================= */

        @media (min-width: 576px) {

            .offcanvas-overlay {
                display: none !important;
            }

            .offcanvas-menu {
                position: static !important;
                height: auto !important;
                width: auto !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }

        /* =====================================================
   SIDEBAR PROFESSIONAL SCHOOL STYLE
===================================================== */

        .sidebar-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e9ecef;
            margin-bottom: 25px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.04);
        }

        .sidebar-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: linear-gradient(135deg, #0eaaa6, #0b8c89);
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.3px;
        }

        /* Icon style */
        .sidebar-header i {
            font-size: 16px;
        }

        /* Body */
        .sidebar-body {
            padding: 15px 18px;
            max-height: 260px;
            overflow-y: auto;
        }

        /* Custom Scrollbar */
        .sidebar-body::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-body::-webkit-scrollbar-thumb {
            background: rgba(14, 170, 166, 0.4);
            border-radius: 10px;
        }

        /* List styling inside partial */
        .sidebar-body ul {
            padding-left: 0;
            list-style: none;
        }

        .sidebar-body li {
            padding: 8px 0;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
            transition: 0.3s;
        }

        .sidebar-body li:last-child {
            border-bottom: none;
        }

        .sidebar-body li:hover {
            padding-left: 5px;
            color: #0eaaa6;
        }

        /* =====================================================
   SAMBUTAN KEPALA MADRASAH - PREMIUM STYLE
===================================================== */

        .kepala-card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e6e9f0;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            transition: 0.3s ease;
        }

        .kepala-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .kepala-header {
            background: linear-gradient(135deg, #0eaaa6, #0b8c89);
            padding: 15px 20px;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        /* Body */
        .kepala-body {
            padding: 20px;
        }

        /* Profile */
        .kepala-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .kepala-profile img {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0eaaa6;
        }

        .kepala-info h6 {
            margin: 0;
            font-weight: 600;
            color: #1c2c4c;
        }

        .kepala-info small {
            color: #6c757d;
        }

        /* Sambutan text */
        .kepala-sambutan {
            font-size: 14px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 15px;
            text-align: justify;
        }

        /* Button */
        .btn-sambutan {
            display: inline-block;
            background: #0eaaa6;
            color: #fff;
            padding: 6px 16px;
            font-size: 13px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-sambutan:hover {
            background: #0b8c89;
            color: #fff;
        }

        /* =========================================
   RESPONSIVE
========================================= */
        @media (max-width: 768px) {
            .sambutan-scroll {
                max-height: 200px;
                font-size: 12px;
                text-align: justify;
            }
        }
    </style>


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
