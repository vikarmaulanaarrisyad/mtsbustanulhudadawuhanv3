<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PPDB - {{ $setting->company_name ?? 'Sekolah' }} | @yield('title', 'Dashboard')</title>

    <link rel="icon" href="{{ asset('/img/favicon.png') }}" type="image/*">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f0f4f8; min-height: 100vh; }

        .ppdb-navbar {
            background: linear-gradient(135deg, #1a7431 0%, #28a745 50%, #20c997 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
        }
        .ppdb-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff !important;
        }
        .ppdb-navbar .nav-link { color: rgba(255,255,255,0.9) !important; }
        .ppdb-navbar .nav-link:hover { color: #fff !important; }

        .ppdb-container { max-width: 900px; margin: 0 auto; padding: 30px 15px; }

        .ppdb-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
        }
        .ppdb-card .card-header {
            background: linear-gradient(135deg, #1a7431 0%, #28a745 100%);
            color: #fff;
            font-weight: 600;
            padding: 15px 25px;
            border: none;
        }
        .ppdb-card .card-body { padding: 25px; }

        .status-card {
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }
        .status-card.pending { background: linear-gradient(135deg, #ffc107, #ffca2c); color: #333; }
        .status-card.berkas_lengkap { background: linear-gradient(135deg, #17a2b8, #20c9e0); }
        .status-card.berkas_tidak_lengkap { background: linear-gradient(135deg, #dc3545, #e45561); }
        .status-card.diterima { background: linear-gradient(135deg, #28a745, #34d058); }
        .status-card.ditolak { background: linear-gradient(135deg, #343a40, #555); }

        .status-card h3 { font-weight: 700; margin: 0; }
        .status-card p { margin: 5px 0 0; opacity: 0.9; }

        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        .doc-item:last-child { border-bottom: none; }
        .doc-item .doc-name { font-weight: 500; }

        .form-section-title {
            font-weight: 600;
            color: #1a7431;
            border-bottom: 2px solid #28a745;
            padding-bottom: 8px;
            margin-bottom: 20px;
            margin-top: 10px;
        }

        .btn-ppdb {
            background: linear-gradient(135deg, #1a7431, #28a745);
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-ppdb:hover {
            background: linear-gradient(135deg, #155d27, #1e8338);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.4);
        }

        .welcome-banner {
            background: linear-gradient(135deg, #1a7431 0%, #28a745 50%, #20c997 100%);
            color: #fff;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
        }
        .welcome-banner h4 { font-weight: 700; margin-bottom: 5px; }
        .welcome-banner p { opacity: 0.9; margin: 0; }
    </style>
    @stack('css')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg ppdb-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('ppdb.dashboard') }}">
                <i class="fas fa-graduation-cap mr-2"></i> PPDB {{ $setting->company_name ?? '' }}
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- FOOTER --}}
    <footer class="text-center py-4 text-muted">
        <small>&copy; {{ date('Y') }} {{ $setting->company_name ?? 'Sekolah' }} — Sistem PPDB Online</small>
    </footer>

    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('AdminLTE') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    @stack('scripts')
</body>
</html>
