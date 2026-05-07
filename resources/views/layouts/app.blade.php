<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $setting->company_name }} - @yield('title')</title>

    <link rel="icon"
        href="{{ $setting->pwa_icon ?? Storage::url($setting->path_image ?? '') }}?v={{ $setting->pwa_version ?? '1.0.0' }}"
        type="image/*">
    <link rel="manifest" href="/manifest.json?v={{ $setting->pwa_version ?? '1.0.0' }}">
    <meta name="theme-color" content="{{ $setting->pwa_theme_color ?? '#10b981' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $setting->pwa_short_name ?? 'Madrasah' }}">
    <link rel="apple-touch-icon" href="/storage/pwa/icons/icon-192x192.png?v={{ $setting->pwa_version ?? '1.0.0' }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- SweetAler2 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/toastr/toastr.min.css') }}">
    @stack('css_vendor')

    <!-- Theme style -->
    {{--  <link rel="stylesheet" href="{{ asset('/AdminLTE/dist/css/adminlte.min.css') }}">  --}}
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css?v=3.2.0') }}">


    <style>
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #3c8dbc, #00c0ef);
            color: #fff;
            box-shadow: 0 6px 18px rgba(60, 141, 188, .35);
        }

        .note-editor {
            margin-bottom: 0;
        }

        .note-editor.is-invalid {
            border-color: var(--danger);
        }

        .nav-sidebar .nav-header {
            font-size: .6rem;
            font-weight: bold;
            color: #888;
        }

        .styleblock {
            display: block !important;
            /* Menampilkan dropdown dalam gaya blok */
        }

        .status-toggle {
            border: none !important;
            background: none !important;
            outline: none !important;
        }

        /* Sidebar scroll */
        .sidebar {
            overflow-y: auto;
            max-height: calc(100vh - 57px);
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, .2) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, .2);
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
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

        /* Logo statis di tengah */
        .logo-loading {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            z-index: 2;
        }

        /* Ring loader animasi */
        .loader-ring {
            position: absolute;
            width: 140px;
            height: 140px;
            border: 5px solid rgba(255, 255, 255, 0.2);
            border-top-color: #28a745;
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
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

        /* Premium Breadcrumb & Header */
        .premium-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .premium-breadcrumb .breadcrumb-item+.breadcrumb-item::before {
            content: "\f105";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #cbd5e0;
            padding: 0 10px;
        }

        .premium-breadcrumb a {
            color: #718096;
            transition: all 0.3s;
        }

        .premium-breadcrumb a:hover {
            color: #2d3748;
            text-decoration: none;
        }

        .premium-breadcrumb .active {
            color: #a0aec0;
        }

        .header-title-premium {
            font-size: 1.75rem;
            letter-spacing: -0.5px;
        }

        .letter-spacing-1 {
            letter-spacing: 1px;
        }

        .bg-light-soft {
            background: #f8fafc;
        }

        .content-wrapper {
            background: #f8fafc !important;
        }
    </style>

    @stack('css')
</head>

<body class="sidebar-mini layout-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- <div id="preloader">
            <div class="preloader-wrapper">
                <div class="loader-ring"></div>
                <img src="{{ Storage::url($setting->path_image ?? 'images/logo.png') }}" alt="Logo"
                    class="logo-loading">
            </div>
        </div> -->

        <!-- Navbar -->
        @includeIf('layouts.partials.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @includeIf('layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header pt-4 pb-2">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-end mb-1 flex-wrap">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb premium-breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                                class="fas fa-home mr-1"></i> Dashboard</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                            <h1 class="m-0 font-weight-bold text-dark header-title-premium">@yield('title')</h1>
                        </div>
                        <div class="header-date-premium d-none d-md-block text-right">
                            <span class="text-muted small font-weight-bold uppercase letter-spacing-1">Hari Ini</span>
                            <p class="mb-0 font-weight-bold text-dark">{{ tanggal_indonesia(date('Y-m-d'), true) }}</p>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- /.content-wrapper -->
        @includeIf('layouts.partials.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('/AdminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('/AdminLTE/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('/AdminLTE/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('/AdminLTE/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('/AdminLTE/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('/AdminLTE/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('/AdminLTE/plugins/moment/moment.min.js') }}"></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('/AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/toastr/toastr.min.js') }}"></script>

    @stack('scripts_vendor')

    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE/dist/js/adminlte.js?v=3.2.0') }}"></script>
    <script src="{{ asset('AdminLTE/dist/js/pages/dashboard.js') }}"></script>

    <script src="{{ asset('/js/custom.js') }}"></script>
    <script>
        $(function() {
            $('#spinner-border').hide();
        });
    </script>

    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            preloader.style.transition = 'opacity 0.7s ease';
            preloader.style.opacity = 0;
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 700);
        });
    </script>

    <x-toast />
    @stack('scripts')
    <script>
        function generateNumber(model, type, targetSelector, column = 'letter_number') {
            $.get('{{ route('letter-number.generate') }}', {
                    model,
                    type,
                    column
                })
                .done(response => {
                    $(targetSelector).val(response.number);
                })
                .fail(xhr => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal mendapatkan nomor surat otomatis.'
                    });
                });
        }
    </script>
    @include('partials.pwa_install')
</body>

</html>
