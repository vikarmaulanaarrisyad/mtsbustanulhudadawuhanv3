<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Smart Madrasah</title>
    <link rel="icon" href="{{ $setting->pwa_icon ?? asset('/img/favicon.png') }}?v={{ $setting->pwa_version ?? time() }}" type="image/*">
    <link rel="manifest" href="/manifest.json?v={{ $setting->pwa_version ?? time() }}">
    <meta name="theme-color" content="{{ $setting->pwa_theme_color ?? '#10b981' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $setting->pwa_short_name ?? 'Madrasah' }}">
    <link rel="apple-touch-icon" href="/storage/pwa/icons/icon-192x192.png?v={{ $setting->pwa_version ?? time() }}">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Bootstrap 4 (AdminLTE Default) -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Fix Bootstrap Modal Conflict with Tailwind */
        .modal { background: rgba(0,0,0,0.5); }
        .modal-backdrop { display: none !important; }
        body.modal-open { overflow: hidden; }
        
        /* Custom Bottom Nav Blur */
        .bottom-nav-blur {
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
        }
        
        /* Hide scrollbar but allow scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        @keyframes pulse-emerald {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
        }
        .animate-pulse-emerald { animation: pulse-emerald 2s infinite; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    @include('partials.preloader')

    <!-- Mobile Container -->
    <div class="max-w-md mx-auto min-h-screen relative shadow-2xl bg-white pb-32">
        
        <!-- Main Content Section -->
        <main>
            @yield('content')
        </main>

        <!-- Bottom Navigation (Fixed & Modernized Emerald) -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md h-24 bg-slate-900/95 bottom-nav-blur border-t border-white/10 rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.2)] flex items-center justify-around px-6 z-[9999]">
            <a href="{{ route('guru.dashboard') }}" class="flex flex-col items-center space-y-1 {{ request()->is('guru/dashboard*') ? 'text-emerald-400' : 'text-slate-500' }}">
                <i class="fas fa-th-large text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-widest">Home</span>
            </a>
            
            <a href="{{ route('guru.schedule') }}" class="flex flex-col items-center space-y-1 {{ request()->is('guru/schedule*') ? 'text-emerald-400' : 'text-slate-500' }}">
                <i class="fas fa-calendar-alt text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-widest">Jadwal</span>
            </a>
 
            <!-- Central Action: Teacher's Own Face Attendance -->
            <div class="relative -top-8">
                <a href="{{ route('teacher.attendance.dashboard') }}" class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white shadow-2xl shadow-emerald-500/40 border-4 border-slate-900 active:scale-90 transition-all animate-pulse-emerald">
                    <i class="fas fa-camera text-2xl"></i>
                </a>
                <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[9px] {{ request()->is('teacher/attendance*') ? 'text-emerald-400' : 'text-slate-400' }} font-bold uppercase tracking-widest">Absensi</span>
            </div>
 
            <a href="{{ route('student-attendances.scanner') }}" class="flex flex-col items-center space-y-1 {{ request()->is('student-attendances*') ? 'text-emerald-400' : 'text-slate-500' }}">
                <i class="fas fa-qrcode text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-widest">Scan Siswa</span>
            </a>

            <a href="{{ route('profile.show') }}" class="flex flex-col items-center space-y-1 {{ request()->is('user/profile*') ? 'text-emerald-400' : 'text-slate-500' }}">
                <i class="fas fa-user-circle text-xl"></i>
                <span class="text-[9px] font-bold uppercase tracking-widest">Profil</span>
            </a>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    @include('partials.pwa_install')
</body>
</html>
