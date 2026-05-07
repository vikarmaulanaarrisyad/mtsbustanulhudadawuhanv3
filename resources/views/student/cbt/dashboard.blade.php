@extends('layouts.ppdb')

@section('title', 'Ujian Berbasis Komputer (CBT)')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <!-- Header Section -->
    <div class="bg-indigo-600 pt-12 pb-24 px-6 md:px-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-indigo-700 rounded-full opacity-20 blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 flex items-center justify-center shadow-2xl overflow-hidden">
                    @if(Auth::user()->path_image)
                        <img src="{{ Storage::url(Auth::user()->path_image) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user-graduate text-4xl text-white"></i>
                    @endif
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ $student->nama_lengkap }}</h1>
                    <div class="flex flex-wrap items-center gap-3 mt-2">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full border border-white/20 uppercase tracking-widest">Kelas {{ $student->kelas_lengkap }}</span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full border border-white/20 uppercase tracking-widest">NISN: {{ $student->nisn }}</span>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex flex-col items-end text-white">
                <div class="text-indigo-100 text-sm font-bold uppercase tracking-widest mb-1">Status Akademik</div>
                <div class="text-xl font-black bg-white/20 px-4 py-2 rounded-2xl backdrop-blur-xl border border-white/30">TA: {{ $student->academicYear->year ?? '2023/2024' }}</div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-6 md:px-12 -mt-16 relative z-20">
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg animate-bounce">
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i> {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg">
                <i class="fas fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Exam List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 h-full">
                    <div class="flex items-center justify-between mb-8">
                        <h4 class="text-xl font-black text-slate-800 uppercase tracking-widest flex items-center">
                            <i class="fas fa-calendar-alt mr-3 text-indigo-600"></i> Ujian Aktif Hari Ini
                        </h4>
                        <div class="hidden md:block px-4 py-2 bg-slate-50 text-slate-500 text-xs font-black rounded-xl uppercase tracking-tighter">
                            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                        </div>
                    </div>
                    
                    @if($activeExams->isEmpty())
                        <div class="text-center py-20 border-2 border-dashed border-slate-200 rounded-[2rem]">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-ghost text-4xl text-slate-300"></i>
                            </div>
                            <h5 class="text-xl font-bold text-slate-700">Tenang Saja...</h5>
                            <p class="text-slate-400 mt-2">Tidak ada jadwal ujian yang perlu Anda khawatirkan hari ini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($activeExams as $exam)
                                @php
                                    $studentExam = $exam->studentExams->first();
                                    $status = $studentExam ? $studentExam->status : 'not_started';
                                    $isFinished = $status === 'finished';
                                @endphp
                                <div class="group relative overflow-hidden bg-white border-2 {{ $isFinished ? 'border-slate-100' : 'border-slate-200 hover:border-indigo-400' }} rounded-[2rem] p-6 transition-all duration-300">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                                    {{ $exam->bank->subject->name ?? 'Mapel' }}
                                                </span>
                                                @if($status === 'finished')
                                                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100">Selesai</span>
                                                @elseif($status === 'doing')
                                                    <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-amber-100 animate-pulse">Berlangsung</span>
                                                @else
                                                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-indigo-100">Tersedia</span>
                                                @endif
                                            </div>
                                            <h5 class="text-2xl font-black text-slate-800 leading-tight mb-4 group-hover:text-indigo-600 transition-colors">{{ $exam->name }}</h5>
                                            
                                            <div class="flex flex-wrap gap-4 text-sm font-medium text-slate-500">
                                                <span class="flex items-center"><i class="far fa-clock mr-2 text-slate-400"></i> {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}</span>
                                                <span class="flex items-center"><i class="fas fa-stopwatch mr-2 text-slate-400"></i> {{ $exam->duration_minutes }} Menit</span>
                                                <span class="flex items-center"><i class="fas fa-layer-group mr-2 text-slate-400"></i> {{ $exam->bank->questions->count() }} Soal</span>
                                            </div>
                                        </div>

                                        <div class="w-full md:w-auto">
                                            @if($isFinished)
                                                <div class="text-right">
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Skor Akhir</div>
                                                    <div class="text-4xl font-black text-emerald-600">{{ number_format($studentExam->final_score, 1) }}</div>
                                                </div>
                                            @else
                                                <form action="{{ route('student.cbt.join', $exam->id) }}" method="POST" class="flex flex-col md:flex-row gap-3">
                                                    @csrf
                                                    <div class="relative">
                                                        <input type="text" name="token" placeholder="TOKEN" required class="w-full md:w-32 bg-slate-100 border-0 text-slate-900 text-sm font-black uppercase rounded-2xl focus:ring-2 focus:ring-indigo-500 block p-4 tracking-[0.2em] text-center" autocomplete="off" maxlength="6">
                                                    </div>
                                                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                                                        Masuk <i class="fas fa-sign-in-alt ml-2"></i>
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

            <!-- Right Sidebar: Stats & Info -->
            <div class="space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Informasi Sistem</h4>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-800 leading-none mb-1">Secure Exam</div>
                                <div class="text-xs text-slate-500 font-medium">Layar akan terkunci otomatis.</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                <i class="fas fa-sync text-xl"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-800 leading-none mb-1">Auto Sync</div>
                                <div class="text-xs text-slate-500 font-medium">Jawaban tersimpan otomatis.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-200/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                    <h4 class="text-lg font-black mb-4 flex items-center">
                        <i class="fas fa-lightbulb mr-2 text-yellow-400"></i> Tips Ujian
                    </h4>
                    <p class="text-sm text-indigo-100 font-medium leading-relaxed mb-6">
                        Pastikan baterai perangkat Anda cukup dan gunakan koneksi internet yang stabil untuk kelancaran pengerjaan soal.
                    </p>
                    <div class="pt-6 border-t border-white/10">
                        <div class="flex justify-between items-end">
                            <div>
                                <div class="text-[10px] font-black uppercase tracking-widest text-indigo-300">Waktu Server</div>
                                <div class="text-2xl font-black text-white font-mono">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
                            </div>
                            <i class="fas fa-clock text-4xl opacity-20"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
