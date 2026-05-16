@extends('layouts.ppdb')

@section('title', 'Portal CBT')

@section('content')
<!-- PREMIUM CBT DASHBOARD - ULTRA MODERN -->
<div class="dashboard-wrapper pb-20">
    <!-- TOP HEADER SECTION -->
    <div class="header-banner bg-grad-indigo pt-12 pb-32 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-6 md:space-x-8">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-[2.2rem] blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>
                        <div class="profile-frame relative p-1 rounded-[2.2rem] bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl">
                            <div class="w-24 h-24 md:w-28 md:h-28 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-[2rem] flex items-center justify-center border-2 border-white/50 shadow-2xl backdrop-blur-md text-white">
                                <i class="fas fa-laptop-code text-4xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="text-white text-center md:text-left">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-3">
                            <span class="bg-indigo-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg">Computer Based Test</span>
                            <span class="bg-emerald-500/40 backdrop-blur-md text-[9px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest border border-white/20 shadow-lg">Digital Portal</span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none mb-3">Portal Ujian</h1>
                        <div class="flex items-center justify-center md:justify-start space-x-4 text-white/70 text-[10px] md:text-xs font-black uppercase tracking-widest">
                            <span class="flex items-center"><i class="fas fa-user-graduate mr-2 text-indigo-400"></i> {{ $student->nama_lengkap }}</span>
                            <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                            <span class="flex items-center"><i class="fas fa-layer-group mr-2 text-indigo-400"></i> {{ $student->classGroup->group_name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-md px-6 py-3 rounded-[2rem] border border-white/10 text-center shadow-2xl">
                        <span class="block text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Tahun Pelajaran</span>
                        <span class="text-sm font-black text-white">{{ $student->academicYear->year ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Animated Background Elements -->
        <div class="absolute right-[-100px] top-[-100px] w-96 h-96 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-indigo-500/20 rounded-full blur-[80px]"></div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="max-w-7xl mx-auto px-6 -mt-16 relative z-20">
        
        <!-- KPI SECTION - VIBRANT GRID -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 mb-12">
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(99,102,241,0.2)]">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-file-signature text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ujian Aktif</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $activeExams->count() }} <small class="text-xs text-slate-300 font-bold uppercase ml-1">Mapel</small></h3>
                </div>
            </div>
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(16,185,129,0.2)]">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Selesai</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['finished_count'] }} <small class="text-xs text-slate-300 font-bold uppercase ml-1">Ujian</small></h3>
                </div>
            </div>
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(245,158,11,0.2)]">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nilai Rata-rata</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ number_format($stats['average_score'], 1) }}</h3>
                </div>
            </div>
            <div class="group">
                <div class="bg-white p-6 md:p-8 rounded-[3rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-50 relative overflow-hidden transition-all duration-500 hover:-translate-y-3 hover:shadow-[0_30px_60px_-15px_rgba(245,158,11,0.2)]">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ranking Kelas</span>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['class_rank'] }} <small class="text-xs text-slate-300 font-bold uppercase ml-1">Dari {{ $stats['total_students'] }}</small></h3>
                </div>
            </div>
        </div>
