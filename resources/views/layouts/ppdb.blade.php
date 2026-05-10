<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ $setting->company_name ?? 'Sekolah' }}</title>

    <link rel="icon" href="{{ $setting->pwa_icon ?? asset('/img/favicon.png') }}?v={{ $setting->pwa_version ?? time() }}" type="image/*">
    <link rel="manifest" href="/manifest.json?v={{ $setting->pwa_version ?? time() }}">
    <meta name="theme-color" content="{{ $setting->pwa_theme_color ?? '#6366f1' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $setting->pwa_short_name ?? 'Madrasah' }}">
    
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    
    <!-- Bootstrap 4 (Legacy support) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE') }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <style>
        :root {
            --p-indigo: #6366f1;
            --p-blue: #3b82f6;
            --p-emerald: #10b981;
            --p-rose: #f43f5e;
            --p-amber: #f59e0b;
            --p-slate-800: #1e293b;
        }

        * { font-family: 'Outfit', sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
        .bg-grad-blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .bg-grad-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
        .bg-grad-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .bg-grad-emerald { background: linear-gradient(135deg, #059669 0%, #064e3b 100%); }
        .bg-grad-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }

        .btn-logout {
            transition: all 0.3s;
        }
        .btn-logout:hover {
            color: #f43f5e !important;
            transform: translateX(3px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animation Keyframes */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    </style>
    @stack('css')
</head>

<body>
    <!-- MODERN NAV BAR -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-[100] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <!-- Brand/Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <span class="block text-sm font-black text-slate-800 leading-none tracking-tight">MADRASAH</span>
                        <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Digital System</span>
                    </div>
                </div>

                <!-- Nav Links (Desktop) -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('siswa.dashboard') }}" class="text-sm font-black text-slate-400 hover:text-indigo-600 transition-colors flex items-center">
                        <i class="fas fa-home mr-2 text-xs"></i> DASHBOARD
                    </a>
                    @if(Auth::user()->can('student.cbt.dashboard'))
                    <a href="{{ route('student.cbt.dashboard') }}" class="text-sm font-black text-slate-400 hover:text-indigo-600 transition-colors flex items-center">
                        <i class="fas fa-laptop-code mr-2 text-xs"></i> CBT PORTAL
                    </a>
                    @endif
                    <a href="{{ route('siswa.achievements') }}" class="text-sm font-black text-slate-400 hover:text-indigo-600 transition-colors flex items-center">
                        <i class="fas fa-award mr-2 text-xs"></i> PRESTASI
                    </a>
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center space-x-4">
                    <div class="hidden lg:flex flex-col items-end mr-2">
                        <span class="text-xs font-black text-slate-800 leading-none">{{ Auth::user()->name }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">SISWA</span>
                    </div>
                    <div class="h-10 w-[1px] bg-slate-200 hidden md:block"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout text-slate-400 hover:text-rose-500 transition-all">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="pt-24 min-h-screen px-4 md:px-0">
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="py-12 bg-white border-t border-slate-100 mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <span class="text-sm font-black text-slate-800">© {{ date('Y') }} {{ $setting->company_name ?? 'Madrasah' }}</span>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Modern Student Portal V3.0</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-slate-300 hover:text-indigo-600 transition-colors"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-slate-300 hover:text-indigo-600 transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-slate-300 hover:text-indigo-600 transition-colors"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script src="{{ asset('AdminLTE') }}/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('AdminLTE') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    
    <script>
        // Navbar Scroll Effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 10) {
                $('.glass-nav').addClass('shadow-xl shadow-slate-200/50 py-1');
            } else {
                $('.glass-nav').removeClass('shadow-xl shadow-slate-200/50 py-1');
            }
        });

        // Global Alerts
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif

        @if(session('error'))
            Swal.fire({ 
                icon: 'error', 
                title: 'Oops...', 
                text: '{{ session("error") }}',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-[2rem]' }
            });
        @endif

        @if($errors->any())
            Swal.fire({ 
                icon: 'error', 
                title: 'Kesalahan Input', 
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#6366f1',
                customClass: { popup: 'rounded-[2rem]' }
            });
        @endif
    </script>

    @stack('scripts')
    @stack('modals')
    @include('partials.pwa_install')
</body>

</html>
