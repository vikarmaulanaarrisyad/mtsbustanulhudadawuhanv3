@extends('layouts.ppdb')

@section('title', 'CBT: ' . $exam->name)

@section('content')
<!-- ULTRA PREMIUM CBT ENGINE V3 -->
<div class="cbt-container fixed inset-0 z-[150] bg-[#fdfdfd] flex flex-col hidden overflow-hidden select-none" id="cbt-engine">
    <!-- TOP NAVIGATION BAR (GLASSMORPHISM) -->
    <div class="h-16 md:h-20 bg-white/95 backdrop-blur-2xl border-b border-slate-100 flex items-center justify-between px-3 md:px-12 shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] relative z-[70]">
        <div class="flex items-center space-x-2 md:space-x-5">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-lg md:rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative w-8 h-8 md:w-12 md:h-12 bg-indigo-600 rounded-lg md:rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100 transform rotate-3 transition-transform group-hover:rotate-0">
                    <i class="fas fa-laptop-code text-sm md:text-xl"></i>
                </div>
            </div>
            <div class="flex flex-col">
                <h1 class="text-[10px] md:text-xl font-black text-slate-800 leading-tight tracking-tight truncate max-w-[80px] md:max-w-none">{{ $exam->name }}</h1>
                <div class="flex items-center space-x-1">
                    <span class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></span>
                    <p class="text-[6px] md:text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] truncate max-w-[60px] md:max-w-none">{{ $exam->bank->subject->name ?? 'Mata Pelajaran' }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-2 md:space-x-10">
            <div class="hidden sm:flex flex-col items-center md:items-end bg-rose-50 px-3 py-1.5 md:px-6 md:py-2.5 rounded-xl md:rounded-[1.5rem] border border-rose-100">
                <span class="text-[6px] md:text-[9px] text-rose-400 font-black uppercase tracking-[0.2em] mb-0.5">Pelanggaran</span>
                <div class="flex items-center space-x-2">
                    <span class="text-xs md:text-xl font-black text-rose-600" id="violation-count">{{ $studentExam->violation_count ?? 0 }}</span>
                    <span class="text-[8px] md:text-sm font-black text-rose-300">/ 3</span>
                </div>
            </div>

            <div class="timer-display flex flex-col items-center md:items-end bg-slate-900 px-3 py-1.5 md:px-6 md:py-2.5 rounded-xl md:rounded-[1.5rem] shadow-2xl shadow-indigo-100/50 border border-slate-800">
                <span class="text-[6px] md:text-[9px] text-slate-500 font-black uppercase tracking-[0.2em] mb-0.5">Sisa Waktu</span>
                <div class="text-xs md:text-3xl font-black text-white font-mono tracking-tighter leading-none" id="timer">--:--:--</div>
            </div>
            <div class="h-8 w-px bg-slate-100 hidden md:block"></div>
            <button onclick="finishExam()" class="group relative px-3 py-2 md:px-10 md:py-4 overflow-hidden rounded-xl md:rounded-2xl bg-white text-slate-900 border border-slate-200 font-black text-[9px] md:text-sm uppercase tracking-widest transition-all hover:bg-rose-600 hover:text-white hover:border-rose-600 hover:shadow-[0_20px_40px_-10px_rgba(225,29,72,0.3)] active:scale-95">
                <span class="relative z-10 hidden md:block">Akhiri Ujian</span>
                <span class="relative z-10 md:hidden">AKHIRI</span>
            </button>
            <button onclick="toggleNav()" class="lg:hidden w-8 h-8 md:w-10 md:h-10 bg-indigo-50 rounded-lg md:rounded-xl flex items-center justify-center text-indigo-600 shadow-inner hover:bg-indigo-600 hover:text-white transition-all">
                <i class="fas fa-th-large text-xs md:text-base"></i>
            </button>
        </div>
    </div>

    <!-- MAIN ENGINE AREA -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- LEFT: QUESTION CONTENT -->
        <div class="flex-1 overflow-y-auto p-4 md:p-16 relative pb-44 custom-scrollbar bg-slate-50/30" id="question-area">
            <div class="max-w-4xl mx-auto">
                @foreach($exam->bank->questions as $index => $q)
                    @php
                        $ans = $answers->get($q->id);
                        $isDoubtful = $ans && $ans->is_doubtful ? 1 : 0;
                    @endphp
                    <div class="question-panel hidden animate-fade-in" id="q-panel-{{ $index + 1 }}" data-qid="{{ $q->id }}" data-doubtful="{{ $isDoubtful }}">
                        <div class="mb-12 flex items-center justify-between">
                            <div class="flex items-center space-x-6">
                                <div class="relative">
                                    <div class="absolute -inset-2 bg-indigo-500 rounded-full blur opacity-10 animate-pulse"></div>
                                    <span class="relative w-12 h-12 md:w-16 md:h-16 bg-white border border-slate-100 text-indigo-600 flex items-center justify-center rounded-2xl md:rounded-[1.8rem] font-black text-xl md:text-3xl shadow-xl">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="hidden md:block">
                                    <div class="h-1.5 w-20 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-600 w-1/3"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em] mt-2 block">Butir Soal</span>
                                </div>
                            </div>
                        </div>

                        <!-- QUESTION CARD -->
                        <div class="bg-white rounded-[2rem] md:rounded-[3.5rem] p-5 md:p-14 border border-slate-100 shadow-[0_30px_100px_-20px_rgba(0,0,0,0.04)] mb-8 md:mb-10 group/card hover:shadow-[0_40px_120px_-20px_rgba(0,0,0,0.08)] transition-all duration-700">
                            <div class="prose-container mb-8 md:mb-12">
                                <div class="text-lg md:text-[1.75rem] text-slate-800 font-medium leading-relaxed md:leading-[1.6] tracking-tight antialiased">
                                    {!! $q->question_text !!}
                                </div>
                            </div>

                            @if($q->question_image)
                                <div class="mb-8 md:mb-12 rounded-2xl md:rounded-[2.5rem] overflow-hidden border-4 md:border-[12px] border-slate-50 shadow-xl md:shadow-2xl bg-white text-center group/img">
                                    <img src="{{ Storage::url($q->question_image) }}" class="max-h-[300px] md:max-h-[600px] w-auto mx-auto object-contain transition-transform duration-700 group-hover/img:scale-[1.02]" alt="Gambar Soal">
                                </div>
                            @endif

                            <!-- OPTIONS GRID -->
                            <div class="grid grid-cols-1 gap-4 md:gap-6">
                                @if(in_array($q->question_type, ['pilihan_ganda', 'ganda_komplek']))
                                    <div class="grid grid-cols-1 gap-5">
                                        @foreach($q->options as $optIndex => $opt)
                                            @php
                                                $ans = $answers->get($q->id);
                                                $isChecked = $ans && $ans->cbt_option_id == $opt->id;
                                                $letter = chr(65 + $optIndex);
                                            @endphp
                                            <label class="option-label group flex items-start p-4 md:p-8 border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-50/50 shadow-2xl shadow-indigo-100/50' : 'border-slate-50 bg-slate-50/30 hover:border-indigo-300 hover:bg-white hover:shadow-xl' }} rounded-3xl md:rounded-[2.5rem] cursor-pointer transition-all duration-300">
                                                <div class="flex items-center justify-center w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl {{ $isChecked ? 'bg-indigo-600 text-white' : 'bg-white text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600' }} font-black text-base md:text-xl transition-all shadow-sm border border-slate-100 shrink-0">
                                                    {{ $letter }}
                                                </div>
                                                
                                                <div class="ml-6 flex-1">
                                                    <div class="hidden">
                                                        @if($q->question_type === 'pilihan_ganda')
                                                            <input type="radio" name="answer_{{ $q->id }}" value="{{ $opt->id }}" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }})">
                                                        @else
                                                            <input type="checkbox" name="answer_{{ $q->id }}[]" value="{{ $opt->id }}" {{ $isChecked ? 'checked' : '' }} onchange="saveAnswer({{ $q->id }}, {{ $opt->id }}, {{ $index + 1 }}, true)">
                                                        @endif
                                                    </div>
                                                    
                                                    @if($opt->option_image)
                                                        <div class="mb-4 rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-white inline-block">
                                                            <img src="{{ Storage::url($opt->option_image) }}" class="max-h-48 w-auto object-contain p-2" alt="Gambar Opsi">
                                                        </div>
                                                    @endif
                                                    <div class="text-slate-700 font-bold text-base md:text-xl leading-relaxed">{!! $opt->option_text !!}</div>
                                                </div>
                                                
                                                <div class="ml-2 md:ml-4 mt-2 md:mt-3">
                                                    <div class="w-6 h-6 md:w-8 md:h-8 rounded-full border-2 {{ $isChecked ? 'border-indigo-600 bg-indigo-600' : 'border-slate-300 bg-white' }} flex items-center justify-center transition-all group-hover:border-indigo-400">
                                                        @if($isChecked) <i class="fas fa-check text-[8px] md:text-xs text-white"></i> @endif
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                @elseif($q->question_type === 'penjodohan')
                                    <div class="bg-indigo-50/30 rounded-[3rem] p-8 md:p-12 border-2 border-indigo-100/50">
                                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                                            <i class="fas fa-link mr-3 text-lg"></i> MODE PENJODOHAN
                                        </p>
                                        <div class="space-y-8">
                                            @php
                                                $pairs = is_array($q->matching_pairs) ? $q->matching_pairs : [];
                                            @endphp
                                            @foreach($pairs as $premise => $correctResponse)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                                    <div class="p-6 bg-white border border-slate-100 rounded-3xl shadow-xl text-lg font-bold text-slate-700">
                                                        {!! $premise !!}
                                                    </div>
                                                    <div class="relative group">
                                                        <select class="matching-select w-full p-6 bg-white border-2 border-slate-100 rounded-3xl focus:border-indigo-500 focus:ring-8 focus:ring-indigo-50 font-black text-slate-700 appearance-none transition-all shadow-xl" 
                                                                data-question-id="{{ $q->id }}" 
                                                                data-premise="{{ $premise }}"
                                                                onchange="saveMatchingAnswer({{ $q->id }}, {{ $index + 1 }})"
                                                                oninput="updateNavColorLocal({{ $index + 1 }})">
                                                            <option value="">Pilih Pasangan...</option>
                                                            @foreach($pairs as $p => $r)
                                                                <option value="{{ $r }}">{!! strip_tags($r) !!}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-indigo-400 group-hover:scale-125 transition-transform">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @elseif(in_array($q->question_type, ['essay', 'uraian']))
                                    <div class="bg-white rounded-[3rem] border-4 border-slate-50 focus-within:border-indigo-200 focus-within:ring-8 focus-within:ring-indigo-50 transition-all overflow-hidden shadow-2xl">
                                        <textarea name="answer_{{ $q->id }}" 
                                                  class="w-full p-10 text-xl font-bold text-slate-800 focus:ring-0 border-0 placeholder:text-slate-300" 
                                                  placeholder="Tuliskan jawaban lengkap Anda di sini..." 
                                                  rows="10"
                                                  oninput="updateNavColorLocal({{ $index + 1 }})"
                                                  onblur="saveEssayAnswer({{ $q->id }}, {{ $index + 1 }})">{{ $answers->get($q->id)->answer_text ?? '' }}</textarea>
                                    </div>
                                @endif

                                <!-- QUESTION CARD FOOTER (NAVIGATION) -->
                                <div class="mt-16 pt-10 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                                    <div class="flex items-center space-x-4 w-full md:w-auto">
                                        @if($index > 0)
                                            <button onclick="prevQuestion()" class="flex-1 md:flex-none h-14 px-10 bg-slate-100 text-slate-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center justify-center group">
                                                <i class="fas fa-chevron-left mr-3 transition-transform group-hover:-translate-x-1"></i> Kembali
                                            </button>
                                        @endif
                                        
                                        <label class="flex items-center cursor-pointer group bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100 hover:border-amber-200 transition-all">
                                            <span class="mr-3 text-[10px] font-black text-slate-400 group-hover:text-amber-500 uppercase tracking-widest transition-colors">Ragu-ragu</span>
                                            <div class="relative">
                                                <input type="checkbox" class="sr-only doubt-checkbox" data-no="{{ $index + 1 }}" onchange="toggleDoubtLocal({{ $index + 1 }})" {{ $ans && $ans->is_doubtful ? 'checked' : '' }}>
                                                <div class="w-10 h-5 bg-slate-200 rounded-full transition-colors box-bg"></div>
                                                <div class="absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-transform dot shadow-sm"></div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-4 w-full md:w-auto">
                                        <div class="hidden md:flex flex-col items-end mr-4">
                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Penyelesaian</span>
                                            <span class="text-sm font-black text-slate-800">{{ $index + 1 }} / {{ $exam->bank->questions->count() }}</span>
                                        </div>

                                        @if($index < $exam->bank->questions->count() - 1)
                                            <button onclick="nextQuestion()" class="flex-1 md:flex-none h-14 px-14 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-indigo-100 flex items-center justify-center group">
                                                Lanjut <i class="fas fa-chevron-right ml-3 transition-transform group-hover:translate-x-1"></i>
                                            </button>
                                        @else
                                            <button onclick="finishExam()" class="flex-1 md:flex-none h-14 px-14 bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-rose-100 flex items-center justify-center group">
                                                AKHIRI UJIAN <i class="fas fa-check-double ml-3 transition-transform group-hover:scale-110"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- RIGHT: NUMBER GRID SIDEBAR (DESKTOP PERMANENT) -->
        <div class="hidden lg:flex w-96 bg-white border-l border-slate-100 p-10 flex-col z-20 shadow-[-10px_0_40px_-20px_rgba(0,0,0,0.05)] relative">
            <div class="flex items-center space-x-4 mb-10">
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-layer-group text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest leading-none mb-1">Navigasi</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pilih Nomor Soal</p>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-3 custom-scrollbar">
                <div class="grid grid-cols-5 gap-3">
                    @foreach($exam->bank->questions as $index => $q)
                        @php
                            $ans = $answers->get($q->id);
                            $statusClass = 'bg-slate-50 border-slate-100 text-slate-300 hover:border-indigo-300 hover:text-indigo-600';
                            if ($ans) {
                                $statusClass = $ans->is_doubtful ? 'bg-amber-500 border-amber-400 text-white shadow-lg shadow-amber-200 animate-pulse' : 'bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-100';
                            }
                        @endphp
                        <button onclick="jumpTo({{ $index + 1 }})" class="q-nav-btn w-full aspect-square rounded-2xl border-2 font-black text-xs flex items-center justify-center transition-all duration-300 {{ $statusClass }}" id="nav-btn-desktop-{{ $index + 1 }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-slate-50">
                <h4 class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] mb-6">Informasi Status</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4"><div class="w-2.5 h-2.5 bg-indigo-600 rounded-full"></div><span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Terjawab</span></div>
                        <span class="text-[9px] font-bold text-slate-300">{{ $answers->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4"><div class="w-2.5 h-2.5 bg-amber-500 rounded-full"></div><span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Ragu-ragu</span></div>
                        <span class="text-[9px] font-bold text-slate-300">{{ $answers->where('is_doubtful', true)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: NUMBER GRID SIDEBAR (MOBILE DRAWER) -->
        <div class="lg:hidden fixed top-24 bottom-0 right-0 w-80 bg-white border-l border-slate-50 p-8 flex flex-col z-[80] transform translate-x-full transition-transform duration-700 ease-[cubic-bezier(0.19,1,0.22,1)] shadow-[-20px_0_60px_-15px_rgba(0,0,0,0.1)]" id="sidebar-nav">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Navigasi</h3>
                </div>
                <button onclick="toggleNav()" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid grid-cols-4 gap-3">
                    @foreach($exam->bank->questions as $index => $q)
                        @php
                            $ans = $answers->get($q->id);
                            $statusClass = 'bg-slate-50 border-slate-100 text-slate-300 hover:border-indigo-300 hover:text-indigo-600';
                            if ($ans) {
                                $statusClass = $ans->is_doubtful ? 'bg-amber-500 border-amber-400 text-white shadow-lg shadow-amber-200 animate-pulse' : 'bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-100';
                            }
                        @endphp
                        <button onclick="jumpTo({{ $index + 1 }})" class="q-nav-btn w-full aspect-square rounded-2xl border-2 font-black text-sm flex items-center justify-center transition-all duration-300 {{ $statusClass }}" id="nav-btn-mobile-{{ $index + 1 }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- AI PROCTORING MONITOR (FLOATING) -->
        <div class="fixed bottom-6 right-6 z-[200] group hidden" id="ai-proctor-window">
            <div class="relative bg-white/80 backdrop-blur-xl rounded-[2rem] p-3 shadow-2xl border border-white/50 transition-all duration-500 hover:scale-105 group-[.minimized]:w-14 group-[.minimized]:h-14 overflow-hidden">
                <div class="flex items-center space-x-3 mb-3 group-[.minimized]:hidden">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_#10b981]"></div>
                    <span class="text-[9px] font-black text-slate-800 uppercase tracking-widest">AI LIVE PROCTOR</span>
                    <button onclick="toggleAIProctor()" class="ml-auto text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas fa-minus text-[10px]"></i>
                    </button>
                </div>
                
                <div class="relative rounded-2xl overflow-hidden bg-black aspect-video group-[.minimized]:hidden w-44">
                    <video id="proctor-video" autoplay muted playsinline class="w-full h-full object-cover grayscale opacity-80"></video>
                    <canvas id="proctor-canvas" class="absolute inset-0 w-full h-full"></canvas>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    <div id="ai-status-overlay" class="absolute bottom-2 left-2 px-2 py-1 bg-emerald-500/90 text-[8px] font-black text-white rounded-lg uppercase tracking-widest hidden animate-pulse">
                        TERDETEKSI
                    </div>
                </div>

                <button onclick="toggleAIProctor()" class="absolute inset-0 hidden group-[.minimized]:flex items-center justify-center text-indigo-600">
                    <i class="fas fa-user-shield"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MOBILE OVERLAY -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] hidden lg:hidden transition-opacity duration-500" id="nav-overlay" onclick="toggleNav()"></div>

<!-- PRE-EXAM SCREEN (MODERNIZED) -->
<div class="max-w-4xl mx-auto my-10 md:my-20 px-4 md:px-6" id="pre-exam-screen">
    <div class="bg-white rounded-[3rem] md:rounded-[4rem] p-8 md:p-20 shadow-2xl shadow-slate-200 border border-slate-100 text-center relative overflow-hidden">
        <div class="w-32 h-32 bg-indigo-600/10 rounded-[3rem] flex items-center justify-center mx-auto mb-10 transform rotate-12 shadow-inner">
            <i class="fas fa-user-shield text-5xl text-indigo-600"></i>
        </div>
        
        <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight leading-none">{{ $exam->name }}</h2>
        <p class="text-lg text-slate-500 font-medium mb-12 max-w-xl mx-auto">Selamat datang di sistem CBT Madrasah. Pastikan Anda siap secara mental dan teknis sebelum menekan tombol mulai.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16 text-left">
            <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100">
                <h4 class="font-black text-slate-800 flex items-center mb-6 uppercase tracking-widest text-xs">
                    <i class="fas fa-info-circle mr-3 text-indigo-500 text-lg"></i> Rincian Ujian
                </h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center"><span class="text-sm font-bold text-slate-400">Durasi</span><span class="text-sm font-black text-slate-800">{{ $exam->duration_minutes }} Menit</span></div>
                    <div class="flex justify-between items-center"><span class="text-sm font-bold text-slate-400">Total Soal</span><span class="text-sm font-black text-slate-800">{{ $exam->bank->questions->count() }} Butir</span></div>
                    <div class="flex justify-between items-center"><span class="text-sm font-bold text-slate-400">Tipe</span><span class="text-sm font-black text-slate-800">CAMPURAN</span></div>
                </div>
            </div>
            <div class="p-8 bg-rose-50 rounded-[2.5rem] border border-rose-100">
                <h4 class="font-black text-rose-800 flex items-center mb-6 uppercase tracking-widest text-xs">
                    <i class="fas fa-shield-alt mr-3 text-rose-500 text-lg"></i> Aturan Keamanan
                </h4>
                <ul class="text-xs text-rose-600 space-y-3 list-none font-black uppercase tracking-widest">
                    <li class="flex items-center"><i class="fas fa-lock mr-2"></i> Mode Layar Penuh</li>
                    <li class="flex items-center"><i class="fas fa-eye mr-2"></i> Deteksi Perpindahan Tab</li>
                    <li class="flex items-center"><i class="fas fa-ban mr-2"></i> Maksimal 3 Pelanggaran</li>
                </ul>
            </div>
        </div>

        <button onclick="startCBT()" class="w-full md:w-auto px-16 py-6 bg-indigo-600 text-white rounded-[2rem] font-black text-xl hover:bg-slate-900 transition-all shadow-2xl shadow-indigo-200 transform hover:-translate-y-2 active:scale-95">
            MULAI UJIAN SEKARANG <i class="fas fa-play-circle ml-3"></i>
        </button>
        
        <!-- Decoration -->
        <div class="absolute left-[-50px] bottom-[-50px] w-64 h-64 bg-indigo-50 rounded-full blur-3xl -z-10"></div>
    </div>
</div>

<!-- FORM FINISH -->
<form id="finish-form" action="{{ route('student.cbt.finish', $exam->id) }}" method="POST" class="hidden">@csrf</form>

<style>
    .dot { transition: all 0.3s; }
    .doubt-checkbox:checked ~ .box-bg { background-color: #f59e0b !important; }
    .doubt-checkbox:checked ~ .dot { transform: translateX(20px); background-color: #fff !important; }

    .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script>
    const examId = {{ $exam->id }};
    const totalQuestions = {{ $exam->bank->questions->count() }};
    let currentQ = 1;
    let endTime = new Date("{{ \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d') }}T{{ $exam->end_time }}").getTime();
    let durationSeconds = {{ $exam->duration_minutes * 60 }};
    let studentStartTime = new Date("{{ \Carbon\Carbon::parse($studentExam->start_time)->format('Y-m-d\TH:i:s') }}").getTime();
    let maxAllowedTime = studentStartTime + (durationSeconds * 1000);
    let finalEndTime = Math.min(endTime, maxAllowedTime);

    function startTimer() {
        let x = setInterval(function() {
            let now = new Date().getTime();
            let distance = finalEndTime - now;
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("timer").innerHTML = "HABIS";
                forceSubmitExam();
                return;
            }
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("timer").innerHTML = (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds < 10 ? "0" + seconds : seconds);
        }, 1000);
    }

    function toggleNav() {
        $('#sidebar-nav').toggleClass('translate-x-full');
        $('#nav-overlay').toggleClass('hidden');
    }

    function toggleAIProctor() {
        $('#ai-proctor-window').toggleClass('minimized');
    }

    let isAIActive = false;
    let aiViolationStreak = 0;

    async function initAIProctoring() {
        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            ]);
            
            const video = document.getElementById('proctor-video');
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
            video.srcObject = stream;
            
            isAIActive = true;
            $('#ai-proctor-window').removeClass('hidden');

            video.addEventListener('play', () => {
                const canvas = document.getElementById('proctor-canvas');
                const displaySize = { width: video.clientWidth, height: video.clientHeight };
                faceapi.matchDimensions(canvas, displaySize);

                setInterval(async () => {
                    if (!isAIActive) return;
                    
                    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions());
                    const overlay = $('#ai-status-overlay');
                    
                    if (detections.length === 1) {
                        overlay.text('TERDETEKSI').removeClass('bg-rose-500 hidden').addClass('bg-emerald-500');
                        aiViolationStreak = 0;
                    } else if (detections.length === 0) {
                        overlay.text('WAJAH TIDAK ADA').removeClass('bg-emerald-500 hidden').addClass('bg-rose-500');
                        aiViolationStreak++;
                    } else {
                        overlay.text('MULTIPLE PERSON').removeClass('bg-emerald-500 hidden').addClass('bg-rose-500');
                        aiViolationStreak++;
                    }

                    if (aiViolationStreak >= 10) { // Trigger violation every ~50 seconds of issues
                        reportViolation();
                        aiViolationStreak = 0;
                    }
                }, 5000);
            });

        } catch (err) {
            console.warn('AI Proctoring failed to initialize:', err);
            $('#ai-proctor-window').addClass('hidden');
        }
    }

    function startCBT() {
        const enterExam = () => {
            $('#pre-exam-screen').addClass('hidden');
            $('#cbt-engine').removeClass('hidden');
            jumpTo(1);
            startTimer();
            initAntiCheat();
            initAIProctoring();
        };

        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen().then(enterExam).catch(() => {
                // If failed (common on some mobile browsers), allow entry but warn
                enterExam();
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'Peringatan Layar', 
                    text: 'Browser Anda tidak mendukung mode layar penuh otomatis. Harap tetap fokus pada halaman ujian.', 
                    timer: 3000,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2.5rem]' } 
                });
            });
        } else {
            // No fullscreen support (iOS Safari)
            enterExam();
        }
    }

    function jumpTo(qNo) {
        $('.question-panel').addClass('hidden');
        $(`#q-panel-${qNo}`).removeClass('hidden');
        currentQ = qNo;
        $('#btn-prev').prop('disabled', currentQ === 1);
        $('#btn-next').prop('disabled', currentQ === totalQuestions);
        $('#progress-text').text(`Soal ${currentQ} / ${totalQuestions}`);
        
        $('.q-nav-btn').removeClass('ring-8 ring-indigo-50 border-indigo-600 scale-110');
        $(`#nav-btn-desktop-${qNo}, #nav-btn-mobile-${qNo}`).addClass('ring-8 ring-indigo-50 border-indigo-600 scale-110');
        if (window.innerWidth < 1024 && !$('#sidebar-nav').hasClass('translate-x-full')) toggleNav();
    }

    function nextQuestion() { if(currentQ < totalQuestions) jumpTo(currentQ + 1); }
    function prevQuestion() { if(currentQ > 1) jumpTo(currentQ - 1); }

    function updateNavColorLocal(qNo) {
        const panel = $(`#q-panel-${qNo}`);
        const isDoubt = panel.find('.doubt-checkbox').is(':checked');
        const hasAnswer = checkHasAnswer(panel.data('qid'));
        const btn = $(`#nav-btn-desktop-${qNo}, #nav-btn-mobile-${qNo}`);
        
        if (hasAnswer) {
            btn.removeClass('bg-slate-50 border-slate-100 text-slate-300');
            if (isDoubt) {
                btn.removeClass('bg-indigo-600').addClass('bg-amber-500 border-amber-500 shadow-amber-200 animate-pulse');
            } else {
                btn.removeClass('bg-amber-500 shadow-amber-200 animate-pulse').addClass('bg-indigo-600 border-indigo-600 shadow-indigo-100 text-white');
            }
        } else {
            btn.removeClass('bg-indigo-600 bg-amber-500 shadow-amber-200 shadow-indigo-100 animate-pulse text-white').addClass('bg-slate-50 border-slate-100 text-slate-300');
        }
    }

    function toggleDoubtLocal(qNo) {
        const panel = $(`#q-panel-${qNo}`);
        const qid = panel.data('qid');
        const isChecked = panel.find('.doubt-checkbox').is(':checked');
        
        panel.data('doubtful', isChecked ? 1 : 0);
        
        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', {
            _token: '{{ csrf_token() }}',
            question_id: qid,
            is_doubtful: isChecked ? 1 : 0
        }).done(() => {
            const btn = $(`#nav-btn-desktop-${qNo}, #nav-btn-mobile-${qNo}`);
            if (isChecked) {
                btn.removeClass('bg-indigo-600').addClass('bg-amber-500 border-amber-500 shadow-amber-200 animate-pulse');
            } else {
                const hasAnswer = checkHasAnswer(qid);
                if (hasAnswer) {
                    btn.removeClass('bg-amber-500 shadow-amber-200 animate-pulse').addClass('bg-indigo-600 border-indigo-600 shadow-indigo-100');
                } else {
                    btn.removeClass('bg-amber-500 shadow-amber-200 animate-pulse').addClass('bg-slate-50 border-slate-100 text-slate-300');
                }
            }
        });
    }

    function checkHasAnswer(qid) {
        const panel = $(`.question-panel[data-qid="${qid}"]`);
        const type = panel.find('.options-grid, .matching-grid, textarea').length > 0;
        
        if (panel.find('input[type="radio"]:checked, input[type="checkbox"]:checked').length > 0) return true;
        if (panel.find('textarea').val()?.trim() !== '') return true;
        
        let matchingAnswered = true;
        panel.find('.matching-select').each(function() {
            if ($(this).val() === '') matchingAnswered = false;
        });
        if (panel.find('.matching-select').length > 0 && matchingAnswered) return true;

        return false;
    }

    function saveAnswer(questionId, optionId, qNo, isMultiple = false) {
        const panel = $(`#q-panel-${qNo}`);
        const isDoubt = panel.find('.doubt-checkbox').is(':checked');
        let btn = $(`#nav-btn-desktop-${qNo}, #nav-btn-mobile-${qNo}`);
        
        // Update local data attribute
        $(`#q-panel-${qNo}`).data('doubtful', isDoubt ? 1 : 0);

        btn.removeClass('bg-slate-50 border-transparent text-slate-300').addClass(isDoubt ? 'bg-amber-500 border-amber-500 text-white shadow-amber-200 animate-pulse' : 'bg-indigo-600 border-indigo-600 text-white shadow-indigo-100');
        
        if(!isMultiple) {
            panel.find('.option-label').removeClass('border-indigo-600 bg-indigo-50/50 shadow-2xl shadow-indigo-100/50').addClass('border-slate-50 bg-slate-50/30');
        }
        let input = panel.find(`input[value="${optionId}"]`);
        if(input.is(':checked')) {
            input.closest('.option-label').addClass('border-indigo-600 bg-indigo-50/50 shadow-2xl shadow-indigo-100/50').removeClass('border-slate-50 bg-slate-50/30');
        }
        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', { 
            _token: '{{ csrf_token() }}', 
            question_id: questionId, 
            option_id: optionId, 
            is_doubtful: isDoubt ? 1 : 0 
        }).fail(function() {
            console.error("Gagal menyimpan jawaban");
        });
    }

    function saveMatchingAnswer(questionId, qNo) {
        const panel = $(`#q-panel-${qNo}`);
        const isDoubt = panel.find('.doubt-checkbox').is(':checked');
        
        // Update local data attribute
        $(`#q-panel-${qNo}`).data('doubtful', isDoubt ? 1 : 0);

        let answers = [];
        $(`#q-panel-${qNo} .matching-select`).each(function() {
            answers.push({
                premise: $(this).data('premise'),
                response: $(this).val()
            });
        });

        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            matching_answers: answers,
            is_doubtful: isDoubt ? 1 : 0
        });
        
        const btn = $(`#nav-btn-desktop-${qNo}, #nav-btn-mobile-${qNo}`);
        btn.removeClass('bg-slate-50 border-transparent text-slate-300');
        if (isDoubt) {
            btn.removeClass('bg-indigo-600').addClass('bg-amber-500 border-amber-500 text-white shadow-amber-200 animate-pulse');
        } else {
            btn.removeClass('bg-amber-500 shadow-amber-200 animate-pulse').addClass('bg-indigo-600 border-indigo-600 text-white shadow-indigo-100');
        }
    }

    function saveEssayAnswer(questionId, qNo) {
        const panel = $(`#q-panel-${qNo}`);
        const isDoubt = panel.find('.doubt-checkbox').is(':checked');
        
        // Update local data attribute
        $(`#q-panel-${qNo}`).data('doubtful', isDoubt ? 1 : 0);

        const text = $(`textarea[name="answer_${questionId}"]`).val();
        
        $.post('{{ route("student.cbt.save-answer", $exam->id) }}', {
            _token: '{{ csrf_token() }}',
            question_id: questionId,
            answer_text: text,
            is_doubtful: isDoubt ? 1 : 0
        });

        const btn = $(`#nav-btn-desktop-${qNo}, #nav-btn-mobile-${qNo}`);
        if (text.trim() !== '') {
            btn.removeClass('bg-slate-50 border-transparent text-slate-300');
            if (isDoubt) {
                btn.removeClass('bg-indigo-600').addClass('bg-amber-500 border-amber-500 text-white shadow-amber-200 animate-pulse');
            } else {
                btn.removeClass('bg-amber-500 shadow-amber-200 animate-pulse').addClass('bg-indigo-600 border-indigo-600 text-white shadow-indigo-100');
            }
        } else {
            btn.removeClass('bg-indigo-600 bg-amber-500 shadow-amber-200 animate-pulse text-white').addClass('bg-slate-50 border-slate-100 text-slate-300');
        }
    }

    function finishExam() {
        Swal.fire({
            title: 'Selesai Ujian?',
            text: "Data yang sudah dikirim tidak dapat diubah kembali.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'YA, SELESAI',
            cancelButtonText: 'BATAL',
            customClass: { popup: 'rounded-[3rem]' }
        }).then((res) => { if (res.isConfirmed) $('#finish-form').submit(); });
    }

    function forceSubmitExam() { $('#finish-form').submit(); }

    function initAntiCheat() {
        document.addEventListener('contextmenu', e => e.preventDefault());
        window.addEventListener('blur', reportViolation);
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                reportViolation();
                Swal.fire({ icon: 'error', title: 'PELANGGARAN!', text: 'Sistem mendeteksi Anda keluar dari layar penuh.', confirmButtonText: 'KEMBALI UJIAN', allowOutsideClick: false, customClass: { popup: 'rounded-[2.5rem]' } }).then(() => document.documentElement.requestFullscreen());
            }
        });
    }

    function reportViolation() {
        $.post('{{ route("student.cbt.report-violation", $exam->id) }}', { _token: '{{ csrf_token() }}' }).done(res => {
            if (res.violation_count !== undefined) {
                $('#violation-count').text(res.violation_count);
            }
            
            if (res.action === 'force_submit') {
                Swal.fire({ icon: 'error', title: 'DISKUALIFIKASI', text: res.message, showConfirmButton: false, allowOutsideClick: false, customClass: { popup: 'rounded-[2.5rem]' } });
                setTimeout(() => forceSubmitExam(), 2000);
            }
        });
    }
</script>
@endpush
