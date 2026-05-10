@extends('layouts.ppdb')

@section('title', 'Hasil Ujian CBT')

@section('content')
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('siswa.dashboard') }}" class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white hover:bg-white/20 transition-all shadow-xl border border-white/10">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="text-white">
                        <span class="bg-blue-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg mb-2 inline-block">Computer Based Test</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none">Hasil Ujian</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-blue-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <div class="grid grid-cols-1 gap-8">
            @forelse($examResults as $result)
                <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden relative group hover:-translate-y-2 transition-all duration-500">
                    <div class="flex flex-col lg:flex-row items-center gap-10">
                        <!-- Left: Exam Info -->
                        <div class="flex-grow">
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="bg-indigo-50 text-indigo-600 text-[9px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border border-indigo-100">
                                    {{ $result->exam->type ?? 'Ujian' }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $result->end_time ? $result->end_time->translatedFormat('d F Y') : '-' }}
                                </span>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 mb-4 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $result->exam->name }}</h3>
                            
                            <div class="flex flex-wrap items-center gap-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Mata Pelajaran</span>
                                        <h6 class="text-xs font-black text-slate-700 mb-0">{{ $result->exam->bank->subject->subject_name ?? '-' }}</h6>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Durasi</span>
                                        <h6 class="text-xs font-black text-slate-700 mb-0">{{ $result->exam->duration_minutes }} Menit</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Scores -->
                        <div class="flex items-center gap-6 w-full lg:w-auto bg-slate-50 p-6 rounded-[2.5rem] border border-slate-100 shadow-inner">
                            <div class="text-center px-4">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Benar</span>
                                <h4 class="text-2xl font-black text-emerald-600">{{ $result->correct_answers }}</h4>
                            </div>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <div class="text-center px-4">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Salah</span>
                                <h4 class="text-2xl font-black text-rose-600">{{ $result->wrong_answers }}</h4>
                            </div>
                            <div class="w-px h-10 bg-slate-200"></div>
                            <div class="text-center px-8 relative">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nilai Akhir</span>
                                <h4 class="text-5xl font-black text-slate-800 tracking-tighter">{{ round($result->score) }}</h4>
                                <!-- Small visual indicator -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-{{ $result->score >= 75 ? 'emerald' : 'rose' }}-500 text-white flex items-center justify-center shadow-lg border-2 border-white animate-bounce">
                                    <i class="fas fa-{{ $result->score >= 75 ? 'check' : 'times' }} text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subtle background decoration -->
                    <div class="absolute right-0 bottom-0 p-10 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity">
                        <i class="fas fa-laptop-code fa-6x"></i>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] p-20 shadow-2xl shadow-slate-200/50 border border-slate-50 text-center">
                    <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                        <i class="fas fa-clipboard-list text-4xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-800 mb-2">Belum Ada Hasil Ujian</h4>
                    <p class="text-sm text-slate-400 max-w-sm mx-auto">Riwayat hasil ujian CBT Anda akan muncul di sini setelah Anda menyelesaikan sesi ujian.</p>
                    <a href="{{ route('student.cbt.dashboard') }}" class="inline-flex items-center mt-8 px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 hover:-translate-y-1 transition-all">
                        Cek Jadwal Ujian <i class="fas fa-arrow-right ml-3"></i>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
</style>
@endsection
