@extends('layouts.ppdb')

@section('title', 'Portal CBT')

@section('content')
<!-- PREMIUM CBT DASHBOARD - ULTRA MODERN -->
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION (SAME AS MAIN DASHBOARD) -->
    <div class="header-banner bg-grad-indigo pt-10 pb-24 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <div class="profile-frame p-1 rounded-[2rem] bg-white/20 backdrop-blur-md">
                        <div class="w-20 h-20 bg-white/20 rounded-[1.8rem] flex items-center justify-center border-2 border-white/50 shadow-2xl backdrop-blur-md text-white">
                            <i class="fas fa-laptop-code text-3xl"></i>
                        </div>
                    </div>
                    <div class="text-white text-center md:text-left">
                        <span class="bg-white/20 backdrop-blur-md text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2 inline-block">Computer Based Test</span>
                        <h1 class="text-3xl font-black tracking-tight leading-tight">Portal Ujian Digital</h1>
                        <p class="text-white/70 text-xs font-bold mt-1"><i class="fas fa-user-graduate mr-2"></i> {{ $student->nama_lengkap }} • {{ $student->classGroup->group_name ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-3xl border border-white/10 text-center min-w-[120px]">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Tahun Pelajaran</span>
                        <span class="text-xs font-black text-white">{{ $student->academicYear->year ?? '2023/2024' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute right-[-50px] top-[-50px] w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute left-[-30px] bottom-[-30px] w-48 h-48 bg-indigo-400/10 rounded-full blur-2xl"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-12 relative z-20">
        
        <!-- KPI SECTION -->
        <div class="row g-4 mb-10">
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ujian Aktif</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $activeExams->count() }} <small class="text-[10px] text-slate-400">Mapel</small></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Selesai</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">{{ $activeExams->where('status', 'finished')->count() }} <small class="text-[10px] text-slate-400">Ujian</small></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">--</h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card bg-white p-6 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-50 group hover:-translate-y-2 transition-all duration-300">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-sm">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu Sisa</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-0">--</h3>
                </div>
            </div>
        </div>

        <!-- EXAM LIST -->
        <div class="bg-white rounded-[3rem] p-1 shadow-2xl shadow-slate-200/50 border border-slate-50 overflow-hidden mb-10">
            <div class="p-10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h4 class="text-2xl font-black text-slate-800 mb-1">Daftar Ujian Hari Ini</h4>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                @if($activeExams->isEmpty())
                    <div class="text-center py-20 bg-slate-50/50 rounded-[2.5rem] border border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-white text-slate-200 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-xl">
                            <i class="fas fa-calendar-times text-4xl"></i>
                        </div>
                        <h5 class="text-xl font-black text-slate-800 mb-2">Belum Ada Jadwal</h5>
                        <p class="text-sm text-slate-400 max-w-xs mx-auto">Tidak ada jadwal ujian untuk Anda hari ini. Selamat beristirahat atau gunakan waktu untuk belajar!</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($activeExams as $exam)
                            @php
                                $studentExam = $exam->studentExams->first();
                                $status = $studentExam ? $studentExam->status : 'not_started';
                                $isFinished = $status === 'finished';
                            @endphp
                            <div class="group bg-white border border-slate-100 rounded-[3rem] p-8 transition-all duration-500 hover:shadow-2xl hover:border-indigo-100 {{ $isFinished ? 'opacity-70 grayscale-[0.5]' : '' }}">
                                <div class="flex flex-col md:flex-row items-center gap-8">
                                    <div class="w-24 h-24 bg-indigo-50 rounded-[2rem] flex flex-col items-center justify-center text-indigo-600 transition-all group-hover:bg-indigo-600 group-hover:text-white shadow-inner">
                                        <i class="fas fa-file-alt text-2xl mb-1"></i>
                                        <span class="text-[8px] font-black uppercase">CBT</span>
                                    </div>
                                    <div class="flex-grow text-center md:text-left">
                                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-3">
                                            <span class="text-[9px] font-black text-indigo-500 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-widest border border-indigo-100">{{ $exam->bank->subject->name ?? 'Mapel' }}</span>
                                            @if($isFinished)
                                                <span class="text-[9px] font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-100">SELESAI</span>
                                            @elseif($status === 'doing')
                                                <span class="text-[9px] font-black text-amber-500 bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest border border-amber-100 animate-pulse">BERJALAN</span>
                                            @endif
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-800 mb-4 group-hover:text-indigo-600 transition-colors">{{ $exam->name }}</h3>
                                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            <div class="flex items-center"><i class="far fa-clock mr-2 text-indigo-400"></i> {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} WIB</div>
                                            <div class="flex items-center"><i class="fas fa-hourglass-start mr-2 text-indigo-400"></i> {{ $exam->duration_minutes }} MENIT</div>
                                            <div class="flex items-center"><i class="fas fa-list-ol mr-2 text-indigo-400"></i> {{ $exam->bank->questions->count() }} SOAL</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center justify-center gap-4">
                                        @if($isFinished)
                                            <div class="text-center bg-slate-50 px-8 py-4 rounded-[2rem] border border-slate-100">
                                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block">HASIL UJIAN</span>
                                                <div class="text-3xl font-black text-emerald-600">{{ number_format($studentExam->final_score, 1) }}</div>
                                            </div>
                                        @elseif($status === 'doing')
                                            <div class="flex flex-col gap-3 w-full md:w-auto">
                                                <a href="{{ route('student.cbt.exam', $exam->id) }}" class="w-full bg-amber-500 text-white rounded-2xl py-4 px-10 font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-amber-100 flex items-center justify-center">
                                                    LANJUTKAN UJIAN <i class="fas fa-play ml-3"></i>
                                                </a>
                                            </div>
                                        @else
                                            <form action="{{ route('student.cbt.join', $exam->id) }}" method="POST" class="flex flex-col gap-3 w-full md:w-auto">
                                                @csrf
                                                <input type="text" name="token" placeholder="INPUT TOKEN" required class="w-full md:w-48 bg-slate-50 border-0 text-slate-900 text-sm font-black rounded-2xl focus:ring-4 focus:ring-indigo-50 block p-4 tracking-[0.3em] text-center" autocomplete="off" maxlength="6">
                                                <button type="submit" class="w-full bg-indigo-600 text-white rounded-2xl py-4 font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100 flex items-center justify-center">
                                                    MASUK UJIAN <i class="fas fa-sign-in-alt ml-3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- INFO BOXES -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="bg-grad-indigo rounded-[3rem] p-10 text-white relative overflow-hidden h-100 shadow-2xl">
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 border border-white/20">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <h4 class="text-2xl font-black mb-2">Keamanan Sistem</h4>
                        <p class="text-indigo-100 text-sm font-medium leading-relaxed mb-0 opacity-80">
                            Sistem CBT dilengkapi dengan deteksi kecurangan otomatis. Segala bentuk perpindahan tab atau aplikasi akan dicatat sebagai pelanggaran.
                        </p>
                    </div>
                    <i class="fas fa-user-secret absolute right-[-20px] bottom-[-20px] text-white/5 fa-8x"></i>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-50 h-100">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Tata Tertib Ujian</h4>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-500 leading-relaxed mb-0">Pastikan koneksi internet stabil sebelum memulai ujian untuk menghindari gangguan teknis.</p>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="text-xs font-bold text-slate-500 leading-relaxed mb-0">Jawaban akan tersimpan otomatis setiap kali Anda menekan tombol simpan atau navigasi soal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .kpi-card { transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
</style>
@endsection
