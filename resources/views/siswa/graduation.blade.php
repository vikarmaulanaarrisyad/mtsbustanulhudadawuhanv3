@extends('layouts.ppdb')

@section('title', 'Cek Kelulusan Siswa')

@section('content')
<div class="max-w-4xl mx-auto px-6 pb-24">
    <!-- HERO HEADER BANNER -->
    <div class="relative rounded-[3rem] bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 p-8 md:p-12 shadow-2xl border border-white/10 overflow-hidden mb-10 text-center animate-fade-in">
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-20 h-20 bg-indigo-500/20 backdrop-blur-xl border border-indigo-400/30 rounded-[1.8rem] flex items-center justify-center text-indigo-400 mb-6 shadow-inner animate-pulse">
                <i class="fas fa-graduation-cap text-3xl animate-bounce"></i>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-none mb-3">Portal Pengumuman Kelulusan</h1>
            <p class="text-sm font-medium text-slate-400 max-w-lg mb-6">Sistem Informasi Pengumuman Kelulusan Siswa Tingkat Akhir Madrasah Digital</p>
            
            <div class="flex flex-wrap justify-center gap-3">
                <span class="bg-white/5 border border-white/10 backdrop-blur-md text-[10px] font-black text-indigo-300 px-4 py-2 rounded-full uppercase tracking-widest">{{ $student->nama_lengkap }}</span>
                <span class="bg-white/5 border border-white/10 backdrop-blur-md text-[10px] font-black text-indigo-300 px-4 py-2 rounded-full uppercase tracking-widest">NISN: {{ $student->nisn }}</span>
                <span class="bg-white/5 border border-white/10 backdrop-blur-md text-[10px] font-black text-indigo-300 px-4 py-2 rounded-full uppercase tracking-widest">{{ $student->classGroup->group_name ?? '-' }}</span>
            </div>
        </div>
        
        <!-- Glow accents -->
        <div class="absolute right-[-100px] top-[-100px] w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute left-[-100px] bottom-[-100px] w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- MAIN BODY BASED ON STATE -->
    @if(!$setting || !$setting->is_active)
        <!-- STATE 1: NOT ACTIVE -->
        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] text-center animate-fade-in">
            <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-amber-100 shadow-inner">
                <i class="fas fa-lock text-xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">Pengumuman Belum Aktif</h3>
            <p class="text-sm text-slate-400 font-medium max-w-md mx-auto mb-0 leading-relaxed">
                Pihak panitia kelulusan madrasah belum mengaktifkan atau merilis informasi kelulusan untuk jenjang kelas Anda. Silakan hubungi wali kelas atau cek kembali nanti.
            </p>
        </div>
    @elseif(now()->lt($setting->announcement_date))
        <!-- STATE 2: COUNTDOWN ACTIVE -->
        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] text-center animate-fade-in">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-indigo-100 shadow-inner">
                <i class="fas fa-history text-xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800 mb-2">Menghitung Mundur Rilis</h3>
            <p class="text-sm text-slate-400 font-medium max-w-md mx-auto mb-8">Informasi kelulusan Anda akan dibuka secara otomatis dalam:</p>
            
            <!-- COUNTDOWN TIMER GRID -->
            <div class="grid grid-cols-4 gap-4 max-w-xl mx-auto mb-8">
                <div class="bg-slate-900 text-white rounded-[2rem] p-4 border border-white/5 relative overflow-hidden shadow-2xl group">
                    <span id="days" class="block text-3xl md:text-5xl font-black text-indigo-400 tracking-tighter mb-1">00</span>
                    <span class="block text-[9px] font-black uppercase text-slate-500 tracking-widest">Hari</span>
                </div>
                <div class="bg-slate-900 text-white rounded-[2rem] p-4 border border-white/5 relative overflow-hidden shadow-2xl group">
                    <span id="hours" class="block text-3xl md:text-5xl font-black text-indigo-400 tracking-tighter mb-1">00</span>
                    <span class="block text-[9px] font-black uppercase text-slate-500 tracking-widest">Jam</span>
                </div>
                <div class="bg-slate-900 text-white rounded-[2rem] p-4 border border-white/5 relative overflow-hidden shadow-2xl group">
                    <span id="minutes" class="block text-3xl md:text-5xl font-black text-indigo-400 tracking-tighter mb-1">00</span>
                    <span class="block text-[9px] font-black uppercase text-slate-500 tracking-widest">Menit</span>
                </div>
                <div class="bg-slate-900 text-white rounded-[2rem] p-4 border border-white/5 relative overflow-hidden shadow-2xl group">
                    <span id="seconds" class="block text-3xl md:text-5xl font-black text-rose-500 tracking-tighter mb-1 animate-pulse">00</span>
                    <span class="block text-[9px] font-black uppercase text-slate-500 tracking-widest">Detik</span>
                </div>
            </div>
            
            <div class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-50 text-indigo-700 rounded-full text-xs font-black uppercase tracking-wider">
                <i class="fas fa-calendar-alt"></i> Rilis Pada: {{ $setting->announcement_date->translatedFormat('d F Y - H:i') }} WIB
            </div>
        </div>
    @else
        <!-- STATE 3: ANNOUNCEMENT RELEASED -->
        @if($student->student_status_id == 2)
            <!-- SUBSTATE 3A: GRADUATED (LULUS) -->
            <div class="bg-white rounded-[3rem] p-1 border border-slate-100 shadow-[0_30px_60px_-15px_rgba(16,185,129,0.15)] overflow-hidden animate-fade-in">
                <!-- Top Header banner -->
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-8 text-center text-white relative">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl">
                        <i class="fas fa-award text-2xl text-white"></i>
                    </div>
                    <span class="bg-emerald-800/40 text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-3 inline-block">Hasil Kelulusan</span>
                    <h2 class="text-3xl font-black tracking-tight leading-none">Dinyatakan LULUS</h2>
                    
                    <div class="absolute right-[-50px] top-[-50px] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                </div>
                
                <div class="p-8 md:p-12">
                    <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-6 md:p-8 bg-slate-50/50 mb-8 text-center">
                        <h4 class="text-lg font-black text-slate-800 mb-3">Selamat Kepada</h4>
                        <div class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">{{ $student->nama_lengkap }}</div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">NISN: {{ $student->nisn }} | Kelas: {{ $student->classGroup->group_name ?? '-' }}</p>
                        
                        <div class="h-[1px] bg-slate-200 w-full mb-6"></div>
                        
                        <p class="text-sm font-medium text-slate-600 italic leading-relaxed max-w-xl mx-auto mb-0">
                            "{{ $setting->announcement_text ?: 'Selamat! Anda dinyatakan LULUS. Tetap semangat menempuh jenjang berikutnya dan jaga nama baik almamater.' }}"
                        </p>
                    </div>
                    
                    <div class="flex flex-col md:flex-row justify-center items-center gap-4">
                        <a href="{{ route('siswa.dashboard') }}" class="w-full md:w-auto px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-[1.5rem] text-sm font-black uppercase tracking-widest text-center transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-home"></i> Dashboard Utama
                        </a>
                        <a href="{{ route('siswa.graduation.print-skl') }}" target="_blank" class="w-full md:w-auto px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:shadow-emerald-200 hover:-translate-y-1 text-white rounded-[1.5rem] text-sm font-black uppercase tracking-widest text-center shadow-lg transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-print"></i> Cetak SKL Digital <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- SUBSTATE 3B: NOT GRADUATED / REMAINING -->
            <div class="bg-white rounded-[3rem] p-1 border border-slate-100 shadow-[0_20px_50px_-15px_rgba(244,63,94,0.15)] overflow-hidden animate-fade-in">
                <!-- Top Header banner -->
                <div class="bg-gradient-to-r from-rose-600 to-red-700 p-8 text-center text-white relative">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl">
                        <i class="fas fa-exclamation-circle text-2xl text-white"></i>
                    </div>
                    <span class="bg-rose-800/40 text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest mb-3 inline-block">Hasil Kelulusan</span>
                    <h2 class="text-3xl font-black tracking-tight leading-none">Dinyatakan Belum Lulus / Tertunda</h2>
                    
                    <div class="absolute right-[-50px] top-[-50px] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                </div>
                
                <div class="p-8 md:p-12 text-center">
                    <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-6 md:p-8 bg-slate-50/50 mb-8 max-w-xl mx-auto">
                        <p class="text-sm font-medium text-slate-600 leading-relaxed mb-0">
                            {{ $setting->non_graduation_text ?: 'Mohon maaf, Anda dinyatakan BELUM LULUS. Silakan hubungi pihak madrasah atau wali kelas untuk mendapatkan informasi dan pengarahan lebih lanjut.' }}
                        </p>
                    </div>
                    
                    <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-800 hover:bg-slate-900 text-white rounded-[1.5rem] text-sm font-black uppercase tracking-widest transition-all">
                        <i class="fas fa-home"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>

@if($setting && $setting->is_active && now()->lt($setting->announcement_date))
<!-- COUNTDOWN SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const announcementDate = new Date("{{ $setting->announcement_date->format('Y-m-d H:i:s') }}").getTime();
        
        function updateTimer() {
            const now = new Date().getTime();
            const difference = announcementDate - now;
            
            if (difference <= 0) {
                clearInterval(timerInterval);
                location.reload(); // Reload to show the result
                return;
            }
            
            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);
            
            document.getElementById('days').innerText = String(days).padStart(2, '0');
            document.getElementById('hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
        }
        
        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);
    });
</script>
@endif

@if($setting && $setting->is_active && now()->gte($setting->announcement_date) && $student->student_status_id == 2)
<!-- CONFETTI CELEBRATION SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Confetti effect on graduation release
        var end = Date.now() + (3 * 1000);

        var interval = setInterval(function() {
            if (Date.now() > end) {
                return clearInterval(interval);
            }

            confetti({
                startVelocity: 30,
                spread: 360,
                ticks: 60,
                origin: {
                    x: Math.random(),
                    // since they fall down, animate them from the top
                    y: Math.random() - 0.2
                }
            });
        }, 200);
    });
</script>
@endif

@endsection
