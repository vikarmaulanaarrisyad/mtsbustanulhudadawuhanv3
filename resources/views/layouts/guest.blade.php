<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $setting->company_name }} - @yield('title')</title>

    <link rel="icon" href="{{ asset('/img/favicon.png') }}" type="image/*">
    <link rel="manifest" href="/manifest.json?v={{ $setting->pwa_version ?? time() }}">
    <meta name="theme-color" content="{{ $setting->pwa_theme_color ?? '#10b981' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $setting->pwa_short_name ?? 'Madrasah' }}">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png?v={{ $setting->pwa_version ?? time() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    @stack('css_vendor')
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css') }}">

    @stack('css')

    <style>
        :root {
            --primary: #198754;
            --primary-hover: #146c43;
            --bg-gradient: linear-gradient(135deg, #198754 0%, #0eaaa6 100%);
            --glass-bg: rgba(255, 255, 255, 0.92);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --input-bg: #f9fafb;
            --shadow-soft: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f3f4f6;
            color: var(--text-main);
            overflow-x: hidden;
        }

        .login-page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: var(--bg-gradient);
            padding: 2rem;
        }

        .login-bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('/img/bg-login.jpg') }}');
            background-size: cover;
            background-position: center;
            opacity: 0.4;
            filter: blur(2px);
            z-index: 0;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-soft);
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            z-index: 1;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            width: 100px;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .login-logo:hover {
            transform: scale(1.05);
        }

        .form-control {
            height: 52px;
            background: var(--input-bg);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0 1.25rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: #fff;
        }

        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .custom-checkbox .custom-control-label::before {
            border-radius: 6px;
        }

        .text-primary-custom {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .text-primary-custom:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    @yield('content')

    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts_vendor')
    <script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('AdminLTE') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    @stack('scripts')
    <script>
        // Show password
        $('#customCheck1').on('click', function() {
            if ($(this).is(':checked')) {
                $('.password').attr('type', 'text');
            } else {
                $('.password').attr('type', 'password');
            }
        })
    </script>

    @include('partials.pwa_install')
</body>

</html>
