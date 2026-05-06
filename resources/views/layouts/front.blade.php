<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->company_name ?? '' }}</title>

    {{-- Bootstrap & Font Awesome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <meta name="description" content="{{ $setting->nama_aplikasi }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- FAVICONS ICON ==== -->
    <link rel="icon" href="{{ $setting->path_image }}" type="image/x-icon" />
    <link rel="icon" href="{{ Storage::url($setting->path_image ?? '') }}" type="image/*">
    <link rel="stylesheet" href="{{ asset('/public/css/mycss.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/custom.css') }}">
    <!-- SweetAler2 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css?v=3.2.0') }}">

    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

    {{-- PWA Meta Tags --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0b8c89">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Madrasah">
    <link rel="apple-touch-icon" href="/storage/pwa/icons/icon-192x192.png">

    <style>
        /* ROOT & GLOBAL */
        :root {
            --primary-color: #0eaaa6;
            --primary-light: #17ccc6;
            --dark-color: #1d1d1d;
            --text-light: rgba(255, 255, 255, 0.7);
            --shadow-soft: 0 2px 6px rgba(0, 0, 0, 0.05);
            --shadow-medium: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-strong: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        html,
        body {
            overflow-x: hidden;
            width: 100%;
        }

        /* Expanding Container for Full Feel */
        @media (min-width: 1200px) {
            .container {
                max-width: 1400px;
            }
        }

        /* Reading Progress Bar */
        .progress-container {
            position: fixed;
            top: 0;
            z-index: 9999;
            width: 100%;
            height: 4px;
            background: transparent;
        }

        .progress-bar {
            height: 4px;
            background: var(--primary-light);
            width: 0%;
            transition: width 0.1s ease;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }

        /* Premium Floating Navbar */
        .navbar {
            background: #ffffff !important;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
            padding: 15px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 8px 0;
            margin: 10px 20px;
            border-radius: 50px;
            width: calc(100% - 40px);
            box-shadow: 0 15px 35px rgba(11, 140, 137, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
        }

        /* Adjust brand and nav on scroll */
        .navbar.scrolled .logo-image {
            height: 35px;
        }

        .navbar.scrolled .navbar-brand span {
            font-size: 1.1rem;
        }

        .navbar.scrolled .nav-link {
            font-weight: 600;
            color: #2c3e50 !important;
        }

        /* Section Dividers */
        .section-divider {
            position: relative;
            height: 100px;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .section-divider svg {
            position: relative;
            display: block;
            width: calc(130% + 1.3px);
            height: 100px;
        }

        .section-divider .shape-fill {
            fill: #f9f9f9;
        }

        /* Global Professional Touches */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            letter-spacing: -0.5px;
        }

        .btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn:active {
            transform: scale(0.95);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
            border: 2px solid #f1f1f1;
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }

        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) #f1f1f1;
        }

        /* NAVBAR PREMIUM */
        .logo-image {
            max-height: 55px;
            width: auto;
            transition: transform 0.3s ease;
        }

        .logo-image:hover {
            transform: scale(1.05);
        }

        .navbar {
            padding: 15px 30px;
            transition: all .4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5) !important;
        }

        .navbar .nav-item {
            margin: 0 5px;
        }

        .navbar .nav-link {
            font-weight: 600;
            font-size: 15px;
            color: #2c3e50 !important;
            padding: 8px 20px !important;
            border-radius: 30px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar .nav-link:hover {
            color: var(--primary-color) !important;
            background: rgba(14, 170, 166, 0.08);
            transform: translateY(-2px);
        }

        li.nav-item.active .nav-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(14, 170, 166, 0.3);
            border-radius: 30px;
        }

        .navbar .dropdown:hover>.dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* DROPDOWN SUBMENU SUPPORT */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -8px;
            margin-left: 0;
            border-radius: 12px;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-submenu>.dropdown-toggle::after {
            transform: rotate(-90deg);
            margin-top: 8px;
            float: right;
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
        }

        .dropdown-menu {
            display: block;
            opacity: 0;
            transform: translateY(15px);
            transition: all 0.3s ease;
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 8px;
            padding: 10px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            min-width: 240px;
        }

        .dropdown-menu::before {
            content: "";
            position: absolute;
            top: -8px;
            left: 20px;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid rgba(255, 255, 255, 0.98);
        }

        .dropdown-menu .dropdown-item {
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 4px;
        }

        .dropdown-menu .dropdown-item:last-child {
            margin-bottom: 0;
        }

        .dropdown-menu .dropdown-item:hover,
        .dropdown-menu .dropdown-item.active {
            background: rgba(14, 170, 166, 0.1);
            color: var(--primary-color);
            padding-left: 25px;
            font-weight: 600;
        }

        /* Animasi */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* CAROUSEL */
        .carousel-inner,
        .carousel-item,
        .carousel-item img {
            height: 100vh;
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

        /* BREAKING NEWS  */
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

        /*  NEWS CARD - PREMIUM VERSION */

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


        /* FOOTER */
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
            color: #0eaaa6;
        }

        /* ANIMATIONS */
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

        /* RESPONSIVE  */

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

        /* MOBILE OFFCANVAS ONLY */

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

        /* DESKTOP NORMAL NAVBAR */

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

        /* SIDEBAR PROFESSIONAL SCHOOL STYLE */

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

        /*SAMBUTAN KEPALA MADRASAH - PREMIUM STYLE */

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

        /*
   RESPONSIVE
 */
        @media (max-width: 768px) {
            .sambutan-scroll {
                max-height: 200px;
                font-size: 12px;
                text-align: justify;
            }
        }

        /* Footer full-width gradient + pattern */
        .footer-premium {
            position: relative;
            width: 100%;
            background: #0b8c89;
            color: #fff;
            padding: 80px 0 0 0;
            margin-top: 50px;
            overflow: hidden;
            border-top: 5px solid rgba(0, 0, 0, 0.1);
        }



        .footer-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset('images/footer-pattern.png') }}');
            opacity: 0.05;
            /* pattern tipis */
            background-repeat: repeat;
            z-index: 0;
        }

        /* Container text & icons */
        .footer-premium .container {
            position: relative;
            z-index: 1;
        }

        /* Links */
        .footer-premium a {
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s, transform 0.3s;
        }

        .footer-premium a:hover {
            color: #07374d;
            transform: translateY(-2px);
            text-decoration: none;
        }

        /* Social icons */
        .footer-premium .social-icons a {
            display: inline-block;
            margin-right: 10px;
            font-size: 1.2rem;
            color: #fff;
            transition: all 0.3s;
        }

        .footer-premium .social-icons a:hover {
            color: #0b8c89;
            transform: scale(1.2) rotate(10deg);
        }

        /* Divider */
        .footer-premium hr {
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .footer-premium {
                padding: 40px 0 20px 0;
            }

            .footer-premium .social-icons a {
                margin-right: 5px;
            }
        }

        /* Full screen preloader */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0b8c89, #1d1d1d);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            overflow: hidden;
        }

        /* Wrapper logo + ring */
        .preloader-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Logo statis di tengah + animasi pulse */
        .logo-loading {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            z-index: 2;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                opacity: 0.9;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            }

            100% {
                transform: scale(0.95);
                opacity: 0.9;
            }
        }

        /* Ring loader animasi */
        .loader-ring {
            position: absolute;
            width: 140px;
            height: 140px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--primary-light);
            border-right-color: rgba(25, 135, 84, 0.5);
            border-radius: 50%;
            animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
            z-index: 1;
        }

        /* Ring spin animasi */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Preloader text */
        .preloader-text {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 25px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeSlideIn 1s forwards;
            animation-delay: 0.5s;
            text-align: center;
            letter-spacing: 2px;
        }

        /* Fade + slide animasi */
        @keyframes fadeSlideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .preloader-wrapper {
                width: 120px;
                height: 120px;
            }

            .logo-loading {
                width: 80px;
                height: 80px;
            }

            .preloader-text {
                font-size: 1.2rem;
            }
        }

        /* Floating Buttons */
        .floating-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 999;
        }

        .floating-btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .btn-whatsapp {
            background: #25d366;
            font-size: 1.8rem;
            animation: pulse-wa 2s infinite;
        }

        .btn-whatsapp:hover {
            background: #1ebc5a;
            transform: scale(1.1);
            color: #fff;
        }

        @keyframes pulse-wa {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        .wa-tooltip {
            position: absolute;
            right: 70px;
            background: #fff;
            color: #333;
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            pointer-events: none;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s ease;
        }

        .wa-tooltip::after {
            content: "";
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 8px solid #fff;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }

        .btn-whatsapp:hover .wa-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        /* Auto show tooltip periodically */
        .wa-tooltip.show-hint {
            opacity: 1;
            transform: translateX(0);
        }

        .btn-back-top {
            background: var(--primary-color);
            font-size: 1.2rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
        }

        .btn-back-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .btn-back-top:hover {
            background: var(--primary-light);
            transform: scale(1.1) translateY(-5px);
            color: #fff;
        }
    </style>

    @stack('css')

</head>

<body>
    <!-- Preloader Ring Effect -->
    <div id="preloader">
        <div class="preloader-wrapper">
            <div class="loader-ring"></div>
            <img src="{{ Storage::url($setting->path_image ?? 'images/logo.png') }}" alt="Logo"
                class="logo-loading">
        </div>
    </div>

    <!-- Reading Progress Bar -->
    <div class="progress-container">
        <div class="progress-bar" id="myBar"></div>
    </div>

    @php
        $menus = App\Models\Menu::where('menu_parent_id', 0)->orderBy('menu_position')->get();
    @endphp

    {{-- Navbar --}}

    <nav class="navbar navbar-expand-sm sticky-top fixed-top navbar-light bg-white border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-success d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ Storage::url($setting->path_image_header) }}" alt="Logo" class="logo-image mr-2">
                <div class="brand-text d-none d-lg-block">
                    <span class="d-block mb-0 h5 font-weight-bold text-success">{{ $setting->company_name }}</span>
                    <small class="text-muted d-block"
                        style="font-size: 10px; margin-top: -5px; letter-spacing: 1px;">MTS BUSTANUL HUDA
                        DAWUHAN</small>
                </div>
            </a>

            <!-- Toggle -->
            <button class="navbar-toggler" type="button" id="mobileMenuToggle">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Overlay -->
            <div class="offcanvas-overlay" id="offcanvasOverlay"></div>
            <!-- MENU -->
            <div class="collapse navbar-collapse offcanvas-menu" id="navbar1">
                <ul class="navbar-nav ml-auto">
                    @include('layouts.partials.frontend-menu', ['menus' => $menus])
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    {{-- Ensure Footer is Outside Any Containers --}}
    </div>
    </div>
    </div>

    {{-- Footer --}}
    <footer class="footer-premium">
        <div class="footer-pattern"></div>
        <div class="container">
            <div class="row">

                {{-- Tentang --}}
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold mb-3">{{ $setting->company_name }}</h5>
                    <p class="text-light">
                        {{ $setting->company_description ?? 'Selamat datang di website resmi madrasah kami. Kami berkomitmen memberikan pendidikan terbaik.' }}
                    </p>
                </div>

                {{-- Link Penting --}}
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold mb-3">Link Penting</h5>
                    <ul class="list-unstyled text-light">
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ url('/about') }}">Tentang Kami</a></li>
                        <li><a href="{{ url('/contact') }}">Kontak</a></li>
                        <li><a href="{{ url('/blog') }}">Berita</a></li>
                    </ul>
                </div>

                {{-- Kontak & Sosial Media --}}
                <div class="col-md-4">
                    <h5 class="text-white fw-bold mb-3">Kontak</h5>
                    <p class="text-light mb-1"><i class="fa fa-map-marker-alt me-2"></i>{{ $setting->address ?? '-' }}
                    </p>
                    <p class="text-light mb-1"><i class="fa fa-phone me-2"></i>{{ $setting->phone ?? '-' }}</p>
                    <p class="text-light mb-3"><i class="fa fa-envelope me-2"></i>{{ $setting->email ?? '-' }}</p>
                    <div class="social-icons">
                        @if ($setting->facebook)
                            <a href="{{ $setting->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if ($setting->twitter)
                            <a href="{{ $setting->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if ($setting->instagram)
                            <a href="{{ $setting->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Bottom Footer --}}
            <hr class="border-light mt-4">
            <div class="text-center pt-3 text-light">
                &copy; 2025 - {{ date('Y') }} {{ $setting->company_name }}. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('/AdminLTE/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE/dist/js/adminlte.js?v=3.2.0') }}"></script>
    <script src="{{ asset('AdminLTE/dist/js/pages/dashboard.js') }}"></script>

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

    <script>
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            // Tambahkan delay 1 detik agar preloader terlihat elegan sebelum menghilang
            setTimeout(() => {
                preloader.style.transition = 'opacity 0.8s ease-out, visibility 0.8s';
                preloader.style.opacity = 0;
                preloader.style.visibility = 'hidden';
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 800);
            }, 1000);
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });
    </script>
    @stack('scripts')

    {{-- Floating Assistant Buttons --}}
    <div class="floating-container">
        <!-- Tooltip Hint -->
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->phone ?? '') }}?text=Halo%20Admin%20PPDB%20MTS%20Bustanul%20Huda,%20saya%20ingin%20bertanya%20mengenai..."
            target="_blank" class="floating-btn btn-whatsapp">
            <div class="wa-tooltip" id="waHint">Ada yang bisa kami bantu?</div>
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="#" class="floating-btn btn-back-top" id="backToTop">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <script>
        // Back to Top Logic
        const backToTop = document.getElementById('backToTop');
        const waHint = document.getElementById('waHint');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Periodic Tooltip Hint for WA
        setTimeout(() => {
            waHint.classList.add('show-hint');
            setTimeout(() => {
                waHint.classList.remove('show-hint');
            }, 5000);
        }, 3000);


    {{-- PWA Registration & Active Version Check --}}
    <script>
        let deferredPrompt;

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('PWA: Service Worker terdaftar!', reg.scope);
                        
                        // Cek update secara berkala setiap 5 menit
                        setInterval(() => {
                            reg.update();
                            console.log('PWA: Mengecek versi baru...');
                        }, 300000); // 5 menit

                        // Cek update saat user kembali ke tab ini
                        window.addEventListener('focus', () => {
                            reg.update();
                        });
                    })
                    .catch(err => {
                        console.log('PWA: Gagal mendaftarkan Service Worker', err);
                    });
            });

            // Handle SW Update Notification
            navigator.serviceWorker.addEventListener('message', event => {
                if (event.data && event.data.type === 'SW_UPDATED') {
                    toastr.info('Versi baru tersedia (v' + event.data.version + '). Klik untuk memperbarui.', 'Update Tersedia', {
                        positionClass: 'toast-bottom-left',
                        timeOut: 0, // Jangan hilang sampai di klik
                        extendedTimeOut: 0,
                        closeButton: true,
                        progressBar: true,
                        onclick: function() { 
                            window.location.reload(); 
                        }
                    });
                }
            });

            // Handle Install Prompt
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
            });
        }
    </script>
</body>

</html>
