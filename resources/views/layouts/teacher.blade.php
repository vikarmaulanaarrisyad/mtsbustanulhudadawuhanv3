<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - Smart Madrasah</title>
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5e72e4',
                        secondary: '#825ee4',
                        success: '#2dce89',
                        info: '#11cdef',
                        warning: '#fb6340',
                        danger: '#f5365c',
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.5rem',
                        '3xl': '2rem',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        /* Hide scrollbar */
        ::-webkit-scrollbar { width: 0; }
    </style>
    @stack('css')
</head>
<body class="bg-slate-50 text-slate-800">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-2xl relative overflow-x-hidden">
        
        <!-- Content Area -->
        <main class="pb-24">
            @yield('content')
        </main>

        <!-- Android Style Bottom Navigation -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md glass-nav border-t border-slate-100 px-6 py-3 flex justify-between items-center z-50 safe-area-bottom">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center transition-all duration-200 {{ request()->is('admin/dashboard') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-home text-xl mb-1"></i>
                <span class="text-[10px] font-bold">Home</span>
            </a>
            <a href="{{ route('teacher.schedule') }}" class="flex flex-col items-center transition-all duration-200 {{ request()->is('admin/teacher/schedule*') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-calendar-alt text-xl mb-1"></i>
                <span class="text-[10px] font-bold">Jadwal</span>
            </a>
            <a href="{{ route('student-attendances.index') }}" class="flex flex-col items-center transition-all duration-200 {{ request()->is('admin/student-attendances*') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
                <div class="bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center -mt-8 shadow-lg shadow-primary/30 mb-1 border-4 border-white">
                    <i class="fas fa-user-check text-lg"></i>
                </div>
                <span class="text-[10px] font-bold">Absensi</span>
            </a>
            <a href="{{ route('profile.show') }}" class="flex flex-col items-center transition-all duration-200 {{ request()->is('admin/user/profile*') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fas fa-user-circle text-xl mb-1"></i>
                <span class="text-[10px] font-bold">Profil</span>
            </a>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
