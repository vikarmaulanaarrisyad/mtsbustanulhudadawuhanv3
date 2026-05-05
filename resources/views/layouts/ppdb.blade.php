<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPDB - {{ $setting->company_name ?? 'Sekolah' }} | @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('/img/favicon.png') }}" type="image/*">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Madrasah Digital">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f7f6;
            min-height: 100vh;
            color: #2d3748;
        }

        .ppdb-navbar {
            background: #ffffff;
            border-bottom: 1px solid #edf2f7;
            padding: 0.75rem 0;
        }

        .ppdb-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #1a7431 !important;
            letter-spacing: -0.025em;
        }

        .ppdb-navbar .nav-link {
            color: #4a5568 !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.2s;
        }

        .ppdb-navbar .nav-link:hover {
            color: #1a7431 !important;
        }

        .ppdb-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 15px;
        }

        .ppdb-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .ppdb-card .card-header {
            background: #ffffff;
            color: #1a7431;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 20px 30px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
        }

        .ppdb-card .card-body {
            padding: 30px;
        }

        .status-card {
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            color: #fff;
            margin-bottom: 25px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .status-card.pending {
            background: linear-gradient(135deg, #fefcbf 0%, #faf089 100%);
            color: #744210;
        }

        .status-card.berkas_lengkap {
            background: linear-gradient(135deg, #4fd1c5 0%, #38b2ac 100%);
        }

        .status-card.berkas_tidak_lengkap {
            background: linear-gradient(135deg, #feb2b2 0%, #f56565 100%);
        }

        .status-card.diterima {
            background: linear-gradient(135deg, #68d391 0%, #48bb78 100%);
        }

        .status-card.daftar_ulang {
            background: linear-gradient(135deg, #63b3ed 0%, #3182ce 100%);
        }

        .status-card.daftar_ulang_terverifikasi {
            background: linear-gradient(135deg, #38b2ac 0%, #2c7a7b 100%);
        }

        .status-card.cadangan {
            background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
        }

        .status-card.ditolak {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }

        .status-card h3 {
            font-weight: 800;
            margin: 0;
            font-size: 1.75rem;
        }

        .status-card p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-weight: 500;
        }

        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            border-bottom: 1px solid #edf2f7;
            transition: background 0.2s;
        }

        .doc-item:hover {
            background: #f7fafc;
        }

        .doc-item:last-child {
            border-bottom: none;
        }

        .doc-item .doc-name {
            font-weight: 600;
            color: #2d3748;
        }

        .form-section-title {
            font-weight: 700;
            color: #1a7431;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 30px;
        }

        .form-section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #edf2f7;
            margin-left: 15px;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            height: auto;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: #fff;
            border-color: #48bb78;
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.15);
        }

        .btn-ppdb {
            background: #1a7431;
            color: #fff;
            border: none;
            padding: 12px 35px;
            border-radius: 10px;
            font-weight: 700;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .btn-ppdb:hover {
            background: #146c43;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(26, 116, 49, 0.3);
        }

        .welcome-banner {
            background: linear-gradient(135deg, #1a7431 0%, #28a745 100%);
            color: #fff;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 15px -3px rgba(26, 116, 49, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: "";
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-banner p {
            opacity: 0.9;
            margin: 0;
            font-size: 1.05rem;
        }

        /* Stepper UI */
        .ppdb-stepper {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
        }

        .ppdb-stepper .step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }

        .ppdb-stepper .step-icon {
            width: 45px;
            height: 45px;
            background: #edf2f7;
            color: #a0aec0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
            transition: all 0.3s;
            border: 4px solid #fff;
        }

        .ppdb-stepper .step.active .step-icon {
            background: #1a7431;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(26, 116, 49, 0.1);
        }

        .ppdb-stepper .step.success .step-icon {
            background: #28a745;
            color: #fff;
        }

        .ppdb-stepper .step.danger .step-icon {
            background: #dc3545;
            color: #fff;
        }

        .ppdb-stepper .step-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #4a5568;
            margin-bottom: 2px;
        }

        .ppdb-stepper .step-desc {
            font-size: 0.7rem;
            color: #a0aec0;
            text-align: center;
            line-height: 1.2;
        }

        .ppdb-stepper .step-line {
            position: absolute;
            top: 22px;
            height: 3px;
            background: #edf2f7;
            z-index: 1;
        }

        .ppdb-stepper .step-line.active {
            background: #1a7431;
        }

        /* Line positions for 4 steps */
        .ppdb-stepper .step-line:nth-of-type(2) {
            left: 12.5%;
            width: 25%;
        }

        .ppdb-stepper .step-line:nth-of-type(4) {
            left: 37.5%;
            width: 25%;
        }

        .ppdb-stepper .step-line:nth-of-type(6) {
            left: 62.5%;
            width: 25%;
        }
    </style>
    @stack('css')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg ppdb-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('ppdb.dashboard') }}">
                {{-- Logo/Title hidden by user request --}}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ppdbNav">
                <i class="fas fa-bars text-white"></i>
            </button>
            <div class="collapse navbar-collapse" id="ppdbNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ppdb.dashboard') }}">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="fas fa-user mr-1"></i> {{ Auth::user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link" style="cursor:pointer;">
                                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="ppdb-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @yield('content')
    </div>



    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('AdminLTE') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered'))
                    .catch(err => console.log('Service Worker registration failed'));
            });
        }
    </script>
    @include('partials.pwa_install')
</body>

</html>
