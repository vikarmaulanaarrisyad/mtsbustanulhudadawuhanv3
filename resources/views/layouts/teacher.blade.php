<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Smart Madrasah</title>
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                        sans: ['Poppins', 'sans-serif'],
                    },
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
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        /* Hide scrollbar but allow scroll */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">

    <!-- Mobile Container -->
    <div class="max-w-md mx-auto min-h-screen relative shadow-2xl bg-white">
        
        <!-- Main Content Section -->
        <main>
            @yield('content')
        </main>

        <!-- Bottom Navigation (Fixed) -->
        <nav class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-[400px] h-20 bg-white/80 bottom-nav-blur border border-white/40 rounded-[2.5rem] shadow-2xl flex items-center justify-around px-6 z-[9999]">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center space-y-1 {{ request()->is('admin/dashboard') ? 'text-indigo-600' : 'text-slate-400' }}">
                <div class="p-2 {{ request()->is('admin/dashboard') ? 'bg-indigo-50 rounded-2xl' : '' }}">
                    <i class="fas fa-home text-xl"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Home</span>
            </a>
            
            <a href="{{ route('teacher.schedule') }}" class="flex flex-col items-center space-y-1 {{ request()->is('admin/teacher/schedule*') ? 'text-indigo-600' : 'text-slate-400' }}">
                <div class="p-2 {{ request()->is('admin/teacher/schedule*') ? 'bg-indigo-50 rounded-2xl' : '' }}">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Jadwal</span>
            </a>

            <!-- Scanner Button (Floating Style) -->
            <div class="relative -top-10">
                <a href="{{ route('student-attendances.index') }}" class="w-16 h-16 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-2xl shadow-indigo-200 border-4 border-white active:scale-90 transition-all">
                    <i class="fas fa-user-check text-2xl"></i>
                </a>
                <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-indigo-600 font-black uppercase tracking-widest">Absensi</span>
            </div>

            <a href="{{ route('teacher.attendance.dashboard') }}" class="flex flex-col items-center space-y-1 {{ request()->is('admin/teacher/attendance*') ? 'text-indigo-600' : 'text-slate-400' }}">
                <div class="p-2 {{ request()->is('admin/teacher/attendance*') ? 'bg-indigo-50 rounded-2xl' : '' }}">
                    <i class="fas fa-file-invoice text-xl"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Laporan</span>
            </a>

            <a href="{{ route('profile.show') }}" class="flex flex-col items-center space-y-1 {{ request()->is('user/profile*') ? 'text-indigo-600' : 'text-slate-400' }}">
                <div class="p-2 {{ request()->is('user/profile*') ? 'bg-indigo-50 rounded-2xl' : '' }}">
                    <i class="fas fa-user-circle text-xl"></i>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Profil</span>
            </a>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