>

        <!-- EXAM LIST -->
        <div class="bg-white rounded-[3.5rem] p-1 shadow-[0_30px_100px_-20px_rgba(0,0,0,0.04)] border border-slate-50 overflow-hidden mb-12">
            <div class="p-8 md:p-12">
                <div class="flex items-center justify-between mb-12">
                    <div class="flex items-center space-x-6">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                            <i class="fas fa-list-ul text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-2xl md:text-3xl font-black text-slate-800 mb-1 tracking-tight">Daftar Ujian Hari Ini</h4>
                            <p class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                </div>

                @if($activeExams->isEmpty())
                    <div class="text-center py-24 bg-slate-50/50 rounded-[3rem] border border-dashed border-slate-200">
                        <div class="w-28 h-28 bg-white text-slate-200 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 shadow-xl">
                            <i class="fas fa-calendar-times text-5xl"></i>
                        </div>
                        <h5 class="text-2xl font-black text-slate-800 mb-3 tracking-tight">Tidak Ada Ujian</h5>
                        <p class="text-sm text-slate-400 max-w-sm mx-auto font-medium leading-relaxed">Saat ini tidak ada jadwal ujian yang aktif untuk Anda. Silakan hubungi admin kurikulum jika terjadi kesalahan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-8">
                        @foreach($activeExams as $exam)
                            @php
                                $studentExam = $exam->studentExams->first();
                                $status = $studentExam ? $studentExam->status : 'not_started';
                                $isFinished = $status === 'finished';
                            @endphp
                            <div class="group bg-white border border-slate-100 rounded-[3.5rem] p-8 md:p-10 transition-all duration-700 hover:shadow-[0_40px_120px_-20px_rgba(0,0,0,0.08)] hover:border-indigo-100 relative overflow-hidden {{ $isFinished ? 'opacity-70' : '' }}">
                                <div class="flex flex-col lg:flex-row items-center gap-10">
                                    <div class="relative">
                                        <div class="absolute -inset-2 bg-indigo-500 rounded-[2.5rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                                        <div class="relative w-28 h-28 bg-white border border-slate-100 rounded-[2.5rem] flex flex-col items-center justify-center text-indigo-600 transition-all group-hover:scale-105 duration-500 shadow-xl">
                                            <i class="fas fa-book-reader text-3xl mb-2"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest">MAPEL</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-grow text-center lg:text-left">
                                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-4">
                                            <span class="text-[10px] font-black text-indigo-600 bg-indigo-50/50 px-5 py-1.5 rounded-full uppercase tracking-widest border border-indigo-100/50 shadow-sm">{{ $exam->bank->subject->name ?? 'Mapel' }}</span>
                                            @if($isFinished)
                                                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50/50 px-5 py-1.5 rounded-full uppercase tracking-widest border border-emerald-100/50 flex items-center"><i class="fas fa-check-circle mr-2"></i> SELESAI</span>
                                            @elseif($status === 'doing')
                                                <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-5 py-1.5 rounded-full uppercase tracking-widest border border-amber-100 animate-pulse flex items-center"><i class="fas fa-running mr-2"></i> SEDANG BERJALAN</span>
                                            @else
                                                <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-5 py-1.5 rounded-full uppercase tracking-widest border border-blue-100 flex items-center"><i class="fas fa-clock mr-2"></i> SIAP DIMULAI</span>
                                            @endif
                                        </div>
                                        <h3 class="text-3xl md:text-4xl font-black text-slate-800 mb-6 tracking-tighter group-hover:text-indigo-600 transition-colors leading-none">{{ $exam->name }}</h3>
                                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-8 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                            <div class="flex items-center group/info"><i class="far fa-calendar-alt mr-3 text-indigo-400 transition-transform group-hover/info:scale-125"></i> {{ \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('d M Y') }}</div>
                                            <div class="flex items-center group/info"><i class="far fa-clock mr-3 text-indigo-400 transition-transform group-hover/info:scale-125"></i> {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} WIB</div>
                                            <div class="flex items-center group/info"><i class="fas fa-hourglass-half mr-3 text-indigo-400 transition-transform group-hover/info:scale-125"></i> {{ $exam->duration_minutes }} MENIT</div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center justify-center gap-6 w-full lg:w-auto">
                                        @if($isFinished)
                                            <div class="text-center bg-emerald-50/50 px-10 py-6 rounded-[2.5rem] border border-emerald-100/50 shadow-inner min-w-[180px]">
                                                @if($exam->display_result)
                                                    <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2 block">SKOR ANDA</span>
                                                    <div class="text-5xl font-black text-emerald-600 tracking-tighter mb-4">{{ number_format($studentExam->final_score, 1) }}</div>
                                                    
                                                    @if($exam->generate_certificate && $studentExam->final_score >= $exam->passing_grade)
                                                        <a href="{{ route('student.cbt.certificate', $exam->id) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-amber-600 text-white text-[9px] font-black px-5 py-2.5 rounded-full uppercase tracking-widest shadow-lg shadow-amber-200 hover:scale-105 transition-transform">
                                                            <i class="fas fa-certificate text-xs"></i> UNDUH SERTIFIKAT
                                                        </a>
                                                    @endif
                                                @else
                                                    <div class="flex flex-col items-center py-2">
                                                        <div class="w-12 h-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mb-3 shadow-lg shadow-emerald-200">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                        <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">HASIL TERKIRIM</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($status === 'doing')
                                            <a href="{{ route('student.cbt.exam', $exam->id) }}" class="w-full lg:w-auto bg-slate-900 text-white rounded-[1.8rem] py-5 px-14 font-black text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-2xl active:scale-95 flex items-center justify-center group/btn relative overflow-hidden">
                                                <span class="relative z-10">LANJUTKAN</span>
                                                <i class="fas fa-play ml-3 relative z-10 transition-transform group-hover/btn:translate-x-1"></i>
                                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                            </a>
                                        @else
                                            <form action="{{ route('student.cbt.join', $exam->id) }}" method="POST" class="flex flex-col gap-4 w-full lg:w-auto">
                                                @csrf
                                                <div class="relative group/input">
                                                    <input type="text" name="token" placeholder="INPUT TOKEN" required class="w-full lg:w-56 bg-slate-50 border-2 border-slate-100 text-slate-900 text-sm font-black rounded-[1.5rem] focus:ring-0 focus:border-indigo-400 block p-5 tracking-[0.4em] text-center transition-all" autocomplete="off" maxlength="6">
                                                    <div class="absolute inset-0 rounded-[1.5rem] border border-indigo-400 opacity-0 group-focus-within/input:opacity-100 pointer-events-none transition-opacity"></div>
                                                </div>
                                                <button type="submit" class="w-full lg:w-auto bg-indigo-600 text-white rounded-[1.5rem] py-5 font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100 flex items-center justify-center group/join">
                                                    MULAI UJIAN <i class="fas fa-sign-in-alt ml-3 transition-transform group-hover/join:translate-x-1"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="absolute top-0 right-0 p-12 opacity-[0.02] group-hover:opacity-[0.05] transition-opacity pointer-events-none">
                                    <i class="fas fa-laptop-code fa-8x"></i>
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
