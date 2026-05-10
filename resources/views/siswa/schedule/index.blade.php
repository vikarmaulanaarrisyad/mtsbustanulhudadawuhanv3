@extends('layouts.ppdb')

@section('title', 'Jadwal Pelajaran')

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
                        <span class="bg-emerald-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg mb-2 inline-block">Akademik</span>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none">Jadwal Pelajaran</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-emerald-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $days = [
                    'Senin' => 'indigo',
                    'Selasa' => 'emerald',
                    'Rabu' => 'amber',
                    'Kamis' => 'rose',
                    'Jumat' => 'blue',
                    'Sabtu' => 'purple'
                ];
            @endphp

            @foreach($days as $day => $color)
                <div class="bg-white rounded-[3rem] p-8 shadow-2xl shadow-slate-200/50 border border-slate-50 relative overflow-hidden group transition-all duration-500 hover:-translate-y-2">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-{{ $color }}-50 text-{{ $color }}-600 rounded-2xl flex items-center justify-center shadow-inner">
                                <i class="fas fa-calendar-day text-xl"></i>
                            </div>
                            <h4 class="text-xl font-black text-slate-800 tracking-tight">{{ $day }}</h4>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $schedules->has($day) ? $schedules[$day]->count() . ' Mapel' : 'Libur' }}</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($schedules->get($day, collect()) as $sch)
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 group-hover:bg-white group-hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-black text-{{ $color }}-600 uppercase tracking-widest bg-{{ $color }}-50 px-2 py-1 rounded-lg">
                                        {{ substr($sch->start_time, 0, 5) }} - {{ substr($sch->end_time, 0, 5) }}
                                    </span>
                                </div>
                                <h6 class="text-sm font-black text-slate-800 mb-1 leading-tight">{{ $sch->subject->subject_name ?? '-' }}</h6>
                                <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <i class="fas fa-user-tie mr-2 text-{{ $color }}-400"></i> {{ $sch->teacher->name ?? '-' }}
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0">Tidak ada jadwal</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Subtle background decoration -->
                    <div class="absolute right-0 bottom-0 p-8 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity">
                        <i class="fas fa-book fa-5x"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; font-family: 'Outfit', sans-serif; }
    .bg-grad-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); }
</style>
@endsection
